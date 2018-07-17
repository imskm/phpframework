<?php

namespace Tests;

class Test extends \Tests\BaseTest
{
	protected $data;

	public function __construct()
	{
		parent::__construct();
	}

	public function doTesting()
	{
		$none = 1;
		echo "Unexisting variable $none + 1";
		echo "Hello";
	}
}
