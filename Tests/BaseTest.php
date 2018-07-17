<?php

namespace Tests;

abstract class BaseTest
{
	protected $state;

	public function __construct()
	{
		$this->state = true;
	}
}
