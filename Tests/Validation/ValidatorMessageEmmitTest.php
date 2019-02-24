<?php

require __DIR__ . '/../init_test_mode.php';

use \Tests\BaseTestTrait;
use \Core\Validation\Validator;

/**
 * ValidatorMessageEmmitTest Class
 */
class ValidatorMessageEmmitTest extends Validator
{
	protected $message_template = '<p class="form-error">%s</p>';

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

	public function test_error_message_template_emmit()
	{
		$this->test_validate();
		$errors = self::validationErrors();

		if (count($errors) !== 4) {
			$this->failed();
			return;
		}

		if (count($errors) !== 4) {
			$this->failed();
			return;
		}

		if ($errors->emmitOrDefault('first_name') != sprintf($this->message_template, $this->custom_messages['first_name']['required'])) {
			$this->failed();
			return;
		}

		if ($errors->emmitOrDefault('last_name') != "") {
			$this->failed();
			return;
		}

		if ($errors->emmitOrDefault('email') != '<p class="form-error">email sadaf@gmail.com is not unique.</p>') {
			$this->failed();
			return;
		}

		if ($errors->emmitOrDefault('password') != sprintf($this->message_template, $this->custom_messages['password']['min'])) {
			$this->failed();
			return;
		}

		if ($errors->emmitOrDefault('dob') != '<p class="form-error">Age must be atleast 18</p>') {
			$this->failed();
			return;
		}

		return $errors;
	}

	protected function test_validate()
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
			'last_name' 	=> 'optional|alpha_space|min:3|max:32',
			'email' 		=> 'required|email|unique:users,email',
			'password' 		=> 'required|min:6|max:16',
			'dob' 			=> 'required|age',
		]);

		$errors = $this->errors;
		echo count($errors) === 4? $this->passed() : $this->failed();

		return $errors;
	}


}

// Tests
$validator_test = new ValidatorMessageEmmitTest;

// TEST #1: test_error_message_template_emmit
echo "TEST #1: test_error_message_template_emmit:  ";
$result = $validator_test->test_error_message_template_emmit();
print_r($result);
$validator_test = null;


echo PHP_EOL;