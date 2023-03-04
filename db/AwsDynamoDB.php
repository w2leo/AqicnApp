<?php

require_once $_SESSION['config']['vendor_dir'] . '/vendor/autoload.php';
require_once('db/AwsDynamoDB.php');
require_once('db/Validation.php');

use Aws\DynamoDb\DynamoDbClient;
use Aws\Exception\AwsException;

abstract class AwsDynamoDB
{
	/**
	 * Status Code
	 * 200 - OK
	 * 400 - table doesn't exists
	 */
	protected int $isConnect;

	public function GetConnectionStatus()
	{
		return $this->isConnect;
	}

	protected $client;
	protected $tableName;
	protected $primaryField;
	protected $data;

	protected $connectionData;

	protected function __construct()
	{
		$this->isConnect = $this->Connect();
	}

	/**
	 * @return int StatusCode :
	 * 200 - table exists
	 * 400 - table doesn't exists
	 */
	protected function Connect(): int
	{
		try {
			$this->client = DynamoDbClient::factory($this->connectionData);
			$result = $this->client->describeTable(
				array(
					'TableName' => $this->tableName
				)
			);
			return $this->GetStatusCode($result) == 200;
		} catch (AwsException $e) {
			return $e->getStatusCode();
		}
	}

	/**
	 * Get and Save Item data into $data
	 * @param string $primaryValue Value for Primary Key
	 * @return mixed Status code (200 ).
	 */
	protected function GetItem(string $primaryValue)
	{
		try {
			$result = $this->client->getItem(
				array(
					'ConsistentRead' => true,
					'TableName' => $this->tableName,
					'Key' => array(
						$this->primaryField => ['S' => $primaryValue]
					)
				)

			);
			$this->data = $result['Item'];
			return 200;
		} catch (AwsException $e) {
			unset($this->data);
			return $e->getStatusCode();
		}
	}

	/**
	 *
	 * @param array $fields Fields for searching
	 * @param array $fieldValues Fields value
	 * @param array $compareOperators Comprasion operators for each field
	 * @return mixed Array of all finded items or FALSE if errors in data
	 */
	protected function FindItems(array $fields, array $fieldValues, array $compareOperators)
	{
		if (!Validation::CompareArrayLengths([$fields, $fieldValues, $compareOperators])) {
			return false;
		}

		// Create Scan Filter
		$scanFilter = array();
		foreach ($fields as $index => $field) {
			$scanFilter[$field] = array(
				'AttributeValueList' => array($this->Format($fieldValues[$index])),
				'ComparisonOperator' => $compareOperators[$index]
			);
		}

		$iterator = $this->client->getIterator(
			'Scan',
			array(
				'TableName' => $this->tableName,
				'ScanFilter' => $scanFilter
			)
		);

		return iterator_to_array($iterator, true);
	}

	/**
	 * Save new data to $data
	 * @param array $primaryValue Primary value
	 * @param array $removeFields Fields names to remove
	 * @return mixed Status code of operation
	 */
	protected function RemoveFields($primaryValue, array $removeFields)
	{
		if (count($removeFields) == 0) {
			return false;
		}

		foreach ($removeFields as $index => $item) {
			if ($index == 0) {
				$updateExpression = 'REMOVE ';
			}
			$updateExpression .= $item . ', ';
		}
		$updateExpression = rtrim($updateExpression, ', ');

		$result = $this->client->updateItem(
			array(
				'TableName' => $this->tableName,
				'Key' => array(
					$this->primaryField => $this->Format($primaryValue)
				),
				'UpdateExpression' => $updateExpression,
				'ReturnValues' => 'ALL_NEW'
			)
		);

		if (isset($result['Attributes'][$this->primaryField][Validation::GetAwsType($primaryValue)])) {
			$this->data = $result['Attributes'];
			return $this->GetStatusCode($result);
		}
	}

	/**
	 * Save new data to $data
	 * @param array $primaryValue Primary value
	 * @param array $updateFields Fields names to remove
	 * @param array $fieldValues Fields values
	 * @return mixed Status code of operation
	 */
	protected function UpdateItem($primaryValue, array $updateFields, array $fieldValues)
	{
		if (!Validation::CompareArrayLengths([$updateFields, $fieldValues])) {
			return false;
		}

		$updateExpression = 'SET ';
		$expressionAttributeValues = array();

		foreach ($updateFields as $index => $item) {
			$updateExpression .= $item . ' = :f' . $index . ', ';
			$expressionAttributeValues[':f' . $index] = $this->Format($fieldValues[$index]);
		}
		$updateExpression = rtrim($updateExpression, ', ');

		$result = $this->client->updateItem(
			array(
				'TableName' => $this->tableName,
				'Key' => array(
					$this->primaryField => $this->Format($primaryValue)
				),
				'UpdateExpression' => $updateExpression,
				'ExpressionAttributeValues' => $expressionAttributeValues,
				'ReturnValues' => 'ALL_NEW'
			)
		);

		if (isset($result['Attributes'][$this->primaryField][Validation::GetAwsType($primaryValue)])) {
			$this->data = $result['Attributes'];
			return $this->GetStatusCode($result);
		}
		return false;
	}

	/**
	 * Add item to DB
	 * @param string $primaryValue Primary Value
	 * @param array $fields Fields for searching
	 * @param array $fieldValues Fields value
	 * @return mixed Returns status code after insert 200 - ok, 400 - error or FALSE
	 */
	protected function AddItem($primaryValue, array $fields, array $fieldValues)
	{
		if (!Validation::CompareArrayLengths([$fields, $fieldValues])) {
			return false;
		}

		$itemData = array();
		$itemData[$this->primaryField] = $this->Format($primaryValue);
		foreach ($fields as $index => $field) {
			$itemData[$field] = $this->Format($fieldValues[$index]);
		}

		try {
			$result = $this->client->putItem(
				array(
					'TableName' => $this->tableName,
					'Item' => $itemData,
					'ConditionExpression' => 'attribute_not_exists(Login)'
				)
			);
			$statusCode = $this->GetStatusCode($result);
		} catch (AwsException $e) {
			$statusCode = $e->getStatusCode();
		} finally {
			return $statusCode;
		}
	}

	/**
	 *
	 * @param array $primaryValue Primary value
	 * @return mixed Returns deleted primary value of null
	 */
	protected function DeleteItem($primaryValue)
	{
		$result = $this->client->deleteItem(
			array(
				'TableName' => $this->tableName,
				'Key' => array(
					$this->primaryField => $this->Format($primaryValue)
				),
				'ReturnValues' => 'ALL_OLD'
			)
		);

		if (isset($result['Attributes'][$this->primaryField][Validation::GetAwsType($primaryValue)]))
			return $result['Attributes'][$this->primaryField][Validation::GetAwsType($primaryValue)];
	}

	protected function Format($value)
	{
		return array(Validation::GetAwsType($value) => $value);
	}

	protected function GetStatusCode($result)
	{
		return $result->get('@metadata')['statusCode'];
	}
}

?>
