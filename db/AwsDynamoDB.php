<?php
use Vtiful\Kernel\Format;

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
	 * @return mixed TRUE of Error code
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
	public function FindItem(array $fields, array $fieldValues, array $compareOperators)
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

	protected function UpdateItem(array $updateFields, array $fieldValues, array $removeFields)
	{


	}

	/**
	 * Add item to DB
	 * @param string $primaryValue Primary Value
	 * @param array $fields Fields for searching
	 * @param array $fieldValues Fields value
	 * @return mixed StatusCode or FALSE if errors in data
	 */
	public function AddItem($primaryValue, array $fields, array $fieldValues)
	{
		if (!Validation::CompareArrayLengths([$fields, $fieldValues])) {
			return false;
		}

		$itemData = array();
		$itemData[$this->primaryField] = $this->Format($primaryValue);
		foreach ($fields as $index => $field) {
			$itemData[$field] = $this->Format($fieldValues[$index]);
		}

		$result = $this->client->putItem(
			array(
				'TableName' => $this->tableName,
				'Item' => $itemData
			)
		);

		return $result->get('@metadata')['statusCode'];
	}

	protected function DeleteItem($primaryValue)
	{

	}

	protected function Format($value)
	{
		return array(Validation::GetAwsType($value) => $value);
	}
}

?>
