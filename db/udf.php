<?php

function ExitPage($message)
{
	$_SESSION['message'][] = $message;
	header('Location: /');
	exit;
}



?>
