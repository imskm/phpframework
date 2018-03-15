<?php

namespace Core;

use PDO;
use App\Config;

/**
 * Database Class
 *  Handles query building and fetching data from database
 *  Most of the methods are static
 */
class Database
{
	protected static $fields = [];
	protected static $table;
	protected static $sql;
	protected static $paramValue = [];
	protected static function getDB()
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

	public static function from($table)
	{
		self::$table = $table;
		self::$sql = "SELECT %s FROM $table";
		return new self;
	}

	public static function select($fields = [])
	{
		$cols = "*";
		if(!empty($fields)) {
			$cols = implode(", ", $fields);
		}

		self::$fields = $fields;
		self::$sql = sprintf(self::$sql, $cols);
		return new self;
	}

	public function where($col, $operator, $value)
	{
		$template = "WHERE %s %s :%s";
		self::$paramValue[$col] = $value;

		self::$sql = self::$sql . " " .sprintf($template, $col, $operator, $col);

		return new self;
	}

	public function limit($limit)
	{
		$template = "LIMIT %s";
		self::$sql = self::$sql . " " .sprintf($template, $limit);
		return new self;
	}

	public function orderBy($field, $order = "ASC")
	{
		$order = strtoupper($order);
		$orders = ["ASC", "DESC"];
		if(!in_array($order, $orders)) {
			// If invalid order type is given then set the order type to asc
			$order = $orders[0];
		}

		$template = "ORDER BY %s %s";
		self::$sql = self::$sql . " " .sprintf($template, $field, $order);
		return new self;
	}

	public static function get()
	{
		$db = self::getDB();
		$st = $db->prepare(self::$sql);

		foreach (self::$paramValue as $field => $value) {
			$st->bindValue(":$field", $value);
		}

		if(!$st->execute()) {
			throw new \Exception("Query failed!");
		}
		while($row = $st->fetchObject()) {
			$rows[] = $row;
		}
		
		if(isset($rows)) {
			if(count($rows) == 1) {
				$rows = $rows[0];
			}
		} else {
			$rows = null;
		}

		return $rows;
	}
}
