<?php

require_once('db/AwsUsersData.php');


if (isset($_GET["main"]) && $_GET["main"]=='fill')
{
	$items = array(
		array('name' => 'Item 1', 'description' => 'Description 1', 'price' => '$10'),
		array('name' => 'Item 2', 'description' => 'Description 2', 'price' => '$20'),
		array('name' => 'Item 3', 'description' => 'Description 3', 'price' => '$30')
	  );

	//   add <a href="/" class="delete-city">delete</a>

	  // encode the data as a JSON string and return it
	  echo json_encode($items);
}


if (isset($_GET["main"]) && isset($_POST["city"])) {

	$city = $_POST['city'];
	$db = new AwsUsersData();
	$db->GetData($_SESSION["username"]);
	$_GET["main"] == 'add' ? $db->AddCity($city) : $db->RemoveCity($city);
	FillTable($db->GetData($_SESSION['username']));
}




function FillTable()
{
	$data = $_SESSION['userData']['Cities'];
	$table_html = '';
	foreach ($data as $item) {
		$table_html .= '<tr>';
		$table_html .= '<td>' . $item[0] . '</td>';
		$table_html .= '<td>' . $item[1] . '</td>';
		$table_html .= '<td>' . $item[2] . '</td>';
		$table_html .= '</tr>';
	}

	// end the table HTML code and output it
	$table_html .= '</table>';
	echo $table_html;
}
?>
