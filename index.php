<?php
//error_reporting(E_ERROR | E_PARSE);

//enable input bufferization
ob_start();

//стартуем сессию
session_start();

// init session from config.ini file
$_SESSION['vendor_path'] = parse_ini_file("config.ini", true)[$_SERVER['SERVER_NAME']];

//check and validate GET and POST requests
if (isset($_GET)) {
	$flagError = false;
	foreach ($_GET as $key => $value) {
		if (!Validation::checkInput($value)) {
			unset($_GET[$key]);
			$flagError = true;

		}
	}
	if ($flagError) {
		header('Location: /');
		exit;
	}
}

if (isset($_POST)) {
	foreach ($_POST as $key => $value) {
		$_GET[$key] = Validation::validate($value);
	}
}


require_once('db/validation.php');
require_once('db/dynamoDB.php');
require_once('db/testAwsRole.php');

//debug info
echo "<pre>GET:", print_r($_GET), "</pre>";
echo "<pre>POST:", print_r($_POST), "</pre>";
echo "<pre>SESSION:", print_r($_SESSION), "</pre>";

//check message array and print it
if (!empty($_SESSION['message'])) {
	foreach ($_SESSION['message'] as $value) {
		echo <<<MESSAGE
<div>{$value}</div>
MESSAGE;
	}
	unset($_SESSION['message']);

}

//confirm email - check link click
if (!empty($confirmation_token = isset($_GET['confirmation_token']))) {
	include "handlers/confirmation_token.php";
}

//password recovery link
if (isset($_GET['recovery_token'])) {
	include "handlers/recovery_token.php";
}

//handle end session
if (isset($_GET['logout'])) {
	include "handlers/logout.php";
}

//Show private part if $_SESSION['username'] exists
if (isset($_SESSION['username'])) {
	include "views/index.php";


} elseif (!isset($_GET['recovery']) && !isset($_GET['signup'])) {

	//handle login
	include "handlers/login.php";
	include "views/login.php";
} elseif (isset($_GET['recovery'])) {

	//handle password recovery
	include "handlers/recovery.php";
	include "views/recovery.php";
} elseif (isset($_GET['signup'])) {

	//signup form
	include "handlers/signup.php";
	include "views/signup.php";
}

?>

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Ubuntu+Condensed&display=swap" rel="stylesheet">
<style>
	* {
		font-family: 'Ubuntu Condensed', sans-serif;
	}

</style>
