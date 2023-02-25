<?php

enum GetKeys: string
{
	case CONFIRMATION_TOKEN = 'confirmation_token';
	case RECOVERY_TOKEN = 'recovery_token';
	case MAIN = 'main';
	case LOGOUT = 'logout';
	case RECOVERY = 'recovery';
	case SIGNUP = 'signup';
}

enum PostKeys: string
{
	case LOGIN = 'login';
	case RECOVERY = 'recovery';
	case SET_PASSWORD = 'set_password';
	case SIGNUP = 'signup';
	case ADD_CITY = 'add_city';
	case REMOVE_CITY = 'remove_city';
}

?>
