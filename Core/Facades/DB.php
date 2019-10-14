<?php

namespace Core\Facades;

use \PDO;
use \App\Config;
use \Core\Database\Database;
use \Core\Database\Connector;

/**
 * DB Facad class
 */
class DB
{
	/**
	 * @var $pdo  PDO object (cached)
	 */
	private static $pdo;

	public static function from($table)
	{
		$pdo 				= self::getConnection();
		$query_builder 		= self::getQuery();
		$db 				= new Databse($pdo, $query_builder);

		return $db->from($table);
	}

	private static function getConnection()
	{

		$driver = Config::DB_DRIVER;

		switch ($driver) {
			case 'mysql':
				$pdo = new Connector();
				break;
			
			default:
				break;
		}
	}
}