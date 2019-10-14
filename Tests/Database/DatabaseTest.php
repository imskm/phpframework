<?php

require __DIR__ . '/../init_test_mode.php';

use Core\Facades\DB;
use \Tests\BaseTest;

class DatabaseTest extends BaseTest
{
	public function test_log_object_creates_logger_instance_properly()
	{
		// Should return Database instance
		$db = DB::from('table_name');

		if (!($db instanceof Database)) {
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

}

$test = new DatabaseTest;

// TEST #1: test_log_object_creates_logger_instance_properly
echo "TEST #1: test_log_object_creates_logger_instance_properly: ";
$test->test_log_object_creates_logger_instance_properly();
