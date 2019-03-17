<?php

require __DIR__ . '/../../init_test_mode.php';

use \Tests\BaseTest;
use \Core\Support\Storages\SessionStorage;

/**
 * SessionStorageTest class
 */
class SessionStorageTest extends BaseTest
{
	protected $storage_name;
	protected $storage;

	public function __construct($storage_name)
	{
		$this->storage_name = $storage_name;
		$this->storage = new SessionStorage($this->storage_name);
	}

	public function test_storage_created_successfully()
	{
		if (!$this->storage instanceof SessionStorage) {
			$this->failed();

			return false;
		}
		$this->passed();

		return true;
	}

	public function test_a_key_is_set_in_storage()
	{
		$key = 1;
		$value = ['value1', 'value2'];

		$this->storage->set($key, $value);

		if (!isset($_SESSION[$this->storage_name][$key])) {
			$this->failed();

			return false;
		}
		$this->passed();

		return true;
	}

	public function test_get_returns_correct_value_that_is_already_set()
	{
		$key = 2;
		$value = 'test get';

		$this->storage->set($key, $value);

		// Check get by getting the value by key
		$result = $this->storage->get($key);

		if ($result != $value) {
			$this->failed();

			return false;
		}
		$this->passed();

		return true;
	}

	public function test_get_returns_null_for_non_existing_key()
	{
		$key = 'notexists';
		if ($this->storage->get($key) !== null) {
			$this->failed();

			return false;
		}
		$this->passed();

		return true;
	}

	public function test_setting_values_is_set_properly_in_storage()
	{
		$key1 = 'key1';
		$value1 = 'value1';
		$key2 = 'key2';
		$value2 = 'value2';

		$this->storage->set($key1, $value1);
		$this->storage->set($key2, $value2);

		if ($this->storage->get($key1) != $value1) {
			$this->failed();

			return false;
		}

		if ($this->storage->get($key2) != $value2) {
			$this->failed();

			return false;
		}
		$this->passed();

		return true;
	}

	public function test_set_key_is_unset_properly()
	{
		$key = 'unset';
		$value = 'to be unset';

		$this->storage->set($key, $value);
		$this->storage->unset($key);

		if ($this->storage->get($key) !== null || isset($_SESSION[$this->storage_name][$key])) {
			$this->failed();

			return false;
		}
		$this->passed();

		return true;
	}
	
	public function test_all_returns_entire_storge()
	{
		// Destory the previous storage
		// After destroying storage, we should not use storage manipulation
		// on the same object any more.
		$this->storage->destroy();

		$key1 = 'key1';
		$value1 = 'value1';
		$key2 = 'key2';
		$value2 = 'value2';

		$this->storage->set($key1, $value1);
		$this->storage->set($key2, $value2);

		if (count($this->storage->all()) !== 2) {
			$this->failed();

			return false;
		}
		$this->passed();

		return true;
	}

	public function test_destory_destorys_storage_correctly()
	{
		$this->storage->destroy();

		if (isset($_SESSION[$this->storage_name])) {
			$this->failed();

			return false;
		}
		$this->passed();

		return true;
	}



	public function test_get_works_even_if_get_is_the_only_first_call_that_ever_made()
	{
		try {
			if ($this->storage->get('somekey') !== null) {
				$this->failed();

				return false;
			}
		} catch (\Exception $e) {
			// It should not fail because session is created on object creation time
			$this->failed();

			return false;
		}
		$this->passed();

		return true;
	}
}

$test = new SessionStorageTest('test_storage');

// TEST #1 : test_storage_created_successfully
echo "TEST #1 : test_storage_created_successfully: ";
$test->test_storage_created_successfully();

// TEST #2 : test_a_key_is_set_in_storage
echo "TEST #2 : test_a_key_is_set_in_storage: ";
$test->test_a_key_is_set_in_storage();

// TEST #3 : test_get_returns_correct_value_that_is_already_set
echo "TEST #3 : test_get_returns_correct_value_that_is_already_set: ";
$test->test_get_returns_correct_value_that_is_already_set();

// TEST #4 : test_get_returns_null_for_non_existing_key
echo "TEST #4 : test_get_returns_null_for_non_existing_key: ";
$test->test_get_returns_null_for_non_existing_key();

// TEST #5 : test_setting_values_is_set_properly_in_storage
echo "TEST #5 : test_setting_values_is_set_properly_in_storage: ";
$test->test_setting_values_is_set_properly_in_storage();

// TEST #6 : test_set_key_is_unset_properly
echo "TEST #6 : test_set_key_is_unset_properly: ";
$test->test_set_key_is_unset_properly();

// TEST #7 : test_destory_destorys_storage_correctly
echo "TEST #7 : test_destory_destorys_storage_correctly: ";
$test->test_destory_destorys_storage_correctly();




// Test it at last
$test2 = new SessionStorageTest('test2_storage');
// TEST #X : test_get_works_even_if_get_is_the_only_first_call_that_ever_made
echo "TEST #X : test_get_works_even_if_get_is_the_only_first_call_that_ever_made: ";
$test->test_get_works_even_if_get_is_the_only_first_call_that_ever_made();
