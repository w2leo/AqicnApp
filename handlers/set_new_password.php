<?php

require_once('db/Validation.php');
require_once('db/AwsSES.php');
require_once('db/AwsUsersData.php');
require_once('db/udf.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$login = $_SESSION['login'];
	$recovery_token = $_SESSION['recovery_token'];

	$pass1 = Validation::CheckInput($_POST['pass1'] ?? '') ? $_POST['pass1'] : '';
	$pass2 = Validation::CheckInput($_POST['pass2'] ?? '') ? $_POST['pass2'] : '';

	$_SESSION['message'] = [];

	if ($pass1 != $pass2 || !$pass1 || !$pass2) {
		ExitPage('Passwords doesn\'t match or empty');
	}

	$db=new AwsUsersData();
	$passwordHash = password_hash($pass1, PASSWORD_DEFAULT);
	$result = $db->ChangePassword($login, $passwordHash, $recovery_token);
	ExitPage($result->value);
}
