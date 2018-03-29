<?php

/**
 * -------------------------------------------------------
 * Custom routes for the application
 * -------------------------------------------------------
 *
 * This page has user specif routes
 */

$router->add('admin', ['controller' => 'Home', 'action' => 'index', 'namespace' => 'Admin']);
$router->add('admin/{controller}/{action}', ["namespace" => "Admin"]);
$router->add('admin/{controller}/{id:\d+}/{action}', ["namespace" => "Admin"]);

$router->add('auth/login', ["namespace" => "Auth", "controller" => "Auth", "action" => "login"]);
$router->add('auth/attempt-login', ["namespace" => "Auth", "controller" => "Auth", "action" => "attemptLogin"]);
$router->add('auth/signup', ["namespace" => "Auth", "controller" => "Auth", "action" => "signup"]);
