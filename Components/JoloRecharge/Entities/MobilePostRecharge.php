<?php

namespace Components\JoloRecharge\Entities;

use Components\JoloRecharge\Entities\AttributeAccessTrait;

/**
 * MobilePostRecharge class
 */
class MobilePostRecharge
{
	protected $attributes;

	use AttributeAccessTrait;

	public function __construct(array $params)
	{
		$this->attributes = $params;
	}
}