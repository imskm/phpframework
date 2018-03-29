<?php

namespace Core;

/**
* Base calss
* handles dynamic getting and setting of properties
*/
class Base
{
	protected $data = array();

	public function __get($key)
	{
		if (! isset($this->data[$key])) {
			throw new \Exception("Trying to access undefined property {$key}");
		}

		return $this->data[$key];
	}

	public function __set($key, $value)
	{
		$this->data[$key] = $value;
		return;
	}

	public function exist($key)
	{
		return isset($this->data[$key]);
	}

	public function allProperties()
	{
		if (! $this->data) {
			return null;
		}

		return $this->data;
	}
	
}