<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<style>
		body {
			padding: 2em;
			width: 50%;
			margin: 0 auto;
		}
	</style>
</head>
<body>

	<form action="<?= path_for("register/store") ?>" method="post">
		<label for="full_name">Full name</label>
		<input type="text" name="full_name" id="full_name">
		<hr>
		<label for="username">Email</label>
		<input type="text" name="username" id="username"> <br>
		<label for="password">Password</label>
		<input type="text" name="password" id="password"> <br>
		<input type="submit" value="Login" name="login">
	</form>
	
</body>
</html>