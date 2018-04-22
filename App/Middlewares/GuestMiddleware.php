<?php
namespace App\Middlewares;

use App\Support\Authentication\Auth;

/**
* Guest Middleware
*/
class GuestMiddleware
{
    public function __invoke()
    {
        if (Auth::check()) {
        	redirect('user/home/index');
        }

        return true;
    }   
}
