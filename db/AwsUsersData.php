<?php

require_once $_SESSION['config']['vendor_dir'] . '/vendor/autoload.php';
require_once('db/AwsDynamoDB.php');

use Aws\DynamoDb\DynamoDbClient;
use Aws\Exception\AwsException;

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

	public function CheckUserExists($login, $email = '')
	{
	}

	public function AddUser()
	{
	}

	public function Login()
	{
	}

	public function GetConfirmationToken()
	{
	}

	public function ConfirmEmail()
	{
	}

	public function AddRecoveryToken()
	{
	}

	public function GetRecoveryToken()
	{
	}

	public function ChangePassword()
	{
	}

	public function AddCity()
	{
	}
	public function RemoveCity()
	{
	}
	public function ChangeSubscription()
	{
	}

}

?>
