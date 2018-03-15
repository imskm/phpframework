<?php

/**
 * -------------------------------------------------------
 * Custom routes for the application
 * -------------------------------------------------------
 *
 * This page has user specif routes
 */

$router->add('admin', ['controller' => 'Home', 'action' => 'index', 'namespace' => 'Admin']);
$router->add('admin/{controller}/', ["namespace" => "Admin", "action" => "index"]);
