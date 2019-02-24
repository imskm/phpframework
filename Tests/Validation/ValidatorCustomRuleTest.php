<?php

require __DIR__ . '/../init_test_mode.php';

use \Tests\BaseTestTrait;
use \Core\Validation\Validator;

/**
 * ValidatorCustomRuleTest Class
 */
class ValidatorCustomRuleTest extends Validator
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

	protected function age_rule($field, $data)
	{
		// validation logic
		$d = DateTime::createFromFormat("Y-m-d", $data);
		if (!$d) {
			$this->setError($field, __FUNCTION__, "Date is invalid or date format is incorrect, use Y-m-d format");
			return false;
		}

		$interval = $d->diff(new DateTime());
		if ($interval->format("%y") < 18) {
			$this->setError($field, __FUNCTION__, "Age must be atleast 18");
			return false;
		}

		return true;
	}

	public function test_user_defined_custom_rule_with_custom_messages()
	{
		$method = "POST";
		$_POST = array();
		// Create form fields
		$_POST['first_name'] 		= '';
		$_POST['last_name'] 		= 'muktar';
		$_POST['email'] 			= 'sadaf@gmail.com';
		$_POST['password'] 			= '12345';
		$_POST['dob']				= '2019-02-24';

		$this->validate($method, $rules = [
			'first_name' 	=> 'required|alpha_space|min:3|max:32',
			'last_name' 	=> 'optional|alpha_space|min:3|max:32', // error message must fallback to default
			'email' 		=> 'required|email|unique:users,email', // error message must fallback to default
			'password' 		=> 'required|min:6|max:16',
			'dob' 			=> 'required|age', 						// age is custom rule defined by user
		]);

		$errors = $this->errors;
		echo count($errors) === 4? $this->passed() : $this->failed();

		return $errors;
	}


}

// Tests
$validator_test = new ValidatorCustomRuleTest;

// TEST #1: test_user_defined_custom_rule_with_custom_messages
echo "TEST #1: test_user_defined_custom_rule_with_custom_messages:  ";
$result = $validator_test->test_user_defined_custom_rule_with_custom_messages();
print_r($result);
$validator_test = null;


echo PHP_EOL;