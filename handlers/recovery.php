<?php

require_once('db/validation.php');
require_once('db/awsses.php');
require_once('db/dynamoDB.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$login = Validation::checkInput($_POST['login'] ?? '') ? $_POST['login'] ?? '' : '';
	$db = new DynamoDb();

	$db->CheckLoginExists($login);

	if (!$db->CheckLoginExists($login)) {
		$_SESSION['message'][] = 'Unknown login';
		header('Location: /');
		exit;
	}

	if (!$db->GetVerifiedLogin($login)) {
		$_SESSION['message'][] = 'Before password recovery - confirm email first';
		header('Location: /');
		exit;
	}

	$email = $db->GetEmail($login);
	$recovery_token = bin2hex(random_bytes(40));
	$_SESSION['recovery_token'] = $recovery_token;
	$_SESSION['email'] = $email;

	$msg = "Password recovery at {$_SERVER['SERVER_NAME']}";
	$msg = "If you try to recover your password, click on <a href=\"http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}?login=" . $login . "&recovery_token=" . $recovery_token . "\">link</a> to set new password";
	$mail = new AwsSES();
	$mail->SendEmail($email, $msg);
	$_SESSION['message'][] = 'Recovery link was sent to email';
	header('Location: /');
	exit;
}
