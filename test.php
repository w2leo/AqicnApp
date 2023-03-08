<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//enable input bufferization
ob_start();

session_start();
require_once '/var/www/vendor/autoload.php';
use Aws\DynamoDb\DynamoDbClient;

$client = DynamoDbClient::factory(array(
    'profile' => 'default',
    'region'  => 'us-east-1'
));

use Aws\Common\Aws;

// Create a service builder using a configuration file
$aws = Aws::factory('/path/to/my_config.json');

// Get the client from the builder by namespace
$client = $aws->get('DynamoDb');

echo 'DynamoDB';
$result = $client->listTables();

// TableNames contains an array of table names
foreach ($result['TableNames'] as $tableName) {
    echo $tableName . "\n";
}

echo 'AWS';
$result = $aws->listTables();

// TableNames contains an array of table names
foreach ($result['TableNames'] as $tableName) {
    echo $tableName . "\n";
}

// init session from config.ini file
// $_SESSION['config'] = parse_ini_file("config.ini", true)[$_SERVER['SERVER_NAME']];


// try {
// 	require_once('db/Validation.php');
// 	require_once('handlers/RequestHandler.php');
// 	require_once('db/AwsSES.php');
// 	require_once('db/AwsUsersData.php');
// } catch (Error $e) {
// 	echo 'Error include file';
// }
// try {
// 	$db = new AwsUsersData();
// } catch (Error $e) {
// 	echo 'Connect db error';
// }
// try {
// 	echo $db->Login('qwe', 'q')->value;
// } catch (Error $e) {
// 	echo 'Error db send';
// }

// try {
// 	$ses = new AwsSES();
// } catch (Error $e) {
// 	echo 'Error create ses';
// }

// try {
// 	echo $ses->SendEmail('test@rfbuild.ru', 'test message');
// } catch (Error $e) {
// 	echo 'Error send mail';
// }

?>
