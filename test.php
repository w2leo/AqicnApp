<?php

ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting (E_ALL);

//enable input bufferization
ob_start();

session_start();

// init session from config.ini file
$_SESSION['config'] = parse_ini_file("config.ini", true)[$_SERVER['SERVER_NAME']];


try {
require_once('db/Validation.php');
require_once('handlers/RequestHandler.php');
require_once('db/AwsSES.php');
require_once('db/AwsUsersData.php');
} catch (Error $e)
{
	echo 'Error include file';
}
try {
$db = new AwsUsersData();
}
catch (Error $e)
{
	echo 'Connect db error';
}
try {
echo $db->Login('qwe','q')->value;
}catch (Error $e) {
	echo 'Error db send';
}

try {
$ses = new AwsSES();
} catch (Error $e) {
	echo 'Error create ses';
}

try {
echo $ses->SendEmail('test@rfbuild.ru', 'test message');
} catch (Error $e) {
	echo 'Error send mail';
}

?>
