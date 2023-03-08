<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//enable input bufferization
ob_start();
session_start();
require_once ('/home/ec2-user/vendor/autoload.php');
use Aws\DynamoDb\DynamoDbClient;
use Aws\Credentials\CredentialProvider;
use Aws\S3\S3Client;
use Aws\Credentials\Credentials;

//$cred = new Credentials('AKIASIUJXHEWU6CEJJHU', 'QGMUJgPqIlLNBziV6J/UgEYFhV$

var_dump($cred);

echo 'CONSTRUCT RUNS \n<br>';

$client = new Aws\DynamoDb\DynamoDbClient(array(
    'region'  => 'us-east-1',
        'profile' => 'default',
        'version' => 'latest',
//      'credentials' => $cred
    'credentials' => new Aws\Credentials\InstanceProfileProvider(),
//      'debug' => true
//      'credentials' => ['key'=>'AKIASIUJXHEWU6CEJJHU','secret'
//      =>'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' ]
));

var_dump($client->getCredentials());

echo 'DynamoDB';
try {
$result = $client->listTables();
} catch (Error $e) {echo 'table error';}
echo 'TABLES';
// TableNames contains an array of table names
foreach ($result['TableNames'] as $tableName) {
    echo $tableName . "\n";
}

?>
