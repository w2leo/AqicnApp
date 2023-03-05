<?php
require_once('handlers/GetPostEnum.php');

class RequestHandler
{
	public function HandleGET($keys)
	{
		if (count($keys) == 0) {
			include "views/login.html";
		}

		foreach ($keys as $key) {
			switch ($key) {
				case GetKeys::CONFIRMATION_TOKEN->value:
					include "handlers/confirmation_token.php";
					break;
				case GetKeys::RECOVERY_TOKEN->value:
					include "handlers/recovery_token.php"; // get and post
					break;
				case GetKeys::MAIN->value:
					include "handlers/main.php";
					//return;
					break;
				case GetKeys::LOGOUT->value:
					include "handlers/logout.php";
					break;
				case GetKeys::RECOVERY->value:
					include "handlers/recovery.php"; // into post
					include "views/recovery.html";
					break;
				case GetKeys::SIGNUP->value:
					include "handlers/signup.php"; // into post
					include "views/signup.html";
					break;
				default:
					if (isset($_SESSION['username'])) {
						include "views/main.html";
					} else {
						include "views/login.html";
					}
					break;
			}
		}
	}

	public function HandlePOST($keys)
	{
		foreach ($keys as $key) {
			switch ($key) {
				case PostKeys::LOGIN->value:
					include "handlers/login.php";
					break;
				case PostKeys::RECOVERY->value:
					include "handlers/recovery.php"; // into post
					break;
				case PostKeys::SET_PASSWORD->value:
					include "handlers/set_new_password.php";
					break;
				case PostKeys::SIGNUP->value:
					include "handlers/signup.php"; // into post
					break;
				case PostKeys::ADD_CITY->value:
					include "handlers/main.php";
					break;
				case PostKeys::REMOVE_CITY->value:
					include "handlers/main.php";
					break;
				default:
					include "views/login.html";
			}
		}
	}

}
?>
