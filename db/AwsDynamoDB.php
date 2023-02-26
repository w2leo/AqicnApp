<?php

abstract class AwsDynamoDB
{
	protected $client;
	protected $tableName;
	protected $connectionData = array(
		'region' => 'us-east-1',
		'version' => 'latest'
	);

	abstract public function __construct();

	abstract protected function Connect();

	abstract public function GetItem($primaryField, $primaryValue);

	abstract public function FindItem($fields, $fieldValues);

	abstract public function UpdateItem($updateFields, $fieldValues, $removeFields);

	abstract public function AddItem($primaryField, $primaryValue, $fields, $fieldValues);

}

?>
