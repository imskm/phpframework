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

if( ! function_exists( "uri_referer" ) )
{
	function uri_referer() {

		// If REFERER does not exist then default to root
		if (!isset($_SERVER['HTTP_REFERER'])) {
			return path_for("/");
		}

		// If HTTP_REFERER is not part of our domain then default to root
		$server = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "";
		if (strpos($_SERVER['HTTP_REFERER'], $server) === false) {
			return path_for("/");
		}

		// Sanetize URL
		$server = filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL);
		if ($server === false) {
			return path_for("/");
		}

		return strip_tags($server);
	}
}
