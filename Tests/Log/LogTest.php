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

		Log::error("This is an error message");

		return true;
	}

	public function test_all_callable_methods_can_be_called()
	{
		try {
			Log::emergency("emergency");
			Log::alert("alert");
			Log::critical("critical");
			Log::error("error");
			Log::warning("warning");
			Log::notice("notice");
			Log::info("info");
			Log::notice("notice");

		} catch (\Exception $e) {
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_not_callable_methods_should_not_be_called()
	{
		try {
			Log::writeLog("error", "Write Log");

		} catch (\Exception $e) {
			$this->passed();
			return true;
		}
		$this->failed();
		
		return false;
	}

	public function test_log_message_is_formatted_properly()
	{
		$regex = "/^<([a-z]+)> "
				."([0-9]{4})-([0-9]{2})-([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2}) "
				."- "
				."(.*)\.php"
				."\(([0-9]+)\) "
				."(.*)$/";
		$msg = Log::formatMessage("error", "This is an error message #12../\?");
		if(preg_match($regex, $msg) === 0) {
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

// TEST #2: test_all_callable_methods_can_be_called
echo "TEST #2: test_all_callable_methods_can_be_called: ";
$test->test_all_callable_methods_can_be_called();

// TEST #3: test_not_callable_methods_should_not_be_called
echo "TEST #3: test_not_callable_methods_should_not_be_called: ";
$test->test_not_callable_methods_should_not_be_called();

// TEST #4: test_log_message_is_formatted_properly
echo "TEST #4: test_log_message_is_formatted_properly: ";
$test->test_log_message_is_formatted_properly();
