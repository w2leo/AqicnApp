<?php

require_once $_SESSION['config']['vendor_dir'] . '/vendor/autoload.php';
require_once('db/AwsDynamoDB.php');

use Aws\DynamoDb\DynamoDbClient;
use Aws\Exception\AwsException;

enum UserDataFields
{
	case Email;
	case Password;
	case ConfirmationToken;
	case RecoveryToken;
	case Cities;
	case Subscription;
}

enum UserDataReturnValues: string
{
	case Sucsess = 'Sucsess';
	case Fail = 'Failed';
	case WrongCredentials = 'Login/password wrong';
	case NotConfirmedEmail = 'Confirm email first';
	case UserExists = 'User with such login&email exists';
	case UserNotExists = 'No such user';
	case EmailConfirmed = 'Email confirmed';
	case WrongToken = 'Wrong Token for';
	case RightToken = 'Token matched';
	case PasswordChanded = 'Password Sucsessfully changed';

}

final class AwsUsersData extends AwsDynamoDB
{
	/**
	 * Constructor for connecting to table UsersData
	 */
	public function __construct()
	{
		$this->connectionData = array(
			'region' => 'us-east-1',
			'version' => 'latest'
		);
		$this->primaryField = 'Login';
		$this->tableName = 'UsersData';
		parent::__construct();
	}

	public function CheckUserExists($login)
	{
		$data = self::FindItems([$this->primaryField], [$login], ['EQ']);
		return count($data) > 0 ? UserDataReturnValues::UserExists : UserDataReturnValues::UserNotExists;
	}

	public function CheckUserEmailExists($login, $email)
	{
		$data = self::FindItems([$this->primaryField, UserDataFields::Email->name], [$login, $email], ['EQ', 'EQ']);
		return count($data) > 0 ? UserDataReturnValues::UserExists : UserDataReturnValues::UserNotExists;
	}

	public function AddUser($login, $passwordHash, $email, $confirmationToken)
	{
		if (self::CheckUserExists($login, $email) == UserDataReturnValues::UserNotExists) {
			$result = self::AddItem(
				$login,
				[UserDataFields::Email->name, UserDataFields::Password->name, UserDataFields::ConfirmationToken->name],
				[$email, $passwordHash, $confirmationToken]
			);
			return $result == 200 ? UserDataReturnValues::Sucsess : UserDataReturnValues::Fail;
		}
		return UserDataReturnValues::UserExists;
	}

	public function Login($login, $password)
	{
		$status = false;
		self::GetItem($login);

		$e1 = isset($this->data);
		$e2 = password_verify($password, $this->data[UserDataFields::Password->name]['S']);
		if (isset($this->data) && password_verify($password, $this->data[UserDataFields::Password->name]['S'])) {
			self::RemoveRecoveryToken($login);
			return self::CheckConfirmationToken($login) ? UserDataReturnValues::NotConfirmedEmail : UserDataReturnValues::Sucsess;
		}
		unset($this->data);
		return UserDataReturnValues::WrongCredentials;
	}

	private function CheckConfirmationToken($login)
	{
		$userData = self::FindItems([$this->primaryField],[$login], ['EQ']);
		$result = isset($userData[0][UserDataFields::ConfirmationToken->name]);
		return $result;
	}

	public function ConfirmEmail($login, $confirmationToken)
	{
		$userData = self::FindItems(
			[$this->primaryField, UserDataFields::ConfirmationToken->name],
			[$login, $confirmationToken],
			['EQ', 'EQ']
		);
		if (isset($userData[0][UserDataFields::ConfirmationToken->name]['S'])) {
			if (self::RemoveFields($login, [UserDataFields::ConfirmationToken->name]) == 200)
				return UserDataReturnValues::EmailConfirmed;
		}
		return UserDataReturnValues::WrongToken;
	}

	public function AddRecoveryToken($login, $recoveryToken)
	{
		if (!self::CheckConfirmationToken($login)) {
			self::UpdateItem($login, [UserDataFields::RecoveryToken->name], [$recoveryToken]);
			return UserDataReturnValues::Sucsess;
			;
		} else
			return UserDataReturnValues::NotConfirmedEmail;
	}

	public function CheckRecoveryToken($login, $recoveryToken)
	{
		$data = self::FindItems([$this->primaryField, UserDataFields::RecoveryToken->name], [$login, $recoveryToken], ['EQ', 'EQ']);
		return count($data) > 0 ? UserDataReturnValues::RightToken : UserDataReturnValues::WrongToken;
	}

	private function RemoveRecoveryToken($login)
	{
		if (isset($this->data[UserDataFields::RecoveryToken->name])) {
			self::RemoveFields($login, [UserDataFields::RecoveryToken->name]);

			//TODO
			// Remove GetItem() and only in $this->data change value
			self::GetItem($login);

		}
	}

	public function ChangePassword($login, $newPasswordHash, $recoveryToken)
	{
		if (self::CheckRecoveryToken($login, $recoveryToken) == UserDataReturnValues::RightToken) {
			self::RemoveRecoveryToken($login);
			self::UpdateItem($login, [UserDataFields::Password->name], [$newPasswordHash]);
			return UserDataReturnValues::PasswordChanded;
		} else {
			return UserDataReturnValues::WrongCredentials;

		}
	}

	public function AddCity($cities)
	{
		if (isset($this->data[$this->primaryField])) {
			if (is_array($cities)) {
				foreach ($cities as $city) {
					$this->data[UserDataFields::Cities->name][] = $city;
				}
			} else {

				$this->data[UserDataFields::Cities->name][] = $cities;
			}
			sort($this->data[UserDataFields::Cities->name]);
			return self::UpdateUserData([UserDataFields::Cities->name], [$this->data[UserDataFields::Cities->name]]);
		}
		return false;
	}

	public function RemoveCity($city)
	{
		unset($array[array_search($city, $this->data[UserDataFields::Cities->name])]);
		sort($this->data[UserDataFields::Cities->name]);
		return self::UpdateUserData([UserDataFields::Cities->name], [$this->data[UserDataFields::Cities->name]]);
	}

	public function ChangeSubscription()
	{
		$this->data[UserDataFields::Subscription->name] = !$this->data[UserDataFields::Subscription->name];
		return self::UpdateUserData([UserDataFields::Subscription->name], [$this->data[UserDataFields::Subscription->name]]);
	}

	private function UpdateUserData($fields, $values)
	{
		if (self::UpdateItem($this->data[$this->primaryField], $fields, $values)) {
			return true;
		} else {
			self::GetItem($this->data[$this->primaryField]);
		}
	}

	public function GetEmail($login)
	{
		$userData = self::FindItems([$this->primaryField], [$login], ['EQ']);
		return $userData[0][UserDataFields::Email->name]['S'];
	}

	public function GetData($login)
	{
		if (isset($this->data) && $login == $this->data[$this->primaryField]['S']) {
			self::GetItem($login);
			return $this->data;
		}
	}
}

?>