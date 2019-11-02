<?php

namespace Tests\Resource;

use Core\Facades\DB;
use Components\Filter\Filter;
use Components\Filter\FilterInterface;

/**
 * UserResource Test
 */
class UserResource extends Filter implements FilterInterface
{
	protected $selectable = [
		'id',
		'name',
		'email',
	];
	
	public function query()
	{
		return DB::table('users');
	}

	/**
	 * TEMP: Just for testing purpose.
	 * Standard way to override the limit propery is to define a limit property
	 * up top with limit value.
	 */
	public function setLimit($limit)
	{
		$this->limit = $limit;

		return $this;
	}
}