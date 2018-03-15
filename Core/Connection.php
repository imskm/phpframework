<?php
namespace Core;

use PDO;
use App\Config;

/**
 * Connection class
 *  Contains static function for Database
 *  Connection function
 */
class Connection
{
	public static function getDB()
	{
		static $db = null;
		if($db === null)
		{
			$db = new PDO("mysql:host=". Config::DB_HOST .";dbname=". Config::DB_NAME .";charset=utf8", Config::DB_USER, Config::DB_PASSWORD);

			// Throw an Exception when Error occurs
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return $db;
	}
}
