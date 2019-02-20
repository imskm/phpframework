<?php

namespace Tests;

abstract class BaseTest
{
	protected $state;

	public function __construct()
	{
		$this->state = true;
	}

	protected function passed()
	{
		echo "\033[1m\033[32m PASSED\033[0m\n";
	}

	protected function failed()
	{
		echo "\033[1m\033[31m FAILED\033[0m\n";
	}
}
