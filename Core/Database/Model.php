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
	/**
	 * Table name at runtime
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Primary Key of the table
	 * Default is "id" else set by Superclass Model.
	 *
	 * @var string
	 */
	protected $primary_key = "id";

	/**
	 * State of the last executed query.
	 *
	 * @var bool
	 */
	protected $error = false;

	/**
	 * Affected rows by the last DELETE, UPDATE, and INSERT query
	 * Count of rows returned by SELECT query is also stored in $count
	 * but it is not the same in all DBMS.
	 *
	 * @var int
	 */
	protected $count = 0;

	/**
	 * Stores rows returned by SELECT query.
	 *
	 * @var resource
	 */
	protected $results = array();

	/**
	 * ID of last inserted row of Auto_Increment column.
	 *
	 * @var int
	 */
	protected $last_insert_id = null;

	/**
	 * State boolean for making decision whether query() method should fetch
	 * the result of last SELECT query.
	 * If last query was UPDATE, INSERT and DELETE then row is not fetchable.
	 *
	 * @var bool
	 */
	protected $is_fetchable = false;

	/**
	 * Stores state that helps save() method to decide whether current save
	 * operation is for INSERT or UPDATE.
	 * So if the $exists is true then UPDATE operation will be performed
	 * else INSERT operation.
	 *
	 * @var bool
	 */
	protected $exists = false;

	/**
	 * Cached Instance of the Model
	 *
	 * @var int
	 */
	protected static $instance = null;

	protected $where_params = array();

	/**
	 * Create new model instance if not created
	 *
	 * @return $this
	 */
	protected static function getInstance()
	{
		$class = get_called_class();
		if (! isset(self::$instance[$class])) {
			self::$instance[$class] = new $class();
		}
		
		return self::$instance[$class];
	}

	/**
	 * Execute query for the given sql.
	 *
	 * @param string $sql
	 * @param array $bindValues  array of columns to be binded
	 *
	 * @return bool
	 */
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
			}

			// Note: rowCount() returns the number of rows affected by a
			// DELETE, INSERT, or UPDATE statement.
			// If the last SQL statement executed by the associated
			// PDOStatement was a SELECT statement, some databases may
			// return the number of rows returned by that statement.
			// So be carefull when using other DB System except MySQL.
			$this->count = $st->rowCount();
			return true;
		}

		$this->error = true;

		return false;
	}

	/**
	 * Insert / Update Wrapper mehtod.
	 *
	 * @return bool
	 */
	public function save()
	{
		// die(var_dump($this->name));

		if ($this->exists) {
			$this->performUpdate();
		} else {
			if ($this->performInsert()) {
				$this->last_insert_id = Conn::getConnection()->lastInsertId();
			}
		}

		return ! $this->error;
	}

	/**
	 * Execute select query to get all records of a table in the DB.
	 *
	 * @param array $columns
	 * @return $this
	 */
	public static function all($columns = array("*"))
	{
		$instance = self::getInstance();
		$instance->is_fetchable = true;
		$instance->query($instance->buildSelect($columns)->sql);

		return $instance;
	}

	/**
	 * Find the record(s) by given id on primary key.
	 *
	 * @param mixed $id  Primary key of the model
	 * @return $this
	 */
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
			$instance->massStore($instance->get());
		}

		return $instance;
	}

	/**
	 * Execute select query by a specific where column.
	 *
	 * @param string $column
	 * @param mixed $value
	 * @param string $operator
	 * @return $this
	 */
	public static function where($column, $value, $operator = '=')
	{
		$instance = self::getInstance();
		$instance->is_fetchable = true;
		if (! $instance->isOperatorAllowed($operator)) {
			throw new \Exception("Invalid operator $operator");
		}

		$sql = $instance->buildSelect(array("*"))
				->setWhere($column, $operator, $value)
				->sql;

		// TESTING
		// die($sql);

		// $instance->query($sql, array($column));
		$instance->where_params[] = $column;

		return $instance;
	}


	public function andWhere($column, $value, $operator = '=')
	{
		$this->setAndWhere($column, $operator, $value);
		$this->where_params[] = $column;
		
		// TESTING
		// die($this->sql);

		return $this;
	}

	public function orWhere($column, $value, $operator = '=')
	{
		$this->setOrWhere($column, $operator, $value);
		$this->where_params[] = $column;

		return $this;
	}


	/**
	 * Execute delete query on the currently found model / by given id.
	 *
	 * @param mixid $id
	 * @return bool
	 */
	public function delete($id = null)
	{
		$this->is_fetchable = false;
		if (! $id) {
			$id = $this->{$this->getPrimaryKey()};
		} else {
			$this->{$this->getPrimaryKey()} = $id;
		}

		$this->buildDelete($this->getPrimaryKey(), "=", $id);

		// TESTING
		// die(var_dump($this->sql));

		return $this->query($this->sql, array($this->getPrimaryKey()));
	}


	/**
	 * Returns the last inserted row id.
	 *
	 * @return int
	 */
	public function lastID()
	{
		return $this->last_insert_id;
	}

	/**
	 * Returns the count of affected rows by last sql query.
	 *
	 * @return int
	 */
	public function count()
	{
		return $this->count;
	}

	/**
	 * Returns the result of select query from $this->result var.
	 *
	 * @return resource|array
	 */
	public function get()
	{
		if ($this->where_params) {
			$this->query($this->sql, $this->where_params);
			$this->clearWhereParams();
		}

		return $this->results;
	}

	/**
	 * Returns the first result of select query from $this->result var.
	 *
	 * @return resource
	 */
	public function first()
	{
		return $this->get()[0];
	}

	/**
	 * Returns the last result of select query from $this->result var.
	 *
	 * @return resource
	 */
	public function last()
	{
		return $this->get()[$this->count - 1];
	}

	/**
	 * Executes raw sql
	 *
	 *
	 */
	public static function raw($sql, array $params = array())
	{
		$instance = self::getInstance();
		$instance->is_fetchable = true;

		$instance->setRawSql($sql, $params);
		$instance->query($sql, array_keys($params));

		return $instance;
	}

	/**
	 * Performs insert operation.
	 *
	 * @return bool
	 */
	protected function performInsert()
	{
		$this->is_fetchable = false;
		$columns = array_keys($this->allProperties());
		$this->buildInsert($columns);

		// TESTING
		// die(var_dump($this->sql));

		return $this->query($this->sql, $columns);
	}

	/**
	 * Performs update operation.
	 *
	 * @return bool
	 */
	protected function performUpdate()
	{
		$this->is_fetchable = false;
		$columns = array_keys($this->allProperties());

		// Removing Primary key so that ID should not be the part of the SET in Update SQL
		$clened_cols = $this->removeKey($this->getPrimaryKey(), $columns);

		$this->buildUpdate(
			$clened_cols,
			$this->getPrimaryKey(),
			"=",
			$this->{$this->getPrimaryKey()}
		);

		// TESTING
		// die(var_dump($this->sql));

		return $this->query($this->sql, $columns);
	}

	/**
	 * Removes key from the given keys array
	 * Mainly used for removing primary key from keys array.
	 *
	 * @param string $key
	 * @param array $keys
	 * @return array
	 */
	protected function removeKey($key, array $keys)
	{
		unset($keys[ array_search($key, $keys) ]);
		return $keys;
	}

	protected function clearWhereParams()
	{
		$this->where_params = array();
	}
}
