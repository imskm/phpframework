<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $title ?> | PHPMVC</title>

<link rel="stylesheet" href="/assets/css/styles.css">

<?= $this->fetchSection('styles') ?>

</head>
<body>
	<div class="container">

		<?= $this->content() ?>

	</div>

	<?= $this->fetchSection('scripts') ?>
</body>
</html>