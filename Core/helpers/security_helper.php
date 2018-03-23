<?php

/**
 * -------------------------------------------------------
 * Security Helpers fucntions
 * -------------------------------------------------------
 *
 * Handles all security functions
 */

if (! function_exists( "e" ))
{

	function e($data) {
		return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
	}

}

if (! function_exists("csrf_field"))
{

	function csrf_field()
	{
		$token = \Core\Token\Token::generate();
		\Core\Token\Token::set("csrf_token", $token);
		return '<input type="hidden" name="csrf_token" value="' . $token . '">';
	}
}