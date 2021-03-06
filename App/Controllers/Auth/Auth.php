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
		// $user = User::find(2);
		// $user = User::where("email", "muktar@gmail.com");
		$user = User::where('email', 'sadaf@gmail.com')->andWhere('password', '12345678');
		// $user = User::where('email', 'ssadaf@gmail.com')->orWhere('password', '12345678');
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

	protected function register()
	{
		return $this->view->render("Auth/register.php");
	}

	protected function store()
	{
		remember_post();
		//
		// do validation here
		//
		forget_post();
		redirect('auth/register');
	}

	public function logout()
	{

	}

	protected function before()
	{
		// New way to handle Middleware
		return (new \App\Middlewares\GuestMiddleware)();
		// return(Middleware::add("csrf", function() {
		// 	return new \App\Middlewares\AuthMiddleware;
		// })->process());
	}
}