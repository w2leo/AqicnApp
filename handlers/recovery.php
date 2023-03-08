<?php

require_once('db/Validation.php');
require_once('db/AwsSES.php');
require_once('db/AwsUsersData.php');
require_once('db/udf.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$login = Validation::CheckInput($_POST['recovery'] ?? '') ? $_POST['recovery'] : '';

	if ($login == '') {
		ExitPage('Incorrect login');
	}

	$db = new AwsUsersData();
	$result = $db->CheckUserExists($login);

	if ($result == UserDataReturnValues::UserExists) {
		$recovery_token = bin2hex(random_bytes(40));
		$result = $db->AddRecoveryToken($login, $recovery_token);

		if ($result != UserDataReturnValues::NotConfirmedEmail) {
			$email = $db->GetEmail($login);
			$msg = "<h3>Password recovery at {$_SERVER['SERVER_NAME']}</h3>";
			$msg .= "<p>If you try to recover your password, click on <a href=\"http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}?login=";
			$msg .= $login . "&recovery_token=" . $recovery_token . "\">link</a> to set new password </p>";
			$mail = new AwsSES();
			try {
				$mail->SendEmail($email, $msg);
			} catch (Error $e) {
				echo '';
			}
		}
	}

	ExitPage($result->value);
}

?>
