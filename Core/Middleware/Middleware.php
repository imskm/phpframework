<?php

namespace Core\Middleware;

use \Core\Token\Token;
use \App\Config as Config;

/**
* Middleware class
*/
class Middleware
{

	protected $middleware;
	protected $middlewares = array(
		"auth" => "auth",
		"csrf" => "verifyCSRFToken",
	);

	public function guard($guard)
	{
		if (! isset($this->middlewares[$guard])) {
			throw new \Exception("Middleware {$guard} was not fond.");
		}

		$this->middleware = $this->middlewares[$guard];

		return $this;
	}

	public function process()
	{
		$middleware = $this->middleware;
		$this->$middleware();
	}
	
	public function auth()
	{

	}

	public function verifyCSRFToken()
	{
		if (Token::check(Config::get("csrf_name"), $_POST["csrf_token"])) {
			throw new \Exception("CSRF Token miss match");
		}

		return true;
	}
}