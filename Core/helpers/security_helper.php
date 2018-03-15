<?php

/**
 * -------------------------------------------------------
 * Security Helpers fucntions
 * -------------------------------------------------------
 *
 * Handles all security functions
 */

if( ! function_exists ( "e" ) )
{

	function e( $data ) {
		return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
	}

}