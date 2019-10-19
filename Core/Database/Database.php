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
	private $query;

	public function __construct(PDO $pdo, QueryBuilderInterface $query_builder)
	{
		$this->pdo 				= $pdo;
		$this->query 		 	= $query_builder;
	}

	public function get()
	{
		$query_string = $this->query->buildSelect();
		$bindings     = $this->query->getBindings();
	}

	public function getQueryString()
	{
		return $this->query->buildSelect();
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
			throw new \Exception("An error occured when calling \"$name\" dynamically.");
		}

		return $this;
	}


}