<?php
require_once('db/awsses.php');
require_once('db/validation.php');

$login = '';
$email = '';
$pass1 = '';
$pass2 = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$email = filter_input(
		INPUT_POST,
		'email',
		FILTER_VALIDATE_EMAIL
	);

	$login = Validation::checkInput($_POST['login'] ?? '') ? $_POST['login'] ?? '' : '';
	$pass1 = Validation::checkInput($_POST['password'] ?? '') ? $_POST['password'] ?? '' : '';
	$pass2 = Validation::checkInput($_POST['confirmPassword'] ?? '') ? $_POST['confirmPassword'] ?? '' : '';

	// $_SESSION['field_email'][]=$email;
	// $_SESSION['field_login'][]=$login;

	$_SESSION['message'] = [];
	if (!$email) {
		$_SESSION['message'][] = 'Incorrect email';

		header('Location: /?signup');
		exit;
	}
	if (!$pass1 || !$pass2) {
		$_SESSION['message'][] = 'Incorrect password';
		header('Location: /?signup');
		exit;
	}
	if ($pass1 != $pass2) {
		$_SESSION['message'][] = 'Passwords doesn\'t match';
		header('Location: /?signup');
		exit;
	}

	$db = new DynamoDb();
	if ($db->CheckLoginExists($login)) {
		$_SESSION['message'][] = 'Login already exists';
		header('Location: /?signup');
		exit;
	}


	$confirmationToken = bin2hex(random_bytes(40));

	$db->AddUser($login, $email, $pass1, $confirmationToken);

	$_SESSION['message'][] = 'Check email for verifying link';
	$msg = "<h1>Confirm registration on {$_SERVER['SERVER_NAME']}</h1>";
	$msg .= "Click on  <a href=\"http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}?login=" . $login . "&confirmation_token=" . $confirmationToken . "\">link</a> to confirm email";
	$_SESSION['message'][] = $msg;

	$mail = new awsMail();
	$mail->SendEmail($email, $msg);

	header('Location: /');
	exit;
}
