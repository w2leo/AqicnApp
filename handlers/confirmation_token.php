<?php
require_once('db/dynamoDB.php');

$login = $_GET['login'];
$confirmation_token = $_GET['confirmation_token'];
$db = new DynamoDb();

$result = $db->CheckConfirmationToken($login, $confirmation_token);

if ($result) {
	$_SESSION['message'][] = 'Email подтверждён!';
	header('Location: /');
	exit;
}
$_SESSION['message'][] = 'Некорректная ссылка';
header('Location: /');
