<?php

namespace Core\Database\Connectors;

use Core\Database\Interfaces\ConnectorInterface;

/**
 * Mysql class
 */
class Mysql implements ConnectorInterface
{
	private $pdo;

	public function __construct($dsn, $username, $password)
	{
		try {
			$this->pdo = new \PDO($dsn, $username, $password);
			$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch (\Exception $e) {
			throw new \Exception("Mysql: Error: " . $e->getMessage());
		}
	}

	public function getPdo()
	{
		return $this->pdo;
	}
}