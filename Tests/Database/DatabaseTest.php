<?php

require __DIR__ . '/../init_test_mode.php';

use Core\Facades\DB;
use \Tests\BaseTest;
use Core\Database\Database;

class DatabaseTest extends BaseTest
{
	public function test_created_properly()
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

	public function test_builds_basic_select_query_properly()
	{
		$expected_result = "SELECT * FROM users";
		// Should return Database instance
		$gen_result = DB::from('users')->getQueryString();

		if ($gen_result != $expected_result) {
			echo "Expected: $expected_result\n";
			echo "Generated: $gen_result\n";
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_builds_where_select_query_properly()
	{
		$expected_result = "SELECT * FROM users WHERE email = :email";
		// Should return Database instance
		$gen_result = DB::from('users')
						->where('email', '=', 'test@test.com')->getQueryString();

		if ($gen_result != $expected_result) {
			echo "Expected: $expected_result\n";
			echo "Generated: $gen_result\n";
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_builds_and_where_select_query_properly()
	{
		$expected_result = "SELECT * FROM users WHERE email = :email AND id = :id";
		// Should return Database instance
		$gen_result = DB::from('users')
						->where('email', '=', 'test@test.com')
						->andWhere('id', '=', '1')
						->getQueryString();

		if ($gen_result != $expected_result) {
			echo "Expected: $expected_result\n";
			echo "Generated: $gen_result\n";
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_builds_multiple_and_where_select_query_properly()
	{
		$expected_result = "SELECT * FROM users WHERE email = :email AND id = :id ".
							"AND password = :password AND random_col = :random_col";
		// Should return Database instance
		$gen_result = DB::from('users')
						->where('email', '=', 'test@test.com')
						->andWhere('id', '=', '1')
						->andWhere('password', '=', 'testpasswd')
						->andWhere('random_col', '=', 'rand_col_value')
						->getQueryString();

		if ($gen_result != $expected_result) {
			echo "Expected: $expected_result\n";
			echo "Generated: $gen_result\n";
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_builds_or_where_select_query_properly()
	{
		$expected_result = "SELECT * FROM users WHERE email = :email OR id = :id";
		// Should return Database instance
		$gen_result = DB::from('users')
						->where('email', '=', 'test@test.com')
						->orWhere('id', '=', '1')
						->getQueryString();

		if ($gen_result != $expected_result) {
			echo "Expected: $expected_result\n";
			echo "Generated: $gen_result\n";
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_builds_multiple_or_where_select_query_properly()
	{
		$expected_result = "SELECT * FROM users WHERE email = :email OR id = :id ".
							"OR password = :password OR random_col = :random_col";
		// Should return Database instance
		$gen_result = DB::from('users')
						->where('email', '=', 'test@test.com')
						->orWhere('id', '=', '1')
						->orWhere('password', '=', 'testpasswd')
						->orWhere('random_col', '=', 'rand_col_value')
						->getQueryString();

		if ($gen_result != $expected_result) {
			echo "Expected: $expected_result\n";
			echo "Generated: $gen_result\n";
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

}

$test1 = new DatabaseTest;
// TEST #1: test_created_properly
echo "TEST #1: test_created_properly: ";
$test1->test_created_properly();

// TEST #2: test_builds_basic_select_query_properly
echo "TEST #2: test_builds_basic_select_query_properly: ";
$test1->test_builds_basic_select_query_properly();

// TEST #3: test_builds_where_select_query_properly
echo "TEST #3: test_builds_where_select_query_properly: ";
$test1->test_builds_where_select_query_properly();

// TEST #4: test_builds_and_where_select_query_properly
echo "TEST #4: test_builds_and_where_select_query_properly: ";
$test1->test_builds_and_where_select_query_properly();

// TEST #5: test_builds_multiple_and_where_select_query_properly
echo "TEST #5: test_builds_multiple_and_where_select_query_properly: ";
$test1->test_builds_multiple_and_where_select_query_properly();

// TEST #6: test_builds_or_where_select_query_properly
echo "TEST #6: test_builds_or_where_select_query_properly: ";
$test1->test_builds_or_where_select_query_properly();

// TEST #7: test_builds_multiple_or_where_select_query_properly
echo "TEST #7: test_builds_multiple_or_where_select_query_properly: ";
$test1->test_builds_multiple_or_where_select_query_properly();
