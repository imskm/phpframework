<?php

namespace Core\Database;

/**
 * Query class
 */
class Query
{
	protected $operators = [
		'=', '>', '<', '>=', '<=', '<>', '!=', 'in', 'like',
	];
	/** 
	 * Columns that need to be selected in SELECT query
	 */
	protected $columns = ["*"];

	/**
	 * Query string
	 */
	protected $sql_string = "";

	/**
	 * Table name
	 */
	protected $table = "";

	/**
	 * Where column with operators and values
	 * IN operator is also a where operator therefore use
	 * where method to set in operator
	 */
	protected $where = [];

	/**
	 * And Where column with operators and values
	 */
	protected $andWhere = [];

	/**
	 * Or Where column with operators and values
	 */
	protected $orWhere = [];

	/**
	 * Group by columns
	 */
	protected $group = [];

	/**
	 * Bindings of parameters
	 * 'where' => [['param' => 'binding_value'],...]
	 */
	protected $bindings = [
		'where'  	=> [],
		'having' 	=> [],
	];

	/**
	 * Limit values (limit, offset)
	 */
	protected $limit = [];

	/**
	 * Order by values (column, order)
	 */
	protected $order = [];

	public function table($table)
	{
		$this->table = $table;

		return $this;
	}

	public function select(array $columns)
	{
		$this->columns = $columns;

		return $this;
	}

	public function where($column, $operator, $value)
	{
		if ($this->where) {
			throw new \Exception("where must be called once. For combining where use ".
								"andWhere, orWhere, inWhere method call.");
		}
		$this->where = [
			'col' => $column,
			'op'  => $operator,
			'val' => $value
		];
		$this->bindings['where'][] = $value;

		return $this;
	}

	public function andWhere($column, $operator, $value)
	{
		if (!$this->where) {
			throw new \Exception("You havn't set any WHERE caluse first.");
		}
		$this->andWhere[] = [
			'col' => $column,
			'op'  => $operator,
			'val' => $value
		];
		$this->bindings['where'][] = $value;

		return $this;
	}

	public function orWhere($column, $operator, $value)
	{
		if (!$this->where) {
			throw new \Exception("You havn't set any WHERE caluse first.");
		}
		$this->orWhere[] = [
			'col' => $column,
			'op'  => $operator,
			'val' => $value
		];
		$this->bindings['where'][] = $value;

		return $this;
	}

	public function groupBy($column)
	{
		if ($this->where) {
			throw new \Exception("WHERE clause can not be used in aggregate SQL.");
		}
		if (!is_array($column)) {
			$this->group[] = $column;
			return $this;
		}

		foreach ($column as $col) {
			$this->group[] = $col;
		}

		return $this;
	}

	public function raw($sql, array $params = [])
	{
		if ($params) {
			foreach ($params as $param => $value) {
				$this->bindings[] = [$param => $value];
			}
		}
		$this->sql_string = $sql;

		return $this;
	}

	public function limit($limit, $offset = 0)
	{
		$this->limit = [$limit, $offset];

		return $this;
	}

	public function orderBy($column, $order = "ASC")
	{
		$this->order = [$column, $order];

		return $this;
	}


	public function buildSelect()
	{
		$template = "SELECT %s FROM %s";
		if (empty($this->table)) {
			throw new \Exception("Table is missing; use from method to set table");
		}
		if ($this->where) {
			$where = $this->where;
			// Validate operator
			if (!$this->isOperatorAllowed($where['op'])) {
				throw new \Exception("Opeartor \"{$where['op']}\" is invalid");
			}
			$template .= " WHERE {$where['col']} {$where['op']} ?";
		}

		if ($this->andWhere) {
			foreach ($this->andWhere as $where) {
				// Validate operator
				if (!$this->isOperatorAllowed($where['op'])) {
					throw new \Exception("Opeartor \"{$where['op']}\" is invalid");
				}
				$template .= " AND {$where['col']} {$where['op']} ?";
			}
		}

		if ($this->orWhere) {
			foreach ($this->orWhere as $where) {
				// Validate operator
				if (!$this->isOperatorAllowed($where['op'])) {
					throw new \Exception("Opeartor \"{$where['op']}\" is invalid");
				}
				$template .= " OR {$where['col']} {$where['op']} ?";
			}
		}

		if ($this->group) {
			$template .= " GROUP BY " . implode(", ", $this->group);
		}

		if ($this->order) {
			$template .= " " . $this->getOrderByString($this->order);
		}

		if ($this->limit) {
			$template .= " " . $this->getLimitString($this->limit);
		}

		$result = sprintf($template, implode(", ", $this->columns), $this->table);

		$this->sql_string = $result;

		return $this;
	}

	public function getSqlString()
	{
		return $this->sql_string;
	}

	public function getBindings()
	{
		return $this->bindings;
	}

	public function getColumns()
	{
		return $this->columns;
	}

	public function setColumns(array $columns)
	{
		$this->columns = $columns;
	}

	public function isOperatorAllowed($operator)
	{
		return in_array(strtolower($operator), $this->operators, true);
	}

	public function getQueryString()
	{
		if (!($sql = $this->getSqlString())) {
			$sql = $this->buildSelect()->getSqlString();
		}

		return $sql;
	}
}