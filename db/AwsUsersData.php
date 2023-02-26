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
		$this->tableName = 'UsersData';
		parent::__construct();
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
