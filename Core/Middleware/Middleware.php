<?php

namespace Core\Middleware;

use \Core\Token\Token;
use \App\Config as Config;

/**
* Middleware class
*/
class Middleware
{

	protected static $middleware;

	protected static $next;

	protected $middlewares = array(
		"auth" => "auth",
		"csrf" => "verifyCSRFToken",
	);

	public static function add($middleware, $next = null)
	{
		self::$middleware = $middleware;
		self::$next = $next;

		return new self;
	}


	public function process()
	{
		if (! $this->check()) {
			throw new \Exception("Invalid Middlewares.");
		}

		$this->{$this->middlewares[self::$middleware]}();

		return (self::$next)()();
	}
	
	protected function check()
	{
		if (! self::$next) {
			self::$next = function() {
				return true;
			};
		}

		return isset($this->middlewares[self::$middleware]);
	}


	public function verifyCSRFToken()
	{
		// echo Token::get(Config::get("csrf_name")) . '<br>';
		// echo $_POST["csrf_token"] . '<br>';

		if(isset($_POST["csrf_token"])) {
			if (! Token::check($_POST["csrf_token"], Config::get("csrf_name"))) {
				throw new \Exception("CSRF Token miss match");
			}
		}

		return true;
	}
}