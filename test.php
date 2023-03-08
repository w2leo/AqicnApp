<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//enable input bufferization
ob_start();

session_start();
require_once '/var/www/vendor/autoload.php';
use Aws\DynamoDb\DynamoDbClient;
use Aws\Credentials\CredentialProvider;
use Aws\S3\S3Client;

// Use the default credential provider
$provider = CredentialProvider::defaultProvider();

// Pass the provider to the client
$client = new S3Client([
    'region'      => 'us-west-2',
    'version'     => '2006-03-01',
    'credentials' => $provider
]);


$client = DynamoDbClient::factory(array(
    'profile' => 'default',
    'region'  => 'us-east-1',
	'version' => '2012-08-10',
    'credentials' => $provider
));

echo 'DynamoDB';
$result = $client->listTables();

echo 'TABLES';
// TableNames contains an array of table names
foreach ($result['TableNames'] as $tableName) {
    echo $tableName . "\n";
}

// init session from config.ini file
// $_SESSION['config'] = parse_ini_file("config.ini", true)[$_SERVER['SERVER_NAME']];

$_SESSION['config']['vendor_dir'] = '/var/www';
//try {
//	require_once('db/Validation.php');
//	require_once('handlers/RequestHandler.php');
//	require_once('db/AwsSES.php');
	require_once('db/AwsUsersData.php');

	$db2 = new AwsUsersData();
	echo 'Check:'.$db2->CheckUserExists('qwe')->value;

	$login = 'u'.rand(0,1000);
	$db2->AddUser($login,'passhash',$login.'@rfbuild.ru','confToken');


	require_once('db/AwsSES.php');
	try {
	$ses = new AwsSES();
	} catch (Error $e)
	{
		echo 'some SES error';
	}
	echo 'Mail:'.$ses->SendEmail('mm@rfbuild.ru', 'test message 1');

//} catch (Error $e) {
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
