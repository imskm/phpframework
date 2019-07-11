<?php

require __DIR__ . '/../init_test_mode.php';

use \Tests\BaseTest;
use Core\Log\Log;

class LoggerTest
{
	public function test_()
	{
		$this->test_2();
	}

	public function test_2()
	{
		$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

		/*
		$trace = array_reverse($trace);
		$error_stack = array_shift($trace);
		var_dump($error_stack);
		 */

		var_dump($trace[count($trace) - 1]);
	}
}

//$test = new LoggerTest;

// TEST #1: test_phurl_prepare_returns_phurl_instance
//echo "TEST #1: test_phurl_prepare_returns_phurl_instance: ";
//$test->test_phurl_prepare_returns_phurl_instance();
