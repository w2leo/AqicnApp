<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Login page</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>

	<form class="box" method="POST">
		<h1>Восстановление пароля: шаг 2 из 2</h1>
		<input type="hidden" name="login" value=<?php echo $_GET['login']?>>
		<input type="password" name="pass1" id="pass1" placeholder="Password">
		<input type="password" name="pass2" id="pass2" placeholder="Confirm password">
		<input type="submit" name="submitRegister" value="Reset">
		<input type="submit" name="" value="Back" formaction="/">
		</div>
	</form>

</body>

</html>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Ubuntu+Condensed&display=swap" rel="stylesheet">
<style>
*{
  font-family: 'Ubuntu Condensed', sans-serif;
}</style>
