<?php

namespace Core\Database;

use \PDO;
use Core\Database\Query;
use Core\Database\Connector as Conn;

/**
 * Base Model class
 */
abstract class Model extends Query
{
	protected $model;
	protected $primary_key = "id";

	protected 	$error = false,
				$count = 0,
				$results = array(),
				$last_insert_id,
				$is_fetchable = false,
				$exists = false;

	protected static $instance = null;

	protected static function getInstance()
	{
		if (! self::$instance) {
			$class = get_called_class();
			self::$instance = new $class();
		}
		return self::$instance;
	}

	protected function query($sql, $bindValues = array())
	{
		$db = Conn::getConnection();

		$st = $db->prepare($sql);

		if ($bindValues) {
			foreach ($bindValues as $key) {
				$st->bindValue(":$key", $this->{$key}, PDO::PARAM_STR);
			}
		}

		if ($st->execute()) {
			if ($this->is_fetchable) {
				$this->results = $st->fetchAll(PDO::FETCH_OBJ);
				$this->count = $st->rowCount();
			}
			return true;
		}

		$this->error = true;

		return false;
	}

	public function save()
	{
		// die(var_dump($this->name));

		if ($this->exists) {
			$this->performUpdate();
		} else {
			if ($this->performInsert()) {
				$this->last_insert_id = Conn::getConnection()->lastInsertId();
			} else {
				$this->last_insert_id = null;
			}
		}

		return ! $this->error;
	}

	public static function all($columns = array("*"))
	{
		$this->is_fetchable = true;
		$instance = self::getInstance();
		$instance->query($instance->buildSelect($columns)->sql);

		return $instance;
	}

	public static function find($id)
	{
		if (! $id) {
			throw new \Exception("Please provide valid id.");
		}

		$instance = self::getInstance();
		$instance->is_fetchable = true;
		$sql = $instance->buildSelect(array("*"))
				->setWhere($instance->getPrimaryKey(), "=", $id)
				->sql;

		// TESTING
		// die($sql);

		$instance->query($sql, array($instance->getPrimaryKey()));

		// Setting exists to true if row found so that
		// this state of the var can be used to determine where
		// save() method will perform update or insert.
		if ($instance->count) {
			$instance->exists = true;
		}

		return $instance;
	}

	public static function where($column, $value, $operator = '=')
	{
		if (! $this->isOperatorAllowed($operator)) {
			throw new \Exception("Invalid operator $operator");
		}

		$instance = self::getInstance();
		$sql = $instance->buildSelect(array("*"))
				->setWhere($column, $operator, $value)
				->sql;

		// TESTING
		// die($sql);

		$instance->query($sql, array($column));

		return $instance;
	}

	public function delete()
	{
		
	}

	public function lastID()
	{
		return $this->last_insert_id;
	}

	public function count()
	{
		return $this->count;
	}

	public function get()
	{
		return $this->results;
	}

	public function first()
	{
		return $this->get()[0];
	}

	public function last()
	{
		return $this->get()[$this->count - 1];
	}

	protected function performInsert()
	{
		$columns = array_keys($this->allProperties());
		$this->buildInsert($columns);

		// TESTING
		// die(var_dump($this->sql));

		return $this->query($this->sql, $columns);
	}

	protected function isOperatorAllowed($operator)
	{
		$operators_allowed = array('=', '>', '<', '>=', '<=', '!=', '<>');
		if (! in_array($operator, $operators_allowed)) {
			return false;
		}

		return true;
	}
	
}
