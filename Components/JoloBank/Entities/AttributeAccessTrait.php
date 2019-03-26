<?php

namespace Components\JoloBank\Entities;

trait AttributeAccessTrait
{
	public function __get($key)
	{
		if (!array_key_exists($key, $this->attributes)) {
			throw new \Exception("Undefined property $key");
		}

		return $this->attributes[$key];
	}

	public function allAttributes()
	{
		return $this->attributes;
	}
}