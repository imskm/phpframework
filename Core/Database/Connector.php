<?php

namespace Core\Database;

use App\Config;
use Core\Database\Connectors\Mysql;
use Core\Database\QueryBuilders\MysqlQueryBuilder;

/**
 * class Connector
 */
class Connector
{
	private static $pdo   		  = null;
	private static $query_builder = null;

	private function __construct() {}

	public static function getConnection()
	{
		if (self::$pdo != null) {
			return self::$pdo;
		}

		switch (Config::DB_DRIVER) {
		case 'mysql':
			$mysql = new Mysql(self::buildMysqlDsn(),
				Config::DB_USER, Config::DB_PASSWORD);
			self::$pdo = $mysql->getPdo();
			self::$query_builder = MysqlQueryBuilder::class;

			return self::$pdo;
			
			// 
		}

		throw new \Exception("Unknown/unsupported database driver \"$mysql\"");
	}

	private static function buildMysqlDsn()
	{
		$host   = Config::DB_HOST ?? "127.0.0.1"; /* Default mysql host */
		$port   = Config::DB_PORT ?? 3306; /* Default mysql port */
		$dbname = Config::DB_NAME ?? null;

		if ($dbname == null) {
			throw new \Exception("Missing DB_NAME in database configuration");
		}

		return "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";
	}

	public static function getQueryBuilder()
	{
		if (self::$pdo == null) {
			throw new \Exception("No connection is created. Create a connection first");
		}

		return new self::$query_builder;
	}
}