<?php
namespace App\Middlewares;

use App\Support\Authentication\Auth;

/**
* Guest Middleware
*/
class GuestMiddleware
{
	protected $redirect_to = 'user/home/index';

    public function __invoke()
    {
        if (Auth::check()) {
        	redirect($this->redirect_to);
        }

        return true;
    }   
}
