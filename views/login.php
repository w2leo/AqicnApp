<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Login page</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<form class="box" action="?login" method="post">
		<h1>Login</h1>
		<input type="text" name="login" placeholder="Username">
		<input type="password" name="password" placeholder="Password">
		<div class="row">
			<a href="?recovery">Забыли пароль?</a> |
			<a href="?signup">Регистрация</a>
		</div>
		<input type="submit" name="submitLogin" value="Login">
	</form>

</body>

</html>
