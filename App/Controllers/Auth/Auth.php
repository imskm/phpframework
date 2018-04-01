<?php

namespace App\Controllers\Auth;

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
		// Testing ALL DATA TYPE INSERT IN DB

		// echo User::all()->count();
		// $user = User::all();
		$user = User::find(12);
		// $user = User::where("email", "muktar@gmail.com");
		print_r($user->first());
		// print_r($user);
		// echo '<br>Count : ' . $user->count();

		// $user = new User;
		// $user->name = "Test User";
		// $user->email = "test@gmail.com";
		// $user->password = password_hash("test", PASSWORD_DEFAULT);
		// var_dump($user->save());
		// var_dump($user->lastID());
		
		// $user->password = password_hash("waqar", PASSWORD_DEFAULT);
		// $user->name = "Waqar Azam";
		// $user->save();
		// if ($user->count() && $user->delete()) {
		// 	echo "DELETED 12";
		// } else {
		// 	echo "FAILD";
		// }

		return $this->view->render("Auth/login.php");
	}

	protected function attemptLogin()
	{

		echo "Logged in";

	}

	public function register()
	{
		return $this->view->render("Auth/register.php");
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