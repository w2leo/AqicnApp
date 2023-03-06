<?php

require_once('db/AwsUsersData.php');
require_once('db/udf.php');
require_once('aqicnApi/aqicn_api.php');

if (isset($_GET["main"]) && $_GET["main"] == 'fill') {
	// $db = new AwsUsersData();
	$cities = $_SESSION['userData'][UserDataFields::Cities->name]['SS'];

	$aqi = new AqiCn();

	$items = array();
	foreach ($cities as $item) {

		$airData = $aqi->GetCityData($item)['aqi'];

		$items[] = array(
			'city' => $item,
			'airData' => $airData,
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
	if ($actionType == 'add_city')
	{
		$result = $db->AddCity($city);
		ExitPage($result->value);
	}
	else {
		$db->RemoveCity($city);
	}
}




?>
