<?php

namespace Tests;

trait BaseTestTrait
{
	protected $state;

	public function __construct()
	{
		$this->state = true;
		parent::__construct();
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
