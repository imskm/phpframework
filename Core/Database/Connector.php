<?php

namespace Core\Database;

use \App\Config;

/**
* Connector class
* Let's app to connect to DB with appropriate DSN
*/
class Connector
{
	/**
	 * @var resource  $conn db Connection
	 */
	protected static $db = null;

	protected function __construct()
	{

	}

	public static function getConnection()
	{
		if (self::$db === null) {
			try {
				self::$db = new \PDO("mysql:host=". Config::DB_HOST . ";port=" . Config::DB_PORT .";dbname=". Config::DB_NAME .";charset=utf8", Config::DB_USER, Config::DB_PASSWORD);

				// Throw an Exception when Error occurs
				self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			} catch (\Exception $e) {
				throw new \Exception("ERROR : " . $e->getMessage());
			}
		}

		return self::$db;
	}
}