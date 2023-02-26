<?php

require_once $_SESSION['config']['vendor_dir'] . '/vendor/autoload.php';
require_once('db/AwsDynamoDB.php');
require_once('db/validation.php');

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
	protected $primaryField;
	protected $data;

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
	protected function Connect(): int
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

	/**
	 * Get and Save Item data into $data
	 * @param string $primaryValue Value for Primary Key
	 * @return TRUE of Error code
	 */
	protected function GetItem(string $primaryValue)
	{
		try {
			$this->data = $this->client->getItem(
				array(
					'ConsistentRead' => true,
					'TableName' => $this->tableName,
					'Key' => array(
						$this->primaryField => ['S' => $primaryValue]
					)
				)
			);
			return true;
		} catch (AwsException $e) {
			return $e->getStatusCode();
		}
	}

	/**
	 *
	 * @param array $fields Fields for searching
	 * @param array $fieldValues Fields value
	 * @return mixed Array of all finded items or FALSE if errors in data
	 */
	public function FindItem(array $fields, array $fieldValues, $compareOperators)
	{
		if (!Validation::CompareArrayLengths([$fields, $fieldValues, $compareOperators])) {
			return false;
		}

		// Create Scan Filter
		$scanFilter = array();
		foreach ($fields as $index => $field) {
			$scanFilter[$field] = array(
				'AttributeValueList' => array(
					array(strtoupper(substr(gettype($fieldValues[$index]), 0, 1)) => $fieldValues[$index])
				),
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

	protected function UpdateItem(array $updateFields, array $fieldValues, array $removeFields)
	{


	}

	protected function AddItem($primaryValue, array $fields, array $fieldValues)
	{

	}

}

?>
