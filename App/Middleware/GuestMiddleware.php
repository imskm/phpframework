<?php

namespace App\Middleware;

use App\Models\User;

/**
* Auth Middleware
*/
class GuestMiddleware extends \App\Middleware\Middleware
{
	
	public function __invoke($username, $password)
	{
		$user = User::where("email", $username);
	}
}