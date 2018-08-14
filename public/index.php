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
const VIEW_PATH = APP_PATH . DS . "Views";
const CUSTOM_ROUTE_FILE = "Routes.php";
const BOOTSTRAP_FILE = "Bootstrap.php";

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
 * and a shutdown handler : it is called when exit() is called
 * or script finishes execution
 */
error_reporting(E_ALL);
set_error_handler("Core\Error::errorHandler", error_reporting());
set_exception_handler("Core\Error::exceptionHandler");
register_shutdown_function("Core\Error::shutdownHandler");

/**
 * -------------------------------------------------------
 * Loading all the helper functions
 * -------------------------------------------------------
 *
 *
 */
require_once CORE_PATH . DS . "helpers" . DS . "autoloader.php";

/**
 * Additionaly Liabrary (vendor) bootstap file
 */
if( ! empty(BOOTSTRAP_FILE) ) {

	$_bootstrap_file_path = APP_PATH . DS . BOOTSTRAP_FILE;
	if ( is_readable($_bootstrap_file_path) ) {
		require $_bootstrap_file_path;
	}
}


/**
 * Routing
 */
$router = new Core\Router();

// Adding custom routes specified by user if any
if( ! empty(CUSTOM_ROUTE_FILE) ) {

	$_custom_routes = APP_PATH . DS . CUSTOM_ROUTE_FILE;
	if ( is_readable($_custom_routes) ) {
		require $_custom_routes;
	}
}

// Add the default routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('posts', ['controller' => 'Posts', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');


// Developping Framework route
$router->add('development', ['controller' => 'development', 'action' => 'index']);
$router->add('development', ['controller' => 'development', 'action' => 'validate']);

$router->dispatch($_SERVER["QUERY_STRING"]);
