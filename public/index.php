<?php
/**
 * Front Controller
 *  The Central Entry point for the site
 */

// Starting session for handling sessions
session_start();

/**
 * Directory separator
 * Diffrent platform has diffrent seprator
 * e.g. Linux use '/' and Windows '\'
 *
 * NO TRAILING SPACE IN ANY PATH CONSTANTS
 */
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", dirname(__DIR__));

const APP_PATH = ROOT . DS . "App";
const CORE_PATH = ROOT . DS . "Core";
const CUSTOM_ROUTE_FILE = "Routes.php";

/**
 * Autoloader
 */
spl_autoload_register(function ($class)
{
	$file = ROOT . DS . str_replace("\\", DS , $class) . ".php";
	if(is_readable($file))
		require $file;
});


/**
 * Registering Error and Exception handler
 */
error_reporting("E_ALL");
set_error_handler("Core\Error::errorHandler");
set_exception_handler("Core\Error::exceptionHandler");

/**
 * -------------------------------------------------------
 * Loading all the helper functions
 * -------------------------------------------------------
 *
 *
 */
require_once CORE_PATH . DS . "helpers" . DS . "autoloader.php";


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('posts', ['controller' => 'Posts', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ["namespace" => "Admin"]);
$router->add('admin/{controller}/{id:\d+}/{action}', ["namespace" => "Admin"]);

// Developping Framework route
$router->add('development', ['controller' => 'development', 'action' => 'index']);
$router->add('development', ['controller' => 'development', 'action' => 'validate']);

// Adding custom routes specified by user if any
if( ! empty(CUSTOM_ROUTE_FILE) ) {

	$_custom_routes = APP_PATH . DS . CUSTOM_ROUTE_FILE;
	if ( is_readable($_custom_routes) ) {
		require $_custom_routes;
	}
}

$router->dispatch($_SERVER["QUERY_STRING"]);
