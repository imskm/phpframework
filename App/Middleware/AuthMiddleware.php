<?php

namespace App\Middleware;

use App\Models\User;

/**
* Auth Middleware
*/
class AuthMiddleware
{
	
	public function __invoke($username, $password)
	{
		// $user = User::where("email", $username);
	}
}