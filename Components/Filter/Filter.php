<?php

namespace Components\Filter;

use Core\Database\Database;

/**
 * Filter class
 */
class Filter
{
	protected $args;

	protected $base_query;

	/**
	 * @var $limit  record limit
	 */
	protected $limit = 15;

	/**
	 * @var $total_rows  Total rows for the given query
	 */
	protected $total_rows = 0;

	/**
	 * @var $selectable Fields to be selected, default is all fields
	 */
	protected $selectable = [];

	/**
	 * @var $sum_row  Object that holds summable fields row
	 */
	protected $sum_row;

	protected $allowed_operators = [
		'eq'  => '=',
		'ne'  => '<>',
		'lt'  => '<',
		'le'  => '<=',
		'gt'  => '>',
		'ge'  => '>=',
		// 'bn'  => '',
	];

	public function __construct(array $args = [])
	{
		$this->args = $args;

		// coming from drived class
		$this->base_query = $this->query();
	}

	public function get()
	{
		if ($this->args) {
			$this->updateQuery($this->base_query, $this->args);
		}

		$this->total_rows = (int) $this->base_query
			  ->select(['count(*) as rows'])->get()[0]->rows;

		// If summable property is set by user in his derived class
		//    then fire off sum query also.
		// @Note: Sum does not care about pagination. It always adds all rows with
		//    with filter.
		if (isset($this->summable)) {
			$this->sum_row = $this->base_query
				  ->select($this->buildSummableColumns())->get()[0];
		}

		if ($this->selectable)  {
			$this->base_query->select($this->selectable);
		}

		// Adds limit and offset clause in sql
		//  every query is ran with limit to protect loading of entire db
		$this->addPagination();

		return $this->base_query->get();
	}

	protected function updateQuery(Database $query, array $args)
	{
		// If something is wrong in $args then don't apply the fileter
		//   default back to base filter
		try {
			$columns 	= $this->getColumnsFromArg($args);

			foreach ($columns as $key => $column) {
				$operator 	= $this->getOperatorFromArg($args, $column);
				$value 		= $this->getValueFromArg($args, $column);

				if ($key == 0) {
					$this->base_query->where($column, $operator, $value);
					continue;
				}

				$this->base_query->andWhere($column, $operator, $value);
			}

			// 

		} catch (\Exception $e){
			// @Incomplete: Need to undo what I did in try block
		}

		return $this;
	}

	protected function getColumnsFromArg(array $args)
	{
		if (!isset($args['filters'])) {
			throw new \Exception("Missing 'filters' param in argument");
		}

		foreach ($args['filters'] as $param) {
			if (!in_array($param, $this->selectable)) {
				throw new \Exception("Field '{$param}' is not allowed", 1);
			}
		}

		return $args['filters'];
	}

	protected function getOperatorFromArg(array $args, $column)
	{
		if (!isset($args[$column])) {
			throw new \Exception("Missing '{$column}' param in argument");
		} else if (count($args[$column]) != 2 && count($args[$column]) != 1) {
			throw new \Exception("Column requires 1 or 2 values, " 
				. count($args[$column]) . " given.");
		}

		if (!is_string($args[$column][0])) {
			throw new \Exception("Value of '{$column}' array must be string");
		}

		$op = $args[$column][0];
		if (!array_key_exists($op, $this->allowed_operators)) {
			throw new \Exception("Operator '{$op}' not allowed.");
		}

		return $this->allowed_operators[$op];
	}

	protected function getValueFromArg(array $args, $column)
	{
		// No validation is required as we did it already in getOperatorFromArg()
		//  method. Just don't change the order of calling this method. This method
		//  must be called after getOperatorFromArg() method

		return $args[$column][1];
	}

	protected function addPagination()
	{
		$this->limit = $this->limit <= 0 ? 1 : $this->limit;
		$offset = isset($this->args['page']) ?
					$this->calcOffset($this->args['page']) : 0;

		$this->base_query->limit($this->limit, $offset);
	}

	protected function calcOffset($page)
	{
		return (int) ($page <= 1 ? 0 : ($page - 1) * $this->limit);
	}

	public function getTotalRowCount()
	{
		return $this->total_rows;
	}

	protected function buildSummableColumns()
	{
		if (!is_array($this->summable)) {
			throw new \Exception("summable property must be an array");
		}

		$column = [];
		foreach ($this->summable as $summable) {
			$column [] = "SUM({$summable}) AS total_{$summable}";
		}

		return $column;
	}

	public function getSummable()
	{
		return $this->sum_row;
	}

	public function getQueryString()
	{
		return $this->base_query->getQueryString();
	}

}