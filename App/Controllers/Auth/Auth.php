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
		// $user = User::find(1);
		// $user = User::where("email", "muktar@gmail.com");
		// var_dump($user->first());
		// echo '<br>Count : ' . $user->count();
		$user = new User;
		$user->name = "Ibtesham";
		$user->email = "ibtesham@gmail.com";
		$user->password = password_hash("12345678", PASSWORD_DEFAULT);
		var_dump($user->save());
		var_dump($user->lastID());

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