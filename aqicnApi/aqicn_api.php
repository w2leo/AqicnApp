<?php

// Define the allowed methods for this API endpoint
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json; charset=UTF-8");

// Get the HTTP method, path, and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
$input = json_decode(file_get_contents('php://input'), true);

// Route the request to the appropriate function based on the HTTP method and path
switch ($method) {
	case 'GET':
		handleGet($request);
		break;
	case 'POST':
		handlePost($input);
		break;
	case 'PUT':
		handlePut($request, $input);
		break;
	case 'DELETE':
		handleDelete($request);
		break;
}

// Function to handle a GET request
function handleGet($request)
{
	// Return a JSON response with the requested data
	echo json_encode(
		array(
			"message" => "Hello, World!"
		)
	);
}

// Function to handle a POST request
function handlePost($input)
{
	// Return a JSON response with the posted data
	echo json_encode(
		array(
			"message" => "Received POST request",
			"data" => $input
		)
	);
}

// Function to handle a PUT request
function handlePut($request, $input)
{
	// Return a JSON response with the updated data
	echo json_encode(
		array(
			"message" => "Received PUT request",
			"data" => $input
		)
	);
}

// Function to handle a DELETE request
function handleDelete($request)
{
	// Return a JSON response to confirm deletion
	echo json_encode(
		array(
			"message" => "Received DELETE request"
		)
	);
}

?>
