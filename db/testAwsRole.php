<?php

// If necessary, modify the path in the require statement below to refer to the
// location of your Composer autoload.php file.
if (strripos(php_uname(), 'MacBook'))
	$v_dir = '/Users/mikhailleonov';
else
	$v_dir = '.';

require $v_dir . '/vendor/autoload.php';

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;
use Aws\DynamoDb\DynamoDbClient;



class testAws
{

	public $SesConnector;
	public $DbConnector;

	public function ConnectSES()
	{
		$this->SesConnector = new SesClient(
			[
				'version' => 'latest',
				'region' => 'us-east-1'
			]
		);
	}

	public function ConnectDb()
	{
		$connectionData = array(
			'region' => 'us-east-1',
			'version' => 'latest'
		);

		$this->DbConnector = DynamoDbClient::factory($connectionData);
	}

}

?>
