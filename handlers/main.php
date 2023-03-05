<?php

require_once('db/AwsUsersData.php');
require_once('db/udf.php');


if (isset($_GET["main"]) && $_GET["main"] == 'fill') {
	// $db = new AwsUsersData();
	$cities = $_SESSION['userData'][UserDataFields::Cities->name]['SS'];

	$items = array();
	foreach ($cities as $item) {
		$items[] = array(
			'city' => $item,
			'airData' => random_int(0, 100),
		);
	}

	echo 'JSON_TABLE' . json_encode($items) . 'JSON_TABLE';
}


if (isset($_POST["add_city"]) || isset($_POST["remove_city"])) {
	$val = array_key_first($_POST);
	ChangeCity($val);
}

function ChangeCity($actionType)
{
	$city = $_POST[$actionType];
	$db = new AwsUsersData();
	$db->GetData($_SESSION["username"]);
	$result = $actionType == 'add_city' ? $db->AddCity($city) : $db->RemoveCity($city);
	// $_SESSION['userData'] = $db->GetData($_SESSION["username"]);
	ExitPage($result->value);
}




?>
