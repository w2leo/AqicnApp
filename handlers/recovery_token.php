<?php

if ($_SESSION['recovery_token'] == $_GET['recovery_token']) {

	include "handlers/set_new_password.php";
	include "views/set_new_password.php";
	exit;
} else {
	$_SESSION['message'][] = 'Некорректная ссылка восстановления';
	header('Location: /?recovery');
	exit;
}
