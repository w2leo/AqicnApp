<?php
//error_reporting(E_ERROR | E_PARSE);

require_once('db/Validation.php');
require_once('handlers/RequestHandler.php');

//enable input bufferization
ob_start();
//стартуем сессию
session_start();

// init session from config.ini file
$_SESSION['config'] = parse_ini_file("config.ini", true)[$_SERVER['SERVER_NAME']];
$requestHandler = new RequestHandler();

//Check and validate GET and POST requests
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET)) {
	Validation::ValidateArray($_GET);
	$requestHandler->HandleGET(array_keys($_GET));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST)) {
	Validation::ValidateArray($_POST);
	$requestHandler->HandlePOST(array_keys($_POST));
}


//Log to console;
if (!empty($_SESSION['message'])) {
	foreach ($_SESSION['message'] as $value) {
		echo('$_Session[message] = $value' . PHP_EOL);
	}
	unset($_SESSION['message']);
}

if (!isset($_GET) && !isset($_POST))
	$requestHandler->DefaultPage();


?>
