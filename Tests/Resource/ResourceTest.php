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
		$resource = new UserResource;

		if (!($resource instanceof FilterInterface)) {
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_get_params_are_passed_properly()
	{
		// created_at[]=before&created_at[]=2019-10-29&filters[]=created_at
		$_GET['created_at'] = ['before', '2019-10-29'];
		$_GET['filters']    = ['created_at', ];
		$resource = new UserResource($_GET);

		if (!($resource instanceof FilterInterface)) {
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_base_filter_is_working_properly()
	{
		$resource = new UserResource();

		// Runs base query on database and returns array of stdClass object
		//   for each database record
		$result = $resource->get();

		if (!is_array($result) || count($result) < 5) {
			var_dump($result);
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_overriding_limit_property_works_properly()
	{
		$limit = 4;
		$resource = new UserResource();

		// Runs base query on database and returns array of stdClass object
		//   for each database record
		$result = $resource->setLimit($limit)->get();

		if (!is_array($result) || count($result) != 4) {
			var_dump($result);
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_row_count_is_working_properly()
	{
		$limit = 4;
		$resource = new UserResource();

		// Runs base query on database and returns array of stdClass object
		//   for each database record
		$result = $resource->get();
		exit;

		if (!is_array($result) || $resource->getTotalRowCount() < $limit) {
			var_dump($result);
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

}

$test1 = new ResourceTest;
// // TEST #1: test_resource_object_created_properly
// echo "TEST #1: test_resource_object_created_properly: ";
// $test1->test_resource_object_created_properly();

// // TEST #2: test_get_params_are_passed_properly
// echo "TEST #2: test_get_params_are_passed_properly: ";
// $test1->test_get_params_are_passed_properly();

// // TEST #3: test_base_filter_is_working_properly
// echo "TEST #3: test_base_filter_is_working_properly: ";
// $test1->test_base_filter_is_working_properly();

// // TEST #4: test_overriding_limit_property_works_properly
// echo "TEST #4: test_overriding_limit_property_works_properly: ";
// $test1->test_overriding_limit_property_works_properly();

// TEST #5: test_row_count_is_working_properly
echo "TEST #5: test_row_count_is_working_properly: ";
$test1->test_row_count_is_working_properly();
