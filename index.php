<?php
// error_reporting(E_ERROR | E_PARSE);

//включаем буферизацию вывода
ob_start();

//стартуем сессию
session_start();

//подключаем базу
include "db/dynamoDB.php";

//отладочная информация
echo "<pre>GET:", print_r($_GET), "</pre>";
echo "<pre>POST:", print_r($_POST), "</pre>";
echo "<pre>SESSION:", print_r($_SESSION), "</pre>";

//проверяем массив flash-сообщений и выводим
if (!empty($_SESSION['message'])) {
	foreach ($_SESSION['message'] as $value) {
		echo <<<MESSAGE
<div>{$value}</div>
MESSAGE;
	}
	unset($_SESSION['message']);

}

//подтверждаем регистрацию: проверка клика по ссылке из емейла
if (!empty($confirmation_token = isset($_GET['confirmation_token']))) {
	include "handlers/confirmation_token.php";
}

//восстанавливаем пароль
if (isset($_GET['recovery_token'])) {
	include "handlers/recovery_token.php";
}

//обработка выхода из сессии
if (isset($_GET['logout'])) {
	include "handlers/logout.php";
}

//показ личного кабинета при существовании $_SESSION['username']
if (isset($_SESSION['username'])) {
	include "views/index.php";


} elseif (!isset($_GET['recovery']) && !isset($_GET['signup'])) {

	//обработка входа - logins
	include "handlers/login.php";
	include "views/login.php";
} elseif (isset($_GET['recovery'])) {

	//форма восстановления пароля
	include "handlers/recovery.php";
	include "views/recovery.php";
} elseif (isset($_GET['signup'])) {

	//форма регистрации на сайте
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
