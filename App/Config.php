<?php

namespace App;

/**
 * Configuration for storing
 * settings of the current environment
 */
class Config
{
	/**
	 * Database host
	 * @var string
	 */
	const DB_HOST = "localhost";

	/**
	 * Database name
	 * @var string
	 */
	const DB_NAME = "phpmvc";

	/**
	 * Database username
	 * @var string
	 */
	const DB_USER = "root";

	/**
	 * Database password
	 * @var string
	 */
	const DB_PASSWORD = "bismillah";

	/**
	* Show or Hide error messages on screen
	* @var boolean
	*/
	const SHOW_ERRORS = true;
}
