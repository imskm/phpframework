<?php

namespace Components\JoloRecharge\Entities;

use Components\JoloRecharge\Entities\AttributeAccessTrait;

/**
 * DthRecharge class
 */
class DthRecharge
{
	protected $attributes;

	use AttributeAccessTrait;

	public function __construct(array $params)
	{
		$this->attributes = $params;
	}
}