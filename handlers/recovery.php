<?php

require_once('db/Validation.php');
require_once('db/AwsSES.php');
require_once('db/AwsUsersData.php');
require_once('db/udf.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$login = Validation::CheckInput($_POST['recovery'] ?? '') ? $_POST['recovery'] : '';

	if ($login=='') {
		ExitPage('Incorrect login');
	}
	$db = new AwsUsersData();

	$result = $db->CheckUserExists($login);

	if ($result == UserDataReturnValues::UserExists) {
		$recovery_token = bin2hex(random_bytes(40));
		$result = $db->AddRecoveryToken($login, $recovery_token);

		if ($result != UserDataReturnValues::NotConfirmedEmail) {
			$email = $db->GetEmail($login);
			$msg = "Password recovery at {$_SERVER['SERVER_NAME']}";
			$msg = "If you try to recover your password, click on <a href=\"http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}?login=" . $login . "&recovery_token=" . $recovery_token . "\">link</a> to set new password";
			$mail = new AwsSES();
			$mail->SendEmail($email, $msg);
		}
	}

	ExitPage($result->value);
}
