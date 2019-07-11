<?php

require __DIR__ . '/../init_test_mode.php';

use Core\Log\Log;
use \Tests\BaseTest;
use Core\Log\LoggerInterface;

class LoggerTest extends BaseTest
{
	public function test_log_object_creates_logger_instance_properly()
	{
		$logger = Log::getLoggerInstance();
		if (!($logger instanceof LoggerInterface)) {
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}
}

$test = new LoggerTest;

// TEST #1: test_log_object_creates_logger_instance_properly
echo "TEST #1: test_log_object_creates_logger_instance_properly: ";
$test->test_log_object_creates_logger_instance_properly();
