<?php

namespace Tests\Resource;

require __DIR__ . '/../init_test_mode.php';

use Core\Facades\DB;
use \Tests\BaseTest;
use Core\Database\Database;
use Components\Filter\FilterInterface;

class ResourceTest extends BaseTest
{
	public function test_resource_object_created_properly()
	{
		// Should return Database instance
		$res = new UserResource;

		if (!($res instanceof FilterInterface)) {
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

}

$test1 = new ResourceTest;
// TEST #1: test_resource_object_created_properly
echo "TEST #1: test_resource_object_created_properly: ";
$test1->test_resource_object_created_properly();
