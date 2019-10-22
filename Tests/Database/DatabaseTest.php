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
		$db = DB::table('table_name');

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
		$gen_result = DB::table('users')->getQueryString();

		if ($gen_result != $expected_result) {
			$this->failed();
			echo "Expected: $expected_result\n";
			echo "Generated: $gen_result\n";
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_builds_where_select_query_properly()
	{
		$expected_result = "SELECT * FROM users WHERE email = ?";
		// Should return Database instance
		$gen_result = DB::table('users')
						->where('email', '=', 'test@test.com')->getQueryString();

		if ($gen_result != $expected_result) {
			$this->failed();
			echo "Expected: $expected_result\n";
			echo "Generated: $gen_result\n";
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_builds_and_where_select_query_properly()
	{
		$expected_result = "SELECT * FROM users WHERE email = ? AND id = ?";
		// Should return Database instance
		$gen_result = DB::table('users')
						->where('email', '=', 'test@test.com')
						->andWhere('id', '=', '1')
						->getQueryString();

		if ($gen_result != $expected_result) {
			$this->failed();
			echo "Expected: $expected_result\n";
			echo "Generated: $gen_result\n";
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_builds_multiple_and_where_select_query_properly()
	{
		$expected_result = "SELECT * FROM users WHERE email = ? AND id = ? ".
							"AND password = ? AND random_col = ?";
		// Should return Database instance
		$gen_result = DB::table('users')
						->where('email', '=', 'test@test.com')
						->andWhere('id', '=', '1')
						->andWhere('password', '=', 'testpasswd')
						->andWhere('random_col', '=', 'rand_col_value')
						->getQueryString();

		if ($gen_result != $expected_result) {
			$this->failed();
			echo "Expected: $expected_result\n";
			echo "Generated: $gen_result\n";
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_builds_or_where_select_query_properly()
	{
		$expected_result = "SELECT * FROM users WHERE email = ? OR id = ?";
		// Should return Database instance
		$gen_result = DB::table('users')
						->where('email', '=', 'test@test.com')
						->orWhere('id', '=', '1')
						->getQueryString();

		if ($gen_result != $expected_result) {
			$this->failed();
			echo "Expected: $expected_result\n";
			echo "Generated: $gen_result\n";
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_builds_multiple_or_where_select_query_properly()
	{
		$expected_result = "SELECT * FROM users WHERE email = ? OR id = ? ".
							"OR password = ? OR random_col = ?";
		// Should return Database instance
		$gen_result = DB::table('users')
						->where('email', '=', 'test@test.com')
						->orWhere('id', '=', '1')
						->orWhere('password', '=', 'testpasswd')
						->orWhere('random_col', '=', 'rand_col_value')
						->getQueryString();

		if ($gen_result != $expected_result) {
			$this->failed();
			echo "Expected: $expected_result\n";
			echo "Generated: $gen_result\n";
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_builds_group_by_select_query_properly()
	{
		$expected_result = "SELECT * FROM users GROUP BY email";
		// Should return Database instance
		$gen_result = DB::table('users')
						->groupBy('email')
						->getQueryString();

		if ($gen_result != $expected_result) {
			$this->failed();
			echo "Expected: $expected_result\n";
			echo "Generated: $gen_result\n";
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_builds_raw_sql_bindings()
	{
		$expected_result = "SELECT id, name, email FROM users WHERE id = ? AND email = ?";
		// Should return Database instance
		$gen_result = DB::raw($expected_result, [
			'id' => 1,
			'email' => 'test@test.com',
		])->getQueryString();

		if ($gen_result != $expected_result) {
			$this->failed();
			echo "Expected: $expected_result\n";
			echo "Generated: $gen_result\n";
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_builds_order_by_select_query_properly()
	{
		$expected_result = "SELECT * FROM users GROUP BY email ORDER BY id DESC";
		// Should return Database instance
		$gen_result = DB::table('users')
						->groupBy('email')
						->orderBy('id', 'DESC')
						->getQueryString();

		if ($gen_result != $expected_result) {
			$this->failed();
			echo "Expected: $expected_result\n";
			echo "Generated: $gen_result\n";
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_builds_limit_select_query_properly()
	{
		$expected_result = "SELECT * FROM users GROUP BY email LIMIT 10 OFFSET 4";
		// Should return Database instance
		$gen_result = DB::table('users')
						->groupBy('email')
						->limit(10, 4)
						->getQueryString();

		if ($gen_result != $expected_result) {
			$this->failed();
			echo "Expected: $expected_result\n";
			echo "Generated: $gen_result\n";
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_basic_select_query()
	{
		$expected_result = 1;
		// Should return Database instance
		$gen_result = DB::table("users")->get();

		// var_dump($gen_result);

		if (!($gen_result[0] instanceof \stdClass)) {
			$this->failed();
			echo "Expected: array of stdClass object\n";
			echo "Generated: not an array of stdClass object\n";
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_where_select_query()
	{
		$expected_result = 1;
		// Should return Database instance
		$gen_result = DB::table("users")->where('id', '=', 2)->get();

		// var_dump($gen_result);

		if (count($gen_result) !== $expected_result) {
			$this->failed();
			echo "Expected: 1 record\n";
			echo "Generated: not 1 record\n";
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_complex_where_select_query()
	{
		$expected_result = 5;
		// Should return Database instance
		$db = DB::table("users")->where('id', '=', 2)
					->orWhere('id', '!=', 6)
					->orWhere('id', '>', 5);
		$gen_result = $db->get();

		// var_dump($gen_result);

		if (count($gen_result) !== $expected_result) {
			$this->failed();
			echo "Expected: $expected_result record\n";
			echo "Generated: " . count($gen_result) . " record\n";
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_complex_count_select_query()
	{
		$expected_result = 4;
		// Should return Database instance
		$db = DB::table("users")
					->where('id', '>', 5);
		$gen_result = $db->count();

		// var_dump($gen_result);

		if ($gen_result !== $expected_result) {
			$this->failed();
			echo "Expected: $expected_result record\n";
			echo "Generated: " . $gen_result . " record\n";
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

// TEST #8: test_builds_group_by_select_query_properly
echo "TEST #8: test_builds_group_by_select_query_properly: ";
$test1->test_builds_group_by_select_query_properly();

// TEST #9: test_builds_raw_sql_bindings
echo "TEST #9: test_builds_raw_sql_bindings: ";
$test1->test_builds_raw_sql_bindings();

// TEST #10: test_builds_order_by_select_query_properly
echo "TEST #10: test_builds_order_by_select_query_properly: ";
$test1->test_builds_order_by_select_query_properly();

// TEST #11: test_builds_limit_select_query_properly
echo "TEST #11: test_builds_limit_select_query_properly: ";
$test1->test_builds_limit_select_query_properly();

// TEST #12: test_basic_select_query
echo "TEST #12: test_basic_select_query: ";
$test1->test_basic_select_query();

// TEST #13: test_where_select_query
echo "TEST #13: test_where_select_query: ";
$test1->test_where_select_query();

// TEST #14: test_complex_where_select_query
echo "TEST #14: test_complex_where_select_query: ";
$test1->test_complex_where_select_query();

// TEST #15: test_complex_count_select_query
echo "TEST #15: test_complex_count_select_query: ";
$test1->test_complex_count_select_query();
