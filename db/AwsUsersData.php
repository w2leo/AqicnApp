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
		$this->primaryField = 'Login';
		$this->tableName = 'UsersData';
		parent::__construct();
	}

	public function GetInfo()
	{
		var_dump($this->client->describeTable(
			array(
				'TableName' => $this->tableName
			)
			));
	}
}

?>
