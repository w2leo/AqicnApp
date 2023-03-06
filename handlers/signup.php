<?php
require_once('db/AwsSES.php');
require_once('db/Validation.php');
require_once('db/AwsUsersData.php');
require_once('db/udf.php');

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

	$login = Validation::CheckInput($_POST['newLogin'] ?? '') ? $_POST['newLogin'] : '';
	$pass1 = Validation::CheckInput($_POST['password'] ?? '') ? $_POST['password'] : '';
	$pass2 = Validation::CheckInput($_POST['confirmPassword'] ?? '') ? $_POST['confirmPassword'] : '';

	$_SESSION['message'] = [];

	if($login=='')
	{
		ExitPage('Incorrect login');
	}
	if (!$email) {
		ExitPage('Incorrect email');
	}
	if ($pass1 != $pass2 || !$pass1 || !$pass2) {
		ExitPage('Passwords doesn\'t match or empty');
	}

	$db = new AwsUsersData();
	$passwordHash = password_hash($pass1, PASSWORD_DEFAULT);

	$confirmationToken = bin2hex(random_bytes(40));
	$result = $db->AddUser($login, $passwordHash, $email, $confirmationToken);

	if ($result == UserDataReturnValues::Sucsess) {
		$msg = "<h1>Confirm registration on {$_SERVER['SERVER_NAME']}</h1>";
		$msg .= "Click on  <a href=\"http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}?login=" . $login . "&confirmation_token=" . $confirmationToken . "\">link</a> to confirm email";
		$mail = new AwsSES();
		$mail->SendEmail($email, $msg);
	}
	ExitPage($result->value);
}
