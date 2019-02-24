<?php

require __DIR__ . '/../init_test_mode.php';

use \Tests\BaseTestTrait;
use \Core\Validation\Validator;

/**
 * ValidatorExtendTest Class
 */
class ValidatorExtendTest extends Validator
{
	protected $custom_messages = [
		'first_name' => [
			'required' => 'You must provide first name',
			'alpha_space' => 'Your name must be of alphabet and space nothing else.',
		],

		'password' => [
			'required' => 'You must provide password because it is required',
			'min' => 'Your password must be atleast 6 characters in length',
			'max' => 'Your password must be at max 16 characters in length',
		],
	];

	use BaseTestTrait;

	public function test_user_defined_custom_messages_shows_when_validation_fails()
	{
		$method = "POST";
		$_POST = array();
		// Create form fields
		$_POST['first_name'] 		= '';
		$_POST['last_name'] 		= 'muktar';
		$_POST['email'] 			= 'sadaf@gmail.com';
		$_POST['password'] 			= '12345';

		$this->validate($method, $rules = [
			'first_name' 	=> 'required|alpha_space|min:3|max:32',
			'last_name' 	=> 'optional|alpha_space|min:3|max:32', // error message must fallback to default
			'email' 		=> 'required|email|unique:users,email', // error message must fallback to default
			'password' 		=> 'required|min:6|max:16',
		]);

		$errors = $this->errors;
		echo count($errors) === 3? $this->passed() : $this->failed();

		return $errors;
	}


}

// Tests
$validator_test = new ValidatorExtendTest;

// TEST #1: test_user_defined_custom_messages_shows_when_validation_fails
echo "TEST #1: test_user_defined_custom_messages_shows_when_validation_fails:  ";
$result = $validator_test->test_user_defined_custom_messages_shows_when_validation_fails();
print_r($result);
$validator_test = null;


echo PHP_EOL;