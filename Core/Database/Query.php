<?php

namespace Core\Database;

/**
 * Query class
 */
class Query
{
	protected $operators = [
		'=', '>', '<', '>=', '<=', '<>', '!=', 'in',
	];
	/** 
	 * Columns that need to be selected in SELECT query
	 */
	protected $columns = ["*"];

	/**
	 * Query string
	 */
	protected $query_string;

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
		'where' => [],
	];

	public function from($table)
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
		$this->bindings['where'][] = [$column => $value];

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
		$this->bindings['where'][] = [$column => $value];

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
		$this->bindings['where'][] = [$column => $value];

		return $this;
	}



	public function buildSelect()
	{
		$template = "SELECT %s FROM %s";
		if (empty($this->table)) {
			throw new \Exception("Table is missing: use from method to set table");
		}
		if ($this->where) {
			$where = $this->where;
			// Validate operator
			if (!$this->isOperatorValid($where['op'])) {
				throw new \Exception("Opeartor \"{$where['op']}\" is invalid");
			}
			$template .= " WHERE {$where['col']} {$where['op']} :{$where['col']}";
		}

		if ($this->andWhere) {
			foreach ($this->andWhere as $where) {
				// Validate operator
				if (!$this->isOperatorValid($where['op'])) {
					throw new \Exception("Opeartor \"{$where['op']}\" is invalid");
				}
				$template .= " AND {$where['col']} {$where['op']} :{$where['col']}";
			}
		}

		if ($this->orWhere) {
			foreach ($this->orWhere as $where) {
				// Validate operator
				if (!$this->isOperatorValid($where['op'])) {
					throw new \Exception("Opeartor \"{$where['op']}\" is invalid");
				}
				$template .= " OR {$where['col']} {$where['op']} :{$where['col']}";
			}
		}

		if ($this->group) {
			$template .= " GROUP BY " . implode(", ", $this->group);
		}

		$result = sprintf($template, implode(", ", $this->columns), $this->table);

		return $this->query_string = $result;
	}

	public function getBindings()
	{
		return $this->bindings;
	}

	public function isOperatorValid($operator)
	{
		return in_array(strtolower($operator), $this->operators, true);
	}
}