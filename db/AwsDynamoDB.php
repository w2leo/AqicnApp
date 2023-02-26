<?php

require_once $_SESSION['config']['vendor_dir'] . '/vendor/autoload.php';
require_once('db/AwsDynamoDB.php');

use Aws\DynamoDb\DynamoDbClient;
use Aws\Exception\AwsException;

abstract class AwsDynamoDB
{
	/**
	 * Status Code
	 * 200 - OK
	 * 400 - table doesn't exists
	 */
	public int $Status;
	protected $client;
	protected $tableName;
	protected $connectionData = array(
		'region' => 'us-east-1',
		'version' => 'latest'
	);

	public function __construct()
	{
		$this->Status = $this->Connect();
	}

	/**
	 * @return int StatusCode :
	 * 200 - table exists
	 * 400 - table doesn't exists
	 */
	public function Connect() : int
	{
		try {
			$this->client = DynamoDbClient::factory($this->connectionData);
			$result = $this->client->describeTable(
				array(
					'TableName' => $this->tableName
				)
			);
			return $result->get('@metadata')['statusCode'];
		} catch (AwsException $e) {
			return $e->getStatusCode();
		}
	}

	abstract public function GetItem($primaryField, $primaryValue);

	abstract public function FindItem($fields, $fieldValues);

	abstract public function UpdateItem($updateFields, $fieldValues, $removeFields);

	abstract public function AddItem($primaryField, $primaryValue, $fields, $fieldValues);

}

?>
