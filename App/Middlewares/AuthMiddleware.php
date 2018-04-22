<?php
namespace App\Middlewares;

use App\Support\Authentication\Auth;

/**
* Auth Middleware
*/
class AuthMiddleware
{
    public function __invoke()
    {
        if (! Auth::check()) {
        	redirect('home/index');
        }

        return true;
    }   
}
