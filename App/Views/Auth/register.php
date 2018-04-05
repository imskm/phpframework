<?php $this->use("templates/base.php", ["title" => "Register"]) ?>

<h2>Create Your MVC Account</h2>
<hr>

<strong>
<?php
	// ob_start();
	// ob_start();
	// ob_start();
	// var_dump(ob_get_status());
	// echo "Flushing";
	// ob_end_flush();
	// var_dump(ob_get_status());
	// var_dump(ob_list_handlers());
	// echo ob_get_level();
	// ob_start();

	// echo ob_get_level();
	// ob_end_clean()
?>
		
	</strong>
<form action="<?= path_for("auth/store") ?>" method="post">
	<div class="form-control">
		<label for="name">Full Name : </label>
		<input type="text" name="name" id="name">
	</div>

	<?php

	// throw new \Exception("Register exception.");

// xxxx();
// foreach ($assetssdfs as $asset) {
// 	echo $asset;
// }
?>


	<!-- <div class="form-control">
		<label for="address">Address : </label>
		<textarea name="address" id="address" cols="30" rows="10"></textarea>
	</div> -->
	<div class="form-control">
		<label for="email">Email : </label>
		<input type="text" name="email" id="email">
	</div>
	<div class="form-control">
		<label for="password">Password : </label>
		<input type="password" name="password" id="password">
	</div>
	<div class="form-control">
		<label for="confirm_password">Confirm Password : </label>
		<input type="password" name="confirm_password" id="confirm_password">
	</div>
	<div class="form-control">
		<input type="submit" value="Create account">
	</div>
</form>


<?php $this->section('scripts'); ?>

	<script>
		var $fun = function () {
			alert('hello');
		}
	</script>

<?php $this->endSection() ?>
