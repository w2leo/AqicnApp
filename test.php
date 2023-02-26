<?php
ob_start();

session_start();
$_SESSION['config'] = parse_ini_file("config.ini", true)['localhost'];

require_once('db/AwsUsersData.php');

$test = new AwsUsersData();
echo $test->Status;

?>
