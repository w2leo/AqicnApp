<?php

require_once("dynamoDB.php");
define("EMAIL_PATTERN", '/^[0-9a-zA-Z]+[@][0-9a-zA-Z]+[.][a-zA-Z]+$/');

echo "wtf u r doing here?<br>";

if (isset($_POST['submitLogin'])) {
	$login = $_POST['login'];
	$password = $_POST['password'];

	if (!checkInput($login) || !checkInput($password)) {
		header("Location: /?error=Incorrect input");
		exit();
	}

	$db = new DynamoDb();
	$resLogin = $db->TryLogin($login, $password);
	echo "<br>Login ";

	if ($resLogin) {
		echo "sucsesssfull";
		$_SESSION['login'] = $login;
		header("Location: /main");
		exit();
	} else {
		echo "failed";
		header("Location: index.php?error=User Name or Password incorrect");
		exit();
	}
}

if (isset($_POST['submitRegister'])) {
	$db = new DynamoDb();
	$login = validate($_POST['login']);
	$password = validate($_POST['password']);
	$confirmPassword = validate($_POST['confirmPassword']);
	$email = validate($_POST['email']);
	if (
		$login != $_POST['login'] || $password != $_POST['password'] || $confirmPassword != $_POST['confirmPassword'] ||
		$password != $confirmPassword || !preg_match(EMAIL_PATTERN, $email)
	) {
		header("Location: index.php?error=Incorrect input");
		exit();
	} else {
		$res = $db->AddUser($login, $email, $password);
		var_dump($res);
	}

	header("Location: /index.php#submitForm");
	exit();
}



?>
