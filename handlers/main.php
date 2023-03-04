<?php

require_once('db/AwsUsersData.php');

if (isset($_GET["main"]) && isset($_POST["city"])) {

	$city = $_POST['city'];
	$db = new AwsUsersData();
	$db->GetData($_SESSION["username"]);
	$_GET["main"] == 'add' ? $db->AddCity($city) : $db->RemoveCity($city);

}

?>
