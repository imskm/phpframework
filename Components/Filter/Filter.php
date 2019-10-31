<?php

namespace Components\Filter;

/**
 * Filter class
 */
class Filter
{
	protected $args;

	public function __construct(array $args = [])
	{
		$this->args = $args;
	}
}