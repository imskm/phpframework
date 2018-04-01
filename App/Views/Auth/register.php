<?php $this->use("templates/base.php", ["title" => "Register"]) ?>

<h2>Create Your MVC Account</h2>
<hr>
<form action="<?= path_for("auth/store") ?>">
	<div class="form-control">
		<label for="name">Full Name : </label>
		<input type="text" name="name" id="name">
	</div>
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
