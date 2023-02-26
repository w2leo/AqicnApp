<?php

require $_SESSION['config']['vendor_dir'] . '/vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;
use Aws\Exception\AwsException;


require_once('db/AwsDynamoDB.php');

final class AwsUsersData extends AwsDynamoDB
{
	/**
	 * @return mixed
	 */
	public function __construct()
	{
		$this->tableName = 'UsersData';
		$this->Connect();
	}

	/**
	 * @return mixed
	 */
	protected function Connect()
	{
		try {
			$this->client = DynamoDbClient::factory($this->connectionData);
			// var_dump($this->client);
			$this->client->describeTable(
				array(
					'TableName' => $this->tableName
				)
			);
		} catch (AwsException $e) {
			echo $e->getStatusCode().'<br>';
		}
	}

	/**
	 *
	 * @param mixed $primaryField
	 * @param mixed $primaryValue
	 * @return mixed
	 */
	public function GetItem($primaryField, $primaryValue)
	{
	}

	/**
	 *
	 * @param mixed $fields
	 * @param mixed $fieldValues
	 * @return mixed
	 */
	public function FindItem($fields, $fieldValues)
	{
	}

	/**
	 *
	 * @param mixed $updateFields
	 * @param mixed $fieldValues
	 * @param mixed $removeFields
	 * @return mixed
	 */
	public function UpdateItem($updateFields, $fieldValues, $removeFields)
	{
	}

	/**
	 *
	 * @param mixed $primaryField
	 * @param mixed $primaryValue
	 * @param mixed $fields
	 * @param mixed $fieldValues
	 * @return mixed
	 */
	public function AddItem($primaryField, $primaryValue, $fields, $fieldValues)
	{
	}

}

?>
