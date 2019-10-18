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

	public function from($table)
	{
		$this->query->from($table);

		return $this;
	}


}