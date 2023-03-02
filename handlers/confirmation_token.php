<?php
require_once('db/AwsUsersData.php');

$login = $_GET['login'];
$confirmation_token = $_GET['confirmation_token'];
$db = new AwsUsersData();

$result = $db->ConfirmEmail($login, $confirmation_token);

if ($result == UserDataReturnValues::EmailConfirmed) {
	$_SESSION['message'][] = UserDataReturnValues::EmailConfirmed->value;

} else {
	$_SESSION['message'][] = $result->value;

}
header('Location: /');

header('Location: /');
exit;

?>
