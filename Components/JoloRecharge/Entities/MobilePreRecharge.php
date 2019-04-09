<?php

namespace Components\JoloRecharge\Entities;

use Components\JoloRecharge\Entities\AttributeAccessTrait;

/**
 * MobilePreRecharge class
 */
class MobilePreRecharge
{
	protected $attributes;

	use AttributeAccessTrait;

	public function __construct(array $params)
	{
		$this->attributes = $params;
	}
}