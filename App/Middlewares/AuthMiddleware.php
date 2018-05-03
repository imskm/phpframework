<?php
namespace App\Middlewares;

use App\Support\Authentication\Auth;

/**
* Auth Middleware
*/
class AuthMiddleware
{
	protected $redirect_to = 'home/index';
    public function __invoke()
    {
        if (! Auth::check()) {
        	redirect($this->redirect_to);
        }

        return true;
    }   
}
