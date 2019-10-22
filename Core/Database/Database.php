<?php

namespace Core\Database;

use \PDO;
use Core\Database\Interfaces\QueryBuilderInterface;

/**
 * Database class
 */
class Database
{
	private $pdo;

	/**
	 * Query builder
	 *
	 */
	private $query;

	/**
	 * PHP's prepared statement
	 */
	private $st;

	private $has_error;

	public function __construct(PDO $pdo, QueryBuilderInterface $query_builder)
	{
		$this->pdo 				= $pdo;
		$this->query 		 	= $query_builder;
	}

	public function execute($sql, array $bindings = [])
	{
		$this->st = $this->pdo->prepare($sql);

		$index = 1;
		foreach ($bindings as $binding) {
			foreach ($binding as $value) {
				if (is_bool($value)) {
					$this->st->bindValue($index++, $value, PDO::PARAM_BOOL);

				} else if (is_int($value)) {
					$this->st->bindValue($index++, $value, PDO::PARAM_INT);

				} else if (is_null($value)) {
					$this->st->bindValue($index++, $value, PDO::PARAM_NULL);

				} else {
					$this->st->bindValue($index++, $value, PDO::PARAM_STR);
				}
			}
		}

		return $this->has_error = $this->st->execute();
	}

	public function get()
	{
		$sql_string = $this->getQueryString();

		if (!$this->execute($sql_string, $this->query->getBindings())) {
			throw new \Exception($this->getError());
		}

		return $this->st->fetchAll(PDO::FETCH_OBJ);
	}

	public function first()
	{
		// 
	}

	public function last()
	{
		//
	}

	public function count()
	{
		// TODO For now count() to work I exposed execute() method
		//      find the better solution so that execute() remains protected
		return $this->query->count($this);
	}

	public function getError()
	{
		$error_string = "";
		$errors = $this->st->has_errorInfo();

		if ($errors[0]) {
			$error_string = "[{$errors[0]}]: {$errors[2]}";
		}

		return $error_string;
	}

	public function getQueryString()
	{
		return $this->query->getQueryString();
	}

	public function getPdoStatement()
	{
		return $this->st;
	}


	/**
	 * This captures all the method call that does not exist here but
	 * it translates user's method call to QueryBuilder object. All the calls
	 * goes to QueryBuilder object such as select, where, andWhere, etc.
	 * 
	 */
	public function __call($name, $arguments)
	{
		if (is_callable([$this->query, $name]) === false) {
			throw new \Exception("Method \"$name\" is not callable or exist!");
		}

		if (call_user_func_array([$this->query, $name], $arguments) === false) {
			throw new \Exception("An has_error occured when calling \"$name\" dynamically.");
		}

		return $this;
	}


}