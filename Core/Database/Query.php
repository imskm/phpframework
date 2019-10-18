<?php

namespace Core\Database;

/**
 * Query class
 */
class Query
{
	/** 
	 * Columns that need to be selected in SELECT query
	 */
	protected $columns = ["*"];

	/**
	 * Query string
	 */
	protected $query;

	/**
	 * Table name
	 */
	protected $table;

	public function from($table)
	{
		$this->table = $table;

		return $this;
	}
}