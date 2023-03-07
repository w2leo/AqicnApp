<?php

require_once $_SESSION['config']['vendor_dir'] . '/vendor/autoload.php';
require_once('AwsDynamoDB.php');

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
	case UserExists = 'User with such login/email exists';
	case UserNotExists = 'No such user';
	case EmailConfirmed = 'Email confirmed';
	case WrongToken = 'Wrong Token for user';
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
		//$credentials = parse_ini_file($_SESSION['config']['vendor_dir'] . "/aws.ini", true)['default'];

		$this->connectionData = array(
			'region' => 'us-east-1',
			'version' => 'latest',
			'profile' => 'default',
			'credentials' => [
				'key' => 'AKIASIUJXHEW52Q7LMGN',
				'secret' => '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'
			]
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

	public function CheckEmailExists($email)
	{
		$field = UserDataFields::Email->name;
		$data = self::FindItems([UserDataFields::Email->name], [$email], ['EQ']);
		return count($data) > 0 ? UserDataReturnValues::UserExists : UserDataReturnValues::UserNotExists;
	}

	public function CheckUserEmailExists($login, $email)
	{
		$result = $this->CheckUserExists($login);
		if ($result == UserDataReturnValues::UserExists) {
			return $result;
		}
		$result = $this->CheckEmailExists($email);
		return $result;
	}

	public function AddUser($login, $passwordHash, $email, $confirmationToken)
	{
		if (self::CheckUserEmailExists($login, $email) == UserDataReturnValues::UserNotExists) {
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
		self::GetItem($login);
		if (isset($this->data) && password_verify($password, $this->data[UserDataFields::Password->name]['S'])) {
			self::RemoveRecoveryToken($login);
			return self::CheckConfirmationToken($login) ? UserDataReturnValues::NotConfirmedEmail : UserDataReturnValues::Sucsess;
		}
		unset($this->data);
		return UserDataReturnValues::WrongCredentials;
	}

	private function CheckConfirmationToken($login)
	{
		$userData = self::FindItems([$this->primaryField], [$login], ['EQ']);
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
				$this->data[UserDataFields::Cities->name]['SS'][] = $cities;
			}
			$newCityList = array_unique($this->data[UserDataFields::Cities->name]['SS']);
			$res = self::UpdateItem(
				$this->data[$this->primaryField]['S'],
				[UserDataFields::Cities->name],
				[$newCityList]
			);
			$this->UpdateSessionData();
			return $res == 200 ? UserDataReturnValues::Sucsess : UserDataReturnValues::Fail;
		}
		return false;
	}

	public function RemoveCity($city)
	{
		if (isset($this->data[UserDataFields::Cities->name])) {
			$oldArray = $this->data[UserDataFields::Cities->name]['SS'];
			$key = array_search($city, $oldArray);
			unset($oldArray[$key]);
			$newCityList = array_values($oldArray);

			if (count($newCityList) > 0) {
				$res = self::UpdateItem(
					$this->data[$this->primaryField]['S'],
					[UserDataFields::Cities->name],
					[$newCityList]
				);
			} else {
				$prv = $this->data[$this->primaryField]['S'];
				$arrFields = [UserDataFields::Cities->name];
				$res = self::RemoveFields($prv, $arrFields);
			}
			$this->UpdateSessionData();
			return $res == 200 ? UserDataReturnValues::Sucsess : UserDataReturnValues::Fail;
		}
		return false;
	}

	public function ChangeSubscription()
	{
		if (isset($this->data[UserDataFields::Subscription->name])) {
			$newValue = !$this->data[UserDataFields::Subscription->name]['B'];
		} else {
			$newValue = true;
		}
		$res = self::UpdateItem(
			$this->data[$this->primaryField]['S'],
			[UserDataFields::Subscription->name],
			[$newValue]
		);
		return $res == 200 ? UserDataReturnValues::Sucsess : UserDataReturnValues::Fail;
	}

	private function UpdateSessionData()
	{
		$_SESSION['userData'] = $this->data;
	}

	public function GetEmail($login)
	{
		$userData = self::FindItems([$this->primaryField], [$login], ['EQ']);
		return $userData[0][UserDataFields::Email->name]['S'];
	}

	public function GetData($login)
	{
		self::GetItem($login);
		return $this->data;
	}
}

?>
