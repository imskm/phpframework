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
	public static function table($table)
	{
		$pdo 				= Connector::getConnection();
		$query_builder 		= Connector::getQueryBuilder();
		$db 				= new Database($pdo, $query_builder);

		return $db->table($table);
	}

	public static function raw($sql, array $params = [])
	{
		$pdo 				= Connector::getConnection();
		$query_builder 		= Connector::getQueryBuilder();
		$db 				= new Database($pdo, $query_builder);

		return $db->raw($sql, $params);
	}
}