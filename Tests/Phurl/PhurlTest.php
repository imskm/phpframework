<?php

require __DIR__ . '/../init_test_mode.php';

use \Tests\BaseTest;
use \Components\Phurl\Phurl;

/**
 * PhurlTest
 */
class PhurlTest extends BaseTest
{
	public function test_phurl_prepare_returns_phurl_instance()
	{
		$phurl = new Phurl(true);

		if (!($phurl instanceof Phurl)) {
			$this->failed();
			return false;
		}

		$this->passed();

		return true;
	}

	public function test_phurl_get_request_with_no_params()
	{
		$phurl = new Phurl(true);
		$get_result = $phurl->get($url = 'https://jsonplaceholder.typicode.com/posts/1');

		if ($get_result === false) {
			$this->failed();
			return false;
		}
		$this->passed();

		print_r($phurl->getResponse());

		return true;
	}

	public function test_phurl_get_request_with_params()
	{
		$phurl = new Phurl(true);
		$get_result = $phurl->withParams([
			'userId'	=> 1,
		])->get($url = 'https://jsonplaceholder.typicode.com/posts');

		if ($get_result === false) {
			$this->failed();
			return false;
		}
		$this->passed();

		print_r($phurl->getResponse());

		return true;
	}

	public function test_phurl_get_returns_false_on_invalid_param()
	{
		$phurl = new Phurl(true);
		$get_result = $phurl->withParams([
			'userId'	=> 10000,
		])->get($url = 'https://jsonplaceholder.typicode.com/posts');

		if ($get_result === false) {
			$this->failed();
			return false;
		}
		$this->passed();

		print_r($phurl->getResponse());

		return true;
	}
}

$test = new PhurlTest;

// TEST #1: test_phurl_prepare_returns_phurl_instance
echo "TEST #1: test_phurl_prepare_returns_phurl_instance: ";
$test->test_phurl_prepare_returns_phurl_instance();

// TEST #2: test_phurl_get_request_with_no_params
echo "TEST #2: test_phurl_get_request_with_no_params: ";
$test->test_phurl_get_request_with_no_params();

// TEST #3: test_phurl_get_request_with_params
echo "TEST #3: test_phurl_get_request_with_params: ";
$test->test_phurl_get_request_with_params();

// TEST #4: test_phurl_get_returns_false_on_invalid_param
echo "TEST #4: test_phurl_get_returns_false_on_invalid_param: ";
$test->test_phurl_get_returns_false_on_invalid_param();