<?php

require __DIR__ . '/../init_test_mode.php';

use \Tests\BaseTest;
use \Core\Validation\Validator;

/**
 * ValidatorTest Class
 */
class ValidatorIntegrationTest extends BaseTest
{
	protected $validator;
	protected $method;
	
	public function __construct(Validator $validator, $method = 'POST')
	{
		$this->validator = $validator;
		$this->method = $method;
	}

	public function test_a_real_life_registration_validation()
	{
		$_POST = array();
		// Create form fields
		$_POST['first_name'] 		= 'shek';
		$_POST['last_name'] 		= 'muktar';
		$_POST['email'] 			= 'skm@gmail.com';
		$_POST['password'] 			= '12345678';
		$_POST['confirm_password'] 	= '12345678';
		$_POST['phone'] 			= '9331926606';
		$_POST['gender'] 			= '1';
		$_POST['address'] 			= '';
		$_POST['country'] 			= '2';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'first_name' 	=> 'required|alpha_space|min:3|max:32',
			'last_name' 	=> 'optional|alpha_space|min:3|max:32',
			'email' 		=> 'required|email|unique:users,email',
			'password' 		=> 'required|min:6|max:16',
			'confirm_password' 		=> 'required|confirmed:password',
			'phone' 		=> 'required|phone',
			'gender' 		=> 'required|in:1,2',
			'address' 		=> 'optional|max:32',
			'country' 		=> 'required|exist:users,id',  // just for testing exist rule
		]);

		$errors = $validator->errors;
		echo count($errors) === 0? $this->passed() : $this->failed();

		return $errors;
	}

	public function test_a_real_life_registration_validation_with_failed_rules()
	{
		$_POST = array();
		// Create form fields
		$_POST['first_name'] 		= 'shek';
		$_POST['last_name'] 		= 'muktar';
		$_POST['email'] 			= 'sadaf@gmail.com';  // not unique
		$_POST['password'] 			= '12345678';
		$_POST['confirm_password'] 	= '1234567';  // password doesn't match
		$_POST['phone'] 			= '+919331926606'; // invalid
		// $_POST['gender'] 			= '';  // Gender is not given but validated
		$_POST['address'] 			= '';
		$_POST['country'] 			= '2';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'first_name' 	=> 'required|alpha_space|min:3|max:32',
			'last_name' 	=> 'optional|alpha_space|min:3|max:32',
			'email' 		=> 'required|email|unique:users,email',
			'password' 		=> 'required|min:6|max:16',
			'confirm_password' 		=> 'required|confirmed:password',
			'phone' 		=> 'required|phone',
			'gender' 		=> 'required|in:1,2',
			'address' 		=> 'optional|max:32',
			'country' 		=> 'required|exist:users,id',  // just for testing exist rule
		]);

		$errors = $validator->errors;
		echo count($errors) === 4? $this->passed() : $this->failed();

		return $errors;
	}


}

// Tests
$validator_test = new ValidatorIntegrationTest(new Validator);

// TEST #1: test_a_real_life_registration_validation
echo "TEST #1: test_a_real_life_registration_validation:  ";
$result = $validator_test->test_a_real_life_registration_validation();
print_r($result);

// TEST #2: test_a_real_life_registration_validation_with_failed_rules
echo "TEST #2: test_a_real_life_registration_validation_with_failed_rules:  ";
$result = $validator_test->test_a_real_life_registration_validation_with_failed_rules();
print_r($result);



echo PHP_EOL;