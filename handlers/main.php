<?php

require_once('db/AwsUsersData.php');
require_once('db/udf.php');


if (isset($_GET["main"]) && $_GET["main"] == 'fill') {
	$cities = $_SESSION['userData'][UserDataFields::Cities->name]['SS'];
	$deleteFiled = '<a href="/" class="delete-city">delete</a>';

	$items = array();
	foreach ($cities as $item) {
		$items[] = array(
			'city' => $item,
			'airData' => random_int(0, 100),
			'deleteField' => $deleteFiled
		);
	}

	echo 'JSON_TABLE' . json_encode($items) . 'JSON_TABLE';
}

if (isset($_POST["city"])) {

	$city = $_POST['city'];
	$db = new AwsUsersData();
	$db->GetData($_SESSION["username"]);
	$result = $_GET["main"] == 'add' ? $db->AddCity($city) : $db->RemoveCity($city);
	$_SESSION['userData'] = $db->GetData($_SESSION["username"]);
	ExitPage($result->value);
}


?>
