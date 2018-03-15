<?php

namespace Core;

use PDO;
use App\Config;

/**
 * Base Model class
 */
abstract class Model
{
	protected $fields;
	public $lastId;

	protected static $template = "SELECT * FROM %s";

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

	/**
	 * Stores data to database table with dynamically created fields (property)
	 *
	 * @param array $fields  The key value pair fields of table in which data need to be stored
	 *
	 * @return boolean
	 */
	public function save()
	{
		// Getting connection
		$db = self::getDB();
		// Getting properties of current instance
		$data = get_object_vars($this);

		if(@key_exists($data["primary"], $data)) {
			$sql = $this->_buildUpdate($data);
		} else {
			$sql = $this->_buildInsert($data);
		}

		$st = $db->prepare($sql);

		foreach ($this->fields as $key => $value) {
			$st->bindValue(":{$key}", $value, PDO::PARAM_STR);
		}

		// Binding primry key for updating existing record
		if(@isset($data[$data["primary"]])) {
			$key = $data["primary"];
			$st->bindValue(":{$key}", $data[$data["primary"]], PDO::PARAM_STR);
		}

		if(!$st->execute()) {
			$error = $st->errorInfo();
			throw new Exception($error[2] . " : Faild to save data.");
		}

		// Storing last insert id on INSERT query
		if(@!isset($data[$data["primary"]])) {
			$this->lastId = $db->lastInsertId();
		}

		return true;
	}

	/**
	 * Update existing data of table
	 *
	 * @param array $where  The key value pair of where condition
	 *   where Key is column and value is the value of the column
	 *   e.g. ['id' => 2] 	=> id = 2
	 *
	 * @return boolean
	 */
	public function delete($where)
	{
		// Getting connection
		$db = self::getDB();
		// Getting properties of current instance
		$data = get_object_vars($this);
		$data["_where"] = $where;

		$sql = $this->_buildDelete($data);
		$st = $db->prepare($sql);
		foreach ($this->fields as $key => $value) {
			$st->bindValue(":{$key}", $value, PDO::PARAM_STR);
		}

		$result = $st->execute();
		if(!$result)
		{
			throw new Exception("Faild to delete record.");
		}
		return true;
	}

	/**
	 * Static function to Find record by id.
	 *
	 * @param int $id   Id of the field to get the value by.
	 * @return object
	 */
	public static function find($id)
	{
		$template = "SELECT * FROM %s WHERE %s = :%s";
		$called_class = get_called_class();
		$model_obj = new $called_class;
		if(property_exists($model_obj, "table")) {
			$table = $model_obj->table;
		} else {
			$table = self::_getTableName();
		}

		if(property_exists($model_obj, "primary")) {
			$primary = $model_obj->primary;
		} else {
			$primary = "id";
		}

		$sql = sprintf($template, $table, $primary, $primary);

		$db = self::getDB();
		$st = $db->prepare($sql);
		$st->bindValue(":$primary", $id, PDO::PARAM_STR);

		$result = "";
		if($st->execute()) {
			$result = $st->fetchObject();
		} else {
			$error = $st->errorInfo();
			throw new Exception($error[2] . " : Faild to find data.");
		}


		if($result) {
			foreach ($result as $key => $value) {
				$model_obj->$key = $value;
			}
		}

		// return $result;
		return $model_obj;

	}

	public static function paginate($items)
	{
		$template = "LIMIT %s OFFSET :offset";
		$template = self::$template . " " . $template;
		$table = self::_getTableName();
		$offset = isset($_GET["page"]) && !empty($_GET["page"] && $_GET["page"] > 0) ? ((int) $_GET["page"] - 1) * $items : 0;

		$sql = sprintf($template, $table, $items);

		$db = self::getDB();
		$st = $db->prepare($sql);
		$st->bindValue(":offset", $offset, PDO::PARAM_INT);
		if(!$st->execute()) {
			$error = $st->errorInfo();
			throw new Exception($error[2]);
		}

		while($row = $st->fetchObject()) {
			$rows[] = $row;
		}

		if(!isset($rows)) {
			$rows = null;
		} elseif(count($rows) == 1) {
			$rows = $rows[0];
		}

		return $rows;
	}

	public static function orderBy($field, $order = "ASC")
	{
		$order = strtoupper($order);
		$orders = ["ASC", "DESC"];
		if(!in_array($order, $orders)) {
			// If invalid order type is given then set the order type to asc
			$order = $orders[0];
		}

		$template = "ORDER BY %s %s";
		self::$template = self::$template . " " .sprintf($template, $field, $order);
		return new self;
	}


	protected function _buildInsert($data)
	{
		// Getting the table name
		$table = self::_getTableName($data);
		$template = "INSERT INTO %s ( %s ) VALUES ( %s )";

		// Removing properties which is not columns of a table
		unset($data["table"]);
		unset($data["fields"]);
		unset($data["lastId"]);
		unset($data["primary"]);

		foreach ($data as $key => $value) {
			$fields[] 				= $key;
			$params[] 				= ":" . $key;
			$this->fields[$key] 	= $value;
		}
		$fields = implode(", ", $fields);
		$params = implode(", ", $params);

		return sprintf($template, $table, $fields, $params);
	}

	protected function _buildUpdate($data)
	{
		//UPDATE table set field='value', field='value', field='value' WHERE field=value;
		$table = self::_getTableName();
		$template = "UPDATE %s set %s WHERE %s = :%s";

		// Removing properties which is not columns of a table
		unset($data["table"]);
		unset($data["fields"]);
		unset($data["lastId"]);
		$primary = $data["primary"];
		unset($data[$data["primary"]]);
		unset($data["primary"]);

		foreach ($data as $key => $value) {
			$fields [] = $key . "=:" . $key;
			$this->fields[$key] = $value;
		}
		$fields = implode(", ", $fields);


		return sprintf($template, $table, $fields, $primary, $primary);
	}

	protected function _buildDelete($data)
	{
		$table = self::_getTableName($data);
		$where = $this->where($data["_where"]);
		$template = "DELETE FROM %s WHERE %s";

		return sprintf($template, $table, $where);
	}

	protected function where($where)
	{
		$condition = [];
		foreach ($where as $key => $value) {
			$condition[] 		= "{$key}=:{$key}";
			$this->fields[$key] = $value;
		}
		return implode(" AND ", $condition);
	}

	protected static function _getTableName($data)
	{
		if(key_exists("table", $data)) {
			$table = $data["table"];
		} else {
			$parts = explode("\\", get_called_class());
			$table = array_pop($parts);
			$table = strtolower($table) . "s";
		}
		return $table;
	}

	public function getId()
	{
		return $this->lastId;
	}

}
