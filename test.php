<?php
//enable input bufferization
ob_start();

session_start();

// init session from config.ini file
$_SESSION['config'] = parse_ini_file("config.ini", true)[$_SERVER['SERVER_NAME']];



require_once('db/Validation.php');
require_once('handlers/RequestHandler.php');
require_once('db/AwsSES.php');
require_once('db/AwsUsersData.php');


$db = new AwsUsersData();

echo $db->Login('qwe','q')->value;

$ses = new AwsSES();

echo $ses->SendEmail('test@rfbuild.ru', 'test message');


?>
