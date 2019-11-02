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

	public function __construct(array $args = [])
	{
		$this->args = $args;

		// coming from drived class
		$this->base_query = $this->query();
	}

	public function get()
	{
		if ($this->args) {
			$this->updateQuery($this->args);
		}

		$this->total_rows = (int) $this->base_query->select(['count(*) as rows'])
												->get()[0]->rows;

		// If summable property is set by user in his derived class
		//    then fire off sum query also.
		if (isset($this->summable))
		$this->sum_row    = $this->base_query->select($this->buildSummableColumns())
												->get()[0];

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
		// 

		return $this;
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
		return (int) ($page <= 1 ? 0 : $page * $this->limit);
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

}