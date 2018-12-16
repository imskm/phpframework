<?php

/**
 * -------------------------------------------------------
 * URI Helpers fucntions
 * -------------------------------------------------------
 *
 * Handles all url functions
 */

if( ! function_exists ( "path_for" ) )
{

	function path_for( $uri ) {

		$uri = trim($uri);
		$uri = ltrim($uri, "/");
		$uri = rtrim($uri, "/");
		$uri = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https://" : "http://") . $_SERVER["SERVER_NAME"] . "/" . $uri;

		return $uri;
	}

}

if( ! function_exists( "redirect" ) )
{
	function redirect($url) {

		header("Location: " . path_for($url));
		exit;
	}
}
