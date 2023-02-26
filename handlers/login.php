<?php
require_once('db/validation.php');
require_once('db/dynamoDB.php');

$email = '';
$password1 = '';
$password2 = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$login = Validation::CheckInput($_POST['login'] ?? '') ? $_POST['login'] ?? '' : '';
	$password = Validation::CheckInput($_POST['password'] ?? '') ? $_POST['password'] ?? '' : '';

	$_SESSION['message'] = [];
	if (!$login) {
		$_SESSION['message'][] = 'Set valid login';
		header('Location: /');
		exit;
	}
	if (!$password) {
		$_SESSION['message'][] = 'Set valid password';
		header('Location: /');
		exit;
	}

	$db = new DynamoDb();

	if (!$db->GetVerifiedLogin($login)) {
		$_SESSION['message'][] = 'Verify email first';
		header('Location: /');
		exit;
	}

	if ($userData = $db->Login($login, $password)) {
		$password = '';
		$_SESSION['username'] = $login;
		$_SESSION['userData'] = $userData['Item'];
		header('Location: /');
		exit;
	} else {
		$_SESSION['message'][] = 'Incorrect login / password';
		header('Location: /');
		exit;
	}

}
