<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $title ?> | PHPMVC</title>

<link rel="stylesheet" href="/assets/css/styles.css">
<link rel="stylesheet" href="/assets/css/mrflexible.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css">

<?= $this->fetchSection('styles') ?>

</head>
<body>
	<div class="container">

		<?= $this->content() ?>

	</div>

	<?= $this->fetchSection('scripts') ?>
</body>
</html>