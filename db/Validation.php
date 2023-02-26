<?php

class Validation {

	static function Validate($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	static function CheckInput($input): bool
	{
		$data = Validation::Validate($input);
		return $input != '' && $data == $input;
	}

	static function CompareArrayLengths(array $arrays): bool
	{
		foreach ($arrays as $index => $item) {
			if ($index == 0)
				continue;

			if (count($item) != count($arrays[$index-1]))
			{
				return false;
			}
		}
		return true;
	}

	static function GetAwsType($value): string
	{
		switch (substr(gettype($value),0,1)) {
			case 'i':
				return 'N';
			case 'b':
				return 'BOOL';
			case 'a':
				return 'SS';
			//implement Binary Later
			default:
				return 'S';
		}
	}

}

?>
