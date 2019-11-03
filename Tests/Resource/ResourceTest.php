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

		if (!is_array($result) || $resource->getTotalRowCount() < $limit) {
			var_dump($result);
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_sum_on_base_query_properly()
	{
		$sum_of_id = 34;
		$resource = new UserResource();

		// First atleast one time I need to call get() before getSummable(),
		//  and getTotalRowCount()
		$resource->get();

		// Runs sum query for summable fields and return single row consisting of
		//   all the summable fields with sum. Fields are named as total_<field>
		//   @Note: user must declare summable propery in his ResourceClass
		$result = $resource->getSummable();

		if (!($result instanceof \stdClass) || $result->total_id < $sum_of_id) {
			var_dump($result);
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_pagination_on_base_filter_is_working_properly()
	{
		$limit = 2;
		$_GET['page'] = 2;
		$sum_of_id = 34;

		$resource = new UserResource($_GET);
		$result = $resource->setLimit($limit)->get();

		if (!is_array($result) || count($result) != $limit) {
			var_dump($result);
			var_dump($resource->getQueryString());
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_sum_works_even_with_pagination_query_properly()
	{
		$limit = 2;
		$_GET['page'] = 2;

		// @Note: Sum always considers all rows and ignores limit and offset clause
		//        it is expected behaviour.
		$sum_of_id = 34;

		$resource = new UserResource($_GET);
		$result = $resource->setLimit($limit)->get();
		$sum = $resource->getSummable();

		if (!is_array($result) || count($result) != $limit || $sum->total_id != $sum_of_id) {
			echo "\nFirst check the database. It is more likely that db may have changed.\n";
			var_dump($result);
			var_dump($sum);
			var_dump($resource->getQueryString());
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_basic_filter_with_basic_where_works_properly()
	{
		$result_rows = 2;
		// id[]=before&id[]=6&filters[]=id
		$_GET['id'] = ['lt', '7'];
		$_GET['filters']    = ['id', ];
		unset($_GET['page']);

		$resource = new UserResource($_GET);
		$result = $resource->get();

		if (!is_array($result) || count($result) != $result_rows) {
			echo "\nFirst check the database. It is more likely that db may have changed.\n";
			var_dump($result);
			var_dump($resource->getQueryString());
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_count_even_basic_filter_with_basic_where_applied_properly()
	{
		$result_rows = 2;
		// id[]=before&id[]=6&filters[]=id
		$_GET['id'] = ['lt', '7'];
		$_GET['filters']    = ['id', ];
		unset($_GET['page']);

		$resource = new UserResource($_GET);
		$result = $resource->get();
		$count = $resource->getTotalRowCount();

		if (!is_array($result) || count($result) != $result_rows || $count != $result_rows) {
			echo "\nFirst check the database. It is more likely that db may have changed.\n";
			var_dump($result);
			var_dump($count);
			var_dump($resource->getQueryString());
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_sum_even_basic_filter_with_basic_where_applied_properly()
	{
		$result_rows = 2;
		// id[]=before&id[]=6&filters[]=id
		$_GET['id'] = ['lt', '7'];
		$_GET['filters']    = ['id', ];
		unset($_GET['page']);

		$resource = new UserResource($_GET);
		$result = $resource->get();
		$sum = $resource->getSummable()->total_id;

		if (!is_array($result) || count($result) != $result_rows || $sum != 8) {
			echo "\nFirst check the database. It is more likely that db may have changed.\n";
			var_dump($result);
			var_dump($sum);
			var_dump($resource->getQueryString());
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

// TEST #2: test_get_params_are_passed_properly
echo "TEST #2: test_get_params_are_passed_properly: ";
$test1->test_get_params_are_passed_properly();

// TEST #3: test_base_filter_is_working_properly
echo "TEST #3: test_base_filter_is_working_properly: ";
$test1->test_base_filter_is_working_properly();

// TEST #4: test_overriding_limit_property_works_properly
echo "TEST #4: test_overriding_limit_property_works_properly: ";
$test1->test_overriding_limit_property_works_properly();

// TEST #5: test_row_count_is_working_properly
echo "TEST #5: test_row_count_is_working_properly: ";
$test1->test_row_count_is_working_properly();

// TEST #6: test_sum_on_base_query_properly
echo "TEST #6: test_sum_on_base_query_properly: ";
$test1->test_sum_on_base_query_properly();

// TEST #7: test_pagination_on_base_filter_is_working_properly
echo "TEST #7: test_pagination_on_base_filter_is_working_properly: ";
$test1->test_pagination_on_base_filter_is_working_properly();

// TEST #8: test_sum_works_even_with_pagination_query_properly
echo "TEST #8: test_sum_works_even_with_pagination_query_properly: ";
$test1->test_sum_works_even_with_pagination_query_properly();

// TEST #9: test_basic_filter_with_basic_where_works_properly
echo "TEST #9: test_basic_filter_with_basic_where_works_properly: ";
$test1->test_basic_filter_with_basic_where_works_properly();

// TEST #10: test_count_even_basic_filter_with_basic_where_applied_properly
echo "TEST #10: test_count_even_basic_filter_with_basic_where_applied_properly: ";
$test1->test_count_even_basic_filter_with_basic_where_applied_properly();

// TEST #11: test_sum_even_basic_filter_with_basic_where_applied_properly
echo "TEST #11: test_sum_even_basic_filter_with_basic_where_applied_properly: ";
$test1->test_sum_even_basic_filter_with_basic_where_applied_properly();
