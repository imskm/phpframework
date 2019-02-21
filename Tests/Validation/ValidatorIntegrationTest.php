<?php

require __DIR__ . '/../init_test_mode.php';
require __DIR__ . '/../BaseTest.php';

use \Core\Validation\Validator;

/**
 * ValidatorTest Class
 */
class ValidatorIntegrationTest extends BaseTest
{
	protected $validator;
	protected $method;
	
	public function __construct(Validator $validator, $method = 'GET')
	{
		$this->validator = $validator;
		$this->method = $method;
	}

	public function test_order_of_processing_rules()
	{
		// Create form fields
		$_GET['first_name'] = 'n4'; // gmail@gmail
		$_GET['phone'] 		= ''; // gmail@gmail
		$_GET['email'] 		= ''; // gmail@gmail

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'first_name' 	=> 'required|min:4|alpha_space',
			'phone' 		=> 'required|phone',
			'email' 		=> 'required|email',
		]);

		return $validator->errors;
	}


}

// Tests
$validator_test = new ValidatorIntegrationTest(new Validator);

// TEST #1: test_validation_class_is_working_or_not
echo "TEST #1: test_validation_class_is_working_or_not:  ";
$result = $validator_test->test_validation_class_is_working_or_not();
print_r($result);



echo PHP_EOL;