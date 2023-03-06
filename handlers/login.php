<?php
require_once('db/Validation.php');
require_once('db/AwsUsersData.php');
require_once('db/udf.php');


$email = '';
$password1 = '';
$password2 = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$login = Validation::CheckInput($_POST['login'] ?? '') ? $_POST['login']: '';
	$password = Validation::CheckInput($_POST['password'] ?? '') ? $_POST['password'] : '';

	$_SESSION['message'] = [];

	if (!$login) {
		ExitPage('Set valid login');
	}
	if (!$password) {
		ExitPage('Set valid password');
	}

	$db = new AwsUsersData();
	$result = $db->Login($login, $password);
	if ($result == UserDataReturnValues::Sucsess) {
		$_SESSION['username'] = $login;
		$_SESSION['userData'] = $db->GetData($login);
	}

	ExitPage($result->value);
}
