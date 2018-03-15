<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Home page</title>
	</head>
	<body>

		<h1>Welcome</h1>
		<p>This is my PHP MVC Framework.</p>

		<p>Hello <strong><?php echo htmlspecialchars($name); ?></strong></p>
		<ul>
			<?php foreach ($colours as $colour): ?>
				<li><?=$colour?></li>
			<?php endforeach; ?>
		</ul>

	</body>
</html>
