<?php

namespace Components\JoloBank\Entities;

use Components\JoloBank\Entities\AttributeAccessTrait;
use Components\JoloBank\Exceptions\JoloMissingParamException;

/**
 * JoloAgent class
 */
class JoloAgent
{
	protected $attributes;

	use AttributeAccessTrait;

	public function __construct(array $params)
	{
		$this->attributes = $params;
	}
}