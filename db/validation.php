<?php

class Validation {

	static function validate($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	static function checkInput($input): bool
	{
		$data = Validation::validate($input);
		if ($input == '' || $data != $input)
			return false;
		return true;
	}

}

?>
