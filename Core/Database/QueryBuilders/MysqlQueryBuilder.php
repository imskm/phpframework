<?php

namespace Core\Database\QueryBuilders;

use \PDO;
use Core\Database\Query;
use Core\Database\Database;
use Core\Database\Interfaces\QueryBuilderInterface;

/**
 * MysqlQueryBuilder query builder
 */
class MysqlQueryBuilder extends Query implements QueryBuilderInterface
{

	public function getLimitString(array $limit)
	{
		$result = "LIMIT {$this->limit[0]}" .
				($this->limit[1] ? " OFFSET {$this->limit[1]}" : "");

		return $result;
	}

	public function getOrderByString(array $order)
	{
		return "ORDER BY {$order[0]} {$order[1]}";
	}

	public function count(Database $db)
	{
		$orig = $this->getColumns();
		$this->setColumns(["count(*)"]);
		$sql_string = $this->getQueryString();

		if (!$db->execute($sql_string, $this->getBindings())) {
			throw new \Exception($db->getError());
		}

		// Restoring original columns
		$this->setColumns($orig);

		return (int) $db->getPdoStatement()->fetchColumn();
	}
	
}