<?php

require_once('db/AwsUsersData.php');
require_once('db/udf.php');

if (isset($_GET['recovery_token']) && isset($_GET['login'])) {

	$login = $_GET['login'];
	$recovery_token = $_GET["recovery_token"];

	$db = new AwsUsersData();
	$result = $db->CheckRecoveryToken($login, $recovery_token);

	if ($result == UserDataReturnValues::RightToken) {
		$_SESSION['login'] = $login;
		$_SESSION['recovery_token'] = $recovery_token;

		include "handlers/set_new_password.php";
		include "views/set_new_password.php";
		exit;
	} else {
		ExitPage($result->value);
	}
}
