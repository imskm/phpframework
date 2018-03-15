<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Home page</title>
	</head>
	<body>

		<h1>Welcome to POSTS</h1>

		<?php foreach ($posts as $post): ?>
			<h2><?php echo htmlspecialchars($post["title"]); ?></h2>
			<p><?php echo htmlspecialchars($post["content"]); ?></p>
		<?php endforeach; ?>

	</body>
</html>
