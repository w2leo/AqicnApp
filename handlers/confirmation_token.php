<?php
require_once('db/AwsUsersData.php');
require_once('db/udf.php');

$login = $_GET['login'];
$confirmation_token = $_GET['confirmation_token'];
$db = new AwsUsersData();

$result = $db->ConfirmEmail($login, $confirmation_token);

ExitPage($result->value);

?>
