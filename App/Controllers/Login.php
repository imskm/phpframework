<?php

namespace App\Controllers;


use \Core\View;

/**
* Login controller
*/
class Login extends \Core\Controller
{
	
	protected function index()
	{

		return View::render("Login/index.php");

	}

	protected function login()
	{

		echo "OK";

	}

	public function logout()
	{

	}

	protected function before()
	{
		echo "Passed through before ";
		return true;

	}
}