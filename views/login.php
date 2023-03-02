<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Login page Bootstrap</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="generator" content="Hugo 0.108.0">

	<link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sign-in/">
	<link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="../views/sign-in.css" rel="stylesheet">

	<style>
		.bd-placeholder-img {
			font-size: 1.125rem;
			text-anchor: middle;
			-webkit-user-select: none;
			-moz-user-select: none;
			user-select: none;
		}

		@media (min-width: 768px) {
			.bd-placeholder-img-lg {
				font-size: 3.5rem;
			}
		}

		.b-example-divider {
			height: 3rem;
			background-color: rgba(0, 0, 0, .1);
			border: solid rgba(0, 0, 0, .15);
			border-width: 1px 0;
			box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
		}

		.b-example-vr {
			flex-shrink: 0;
			width: 1.5rem;
			height: 100vh;
		}

		.bi {
			vertical-align: -.125em;
			fill: currentColor;
		}

		.nav-scroller {
			position: relative;
			z-index: 2;
			height: 2.75rem;
			overflow-y: hidden;
		}

		.nav-scroller .nav {
			display: flex;
			flex-wrap: nowrap;
			padding-bottom: 1rem;
			margin-top: -1px;
			overflow-x: auto;
			text-align: center;
			white-space: nowrap;
			-webkit-overflow-scrolling: touch;
		}

	</style>
</head>

<body class="text-center">
	<main class="form-signin w-100 m-auto">

		<form action="?login" method="post">
			<img class="mb-4" src="../assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">

			<h1 class="h3 mb-3 fw-normal">Login</h1>
			<div class="form-floating">
				<input type="text" name="login" class="form-control" id="floatingInput" placeholder="UserName">
				<label for="floatingInput">Login</label>
			</div>

			<div class="form-floating">
				<input type="password" name="password" class="form-control" id="floatingPassword"
					placeholder="Password">
				<label for="floatingPassword">Password</label>

			</div>

			<div class="form-floating">
				<a href="?recovery">Forget password? </a>
			</div>
			<div class="form-floating">

				<a href="?signup"> SigUp</a>
			</div>

			<button class="w-100 btn btn-lg btn-primary" type="submit">SignIn</button>
			<p class="mt-5 mb-3 text-muted">&copy; w2leo</p>

		</form>
	</main>


</body>

</html>
