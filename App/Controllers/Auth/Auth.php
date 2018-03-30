<?php

namespace App\Controllers\Auth;

use \Core\View;
use App\Models\User;
use \Core\Database\Query;
use \Core\Middleware\Middleware;

/**
* Auth Controller
*/
class Auth extends \Core\Controller
{
	protected function login()
	{
		// $user = User::all();
		$user = User::find(4);
		// $user = User::where("email", "muktar@gmail.com");
		// var_dump($user);
		// echo '<br>Count : ' . $user->count();
		// $user = new User;
		// $user->name = "Test";
		// $user->email = "test@gmail.com";
		// $user->password = password_hash("12345678", PASSWORD_DEFAULT);
		// var_dump($user->save());
		// var_dump($user->lastID());
		// $user->password = password_hash("waqar", PASSWORD_DEFAULT);
		// $user->name = "Waqar Azam";
		// $user->save();
		if ($user->count() && $user->delete()) {
			echo "DELETED 5";
		} else {
			echo "FAILD";
		}

		return View::render("Auth/login.php");
	}

	protected function attemptLogin()
	{

		echo "Logged in";

	}

	public function signup()
	{
		return View::render("Auth/signin.php");
	}

	public function logout()
	{

	}

	protected function before()
	{
		return(Middleware::add("csrf", function() {
			return new \App\Middleware\AuthMiddleware;
		})->process());
	}
}