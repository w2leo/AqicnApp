<?php
echo php_uname().'<br>';
echo PHP_OS;

if (strripos(php_uname(), 'MacBook'))
$v_dir = '/Users/mikhailleonov';
else
$v_dir = '.';

require $v_dir.'/vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;

class DynamoDb
{
	private $client;
	private $result;
	private $connectionData = array(
		'region' => 'us-east-1',
		'version' => 'latest',
		'credentials' => [
			'key' => 'AKIASIUJXHEWQ5XZ3MWC',
			'secret' => '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',
		]
	);

	private function ConnectDB()
	{
		$this->client = DynamoDbClient::factory($this->connectionData);
	}

	private function GetUserData($login)
	{
		$this->ConnectDB();
		$this->result = $this->client->getItem(
			array(
				'ConsistentRead' => true,
				'TableName' => 'UsersData',
				'Key' => array(
					'Login' => ['S' => $login]
				)
			)
		);
		return $this->result;
	}

	public function CheckLoginExists($login): bool
	{
		if (isset($this->GetUserData($login)['Item']['Login']['S']))
			return true;

		return false;
	}

	public function Login($login, $password)
	{
		$result = $this->GetUserData($login);
		$dbHash = $this->result['Item']['Password']['S'];
		if (password_verify($password, $dbHash)) {
			return $result;
		}

		return false;
	}

	public function GetVerifiedLogin($login):string
	{
		if ($this->CheckLoginExists($login))
		{
			$result = $this->GetUserData($login);
			return $result['Item']['VerifiedEmail']['BOOL'] ? $result['Item']['Login']['S'] : false;
		}
		return false;
	}

	public function GetEmail($login)
	{
		$result = $this->GetUserData($login);
		return $result['Item']['Email']['S'];
	}

	public function AddUser($login, $email, $password, $confirmationToken): int
	{
		if (!$this->CheckLoginExists($login)) {
			$password = password_hash($password, PASSWORD_DEFAULT);
			$this->result = $this->client->putItem(
				array(
					'TableName' => 'UsersData',
					'Item' => array(
						'Login' => ['S' => $login],
						'Email' => ['S' => $email],
						'Password' => ['S' => $password],
						'ConfirmationToken' => ['S' => $confirmationToken],
						'VerifiedEmail' => ['BOOL' => false]
					)
				)
			);
		}
		// REFACTOR LATER
		return $this->result->get('@metadata')['statusCode'] ?? 0;
		;
	}

	public function UpdatePassword($login, $newPassword)
	{
		$login_str = strval($login);
		$password = password_hash($newPassword, PASSWORD_DEFAULT);
		$this->ConnectDB();

		$result = $this->client->updateItem(
			array(
				'TableName' => 'UsersData',
				'Key' => array(
					'Login' => array('S' => $login_str)
				),
				'UpdateExpression' => 'SET Password = :v',
				'ExpressionAttributeValues' => array(
					':v' => array('S' => $password)
				)
			)
		);

	}


	public function CheckConfirmationToken($login, $token)
	{
		$this->ConnectDB();
		$iterator = $this->client->getIterator(
			'Scan',
			array(
				'TableName' => 'UsersData',
				'ScanFilter' => array(
					'Login' => array(
						'AttributeValueList' => array(
							array('S' => $login)
						),
						'ComparisonOperator' => 'EQ'
					),
					'ConfirmationToken' => array(
						'AttributeValueList' => array(
							array('S' => $token)
						),
						'ComparisonOperator' => 'EQ'
					)
				)
			)
		);

		foreach ($iterator as $item) {
			// Get the values from the item using the 'S' or 'N' key to access the string or number data types, respectively
			$email = $item['Email']['S'];
			$verified = $item['VerifiedEmail']['BOOL'];

			if (!$verified) {
				$result = $this->client->updateItem(
					array(
						'TableName' => 'UsersData',
						'Key' => array(
							'Login' => array('S' => $login)
						),
						'UpdateExpression' => 'SET VerifiedEmail = :v REMOVE ConfirmationToken',
						'ExpressionAttributeValues' => array(
							':v' => array('BOOL' => true)
						),
						'ReturnValues' => 'ALL_NEW'
					)
				);

				return true;
			}
		}
		return false;

	}

	public function updateItem($Login, $newData)
	{

		$client = DynamoDbClient::factory($this->connectionData);

		$tableName = 'UsersData';
		$key = [
			'Login' => ['S' => 'Pavel'],
		];

		//ParseNewData into $updateExpression
		$updateExpression = "set Email = :newValue,	MainCity = :newMainCity,MainCitySubscribe = :flagSubscribe,	SubscribedCities = :newCities";
		$expressionAttributeValues = [
			':newValue' => ['S' => 'pavel@test.ru'],
			':newMainCity' => ['S' => 'MSK'],
			':flagSubscribe' => ['S' => 'true'],
			':newCities' => ['SS' => ['m1', 'm2']]
		];

		$result = $client->updateItem([
			'TableName' => $tableName,
			'Key' => $key,
			'UpdateExpression' => $updateExpression,
			'ExpressionAttributeValues' => $expressionAttributeValues,
			'ReturnValues' => 'UPDATED_NEW',
		]);

		var_dump($result);
		return $result;
	}
}

?>
