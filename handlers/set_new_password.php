<?php

require_once('db/Validation.php');
require_once('db/dynamoDB.php');
require_once('db/AwsSES.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$email = $_SESSION['email'];
	$login = isset($_POST['login']) ? $_POST['login'] : "";
	// $token = $_GET['recovery_token'];

	$pass1 = $_POST['pass1'] ?? '';
	$pass2 = $_POST['pass2'] ?? '';

	$pass1 = Validation::CheckInput($_POST['pass1'] ?? '') ? $_POST['pass1'] ?? '' : '';
	$pass2 = Validation::CheckInput($_POST['pass2'] ?? '') ? $_POST['pass2'] ?? '' : '';

	$_SESSION['message'] = [];

	if (!$pass1 || !$pass2) {
		$_SESSION['message'][] = 'Input password and confirm it';
		header('Location: /');
		exit;
	}
	if ($pass1 != $pass2) {
		$_SESSION['message'][] = 'Passwords doesn\'t match';
		header('Location: /');
		exit;
	}

	$db = new DynamoDb();
	$db->UpdatePassword($login, $pass1);

	$_SESSION['message'][] = 'Пароль изменён';

	unset($_SESSION['recovery_token']);
	header('Location: /');
	exit;


}
