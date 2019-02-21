<?php

require __DIR__ . '/../init_test_mode.php';

use \Core\Validation\Validator;

/**
 * ValidatorTest Class
 */
class ValidatorRuleTest
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

	public function test_validation_class_is_working_or_not()
	{
		// Create form fields
		$_GET['first_name'] = ''; // gmail@gmail
		$_GET['phone'] 		= ''; // gmail@gmail
		$_GET['email'] 		= ''; // gmail@gmail

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'first_name' 	=> 'required|alpha_space',
			'phone' 		=> 'required|phone',
			'email' 		=> 'required|email',
		]);

		echo count($validator->errors) === 3? $this->passed() : $this->failed();
		return $validator->errors;
	}

	public function test_alpha_validation_rule()
	{
		// Create form fields
		$_GET['first_name'] = 'muktar'; // rule should pass
		$_GET['last_name'] = 'shek muktar'; // rule should fail

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'first_name' 	=> 'required|alpha',
			'last_name' 	=> 'required|alpha',
		]);

		$errors = $validator->errors();
		echo count($errors) === 1? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_alpha_dash_validation_rule()
	{
		// Create form fields
		$_GET['prod_name_pass'] = 'silver-product';
		$_GET['prod_name_fail'] = 'silver-01';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'prod_name_pass' 	=> 'required|alpha_dash',
			'prod_name_fail' 	=> 'required|alpha_dash',
		]);

		$errors = $validator->errors();
		echo count($errors) === 1? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_alpha_num_validation_rule()
	{
		// Create form fields
		$_GET['prod_name_pass'] = 'silverproduct01';
		$_GET['prod_name_fail'] = 'silver-01';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'prod_name_pass' 	=> 'required|alpha_num',
			'prod_name_fail' 	=> 'required|alpha_num',
		]);

		$errors = $validator->errors();
		echo count($errors) === 1? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_alpha_space_validation_rule()
	{
		// Create form fields
		$_GET['prod_name_pass'] = 'silver product';
		$_GET['prod_name_fail'] = 'silver-';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'prod_name_pass' 	=> 'required|alpha_space',
			'prod_name_fail' 	=> 'required|alpha_space',
		]);

		$errors = $validator->errors();
		echo count($errors) === 1? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_confirmed_validation_rule()
	{
		// Create form fields
		$_GET['password'] = '12345678';
		$_GET['confirm_password_pass'] = '12345678';
		$_GET['confirm_password_fail'] = '123456789';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'confirm_password_pass' 	=> 'required|confirmed:password',
			'confirm_password_fail' 	=> 'required|confirmed:password',
		]);

		$errors = $validator->errors();
		echo count($errors) === 1? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_email_validation_rule()
	{
		// Create form fields
		$_GET['email_pass'] = 'skmukhtar@gmail.com';
		$_GET['email_fail'] = 'skmukhtar@gmail';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'email_pass' 	=> 'required|email',
			'email_fail' 	=> 'required|email',
		]);

		$errors = $validator->errors();
		echo count($errors) === 1? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_exist_validation_rule()
	{
		// Create form fields
		$_GET['user_pass'] = '2';
		$_GET['user_fail'] = '1000';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'user_pass' 	=> 'required|exist:users,id',
			'user_fail' 	=> 'required|exist:users,id',
		]);

		$errors = $validator->errors();
		echo count($errors) === 1? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_in_validation_rule()
	{
		// Create form fields
		$_GET['gender_pass'] = '2';
		$_GET['gender_fail'] = '4';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'gender_pass' 	=> 'required|in:1,2,3',
			'gender_fail' 	=> 'required|in:1,2,3',
		]);

		$errors = $validator->errors();
		echo count($errors) === 1? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_max_validation_rule_for_number()
	{
		// Create form fields
		$_GET['amount_pass'] = '2000';
		$_GET['amount_fail'] = '3000.5';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'amount_pass' 	=> 'required|max:3000',
			'amount_fail' 	=> 'required|max:3000',
		]);

		$errors = $validator->errors();
		echo count($errors) === 1? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_max_validation_rule_for_string()
	{
		// Create form fields
		$_GET['product_name_pass'] = 'Silver product';
		$_GET['product_name_fail'] = 'This product name is so long that will fail max';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'product_name_pass' 	=> 'required|max:20',
			'product_name_fail' 	=> 'required|max:20',
		]);

		$errors = $validator->errors();
		echo count($errors) === 1? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_min_validation_rule_for_number()
	{
		// Create form fields
		$_GET['amount_pass'] = '2000.00';
		$_GET['amount_fail'] = '1999.99';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'amount_pass' 	=> 'required|min:2000',
			'amount_fail' 	=> 'required|min:2000',
		]);

		$errors = $validator->errors();
		echo count($errors) === 1? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_min_validation_rule_for_string()
	{
		// Create form fields
		$_GET['product_name_pass'] = 'Silver product';
		$_GET['product_name_fail'] = 'short';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'product_name_pass' 	=> 'required|min:10',
			'product_name_fail' 	=> 'required|min:10',
		]);

		$errors = $validator->errors();
		echo count($errors) === 1? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_optional_validation_rule()
	{
		// Create form fields
		$_GET['address_line_1_pass'] = '';
		$_GET['address_line_2_pass'] = 'B.L. No.-5, N.C. Road, 24pgs (N)';
		$_GET['address_line_1_fail'] = 'this is too long adress and must be failed';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'address_line_1_pass' 	=> 'optional|max:34',
			'address_line_2_pass' 	=> 'optional|max:34',
			'address_line_1_fail' 	=> 'optional|max:34',
		]);

		$errors = $validator->errors();
		echo count($errors) === 1? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_phone_validation_rule()
	{
		// Create form fields
		$_GET['phone_1_pass'] = '9331926606';
		$_GET['phone_2_fail'] = '+919331926606'; // special char +
		$_GET['phone_3_fail'] = '933192660';	// 9 digits in it
		$_GET['phone_4_fail'] = '93319266056';	// 11 digits in it
		$_GET['phone_5_fail'] = '933192660m';	// m alpha char in it

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'phone_1_pass' 	=> 'required|phone',
			'phone_2_fail' 	=> 'required|phone',
			'phone_3_fail' 	=> 'required|phone',
			'phone_4_fail' 	=> 'required|phone',
			'phone_5_fail' 	=> 'required|phone',
		]);

		$errors = $validator->errors();
		echo count($errors) === 4? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_required_validation_rule()
	{
		// Create form fields
		$_GET['first_name_1_pass'] = 'Mukhtar';
		$_GET['first_name_2_fail'] = '';
		$_GET['first_name_3_fail'] = '   '; // blanks are treated as empty string
		$_GET['first_name_4_fail'] = '0';   // TODO lets see, what happens

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'first_name_1_pass' 	=> 'required',
			'first_name_2_fail' 	=> 'required',
			'first_name_3_fail' 	=> 'required',
			'first_name_4_fail' 	=> 'required',
		]);

		$errors = $validator->errors();
		echo count($errors) === 3? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_size_validation_rule()
	{
		// Create form fields
		$_GET['file_size_pass'] = '1000';
		$_GET['file_size_fail'] = '999.5';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'file_size_pass' 	=> 'required|size:1000',
			'file_size_fail' 	=> 'required|size:1000',
		]);

		$errors = $validator->errors();
		echo count($errors) === 1? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_size_validation_rule_for_string()
	{
		// NOTE: size rule will always fail if the data is
		// number. This rule is not appropriate for number
		// Do not use it for checking number size.
		// Create form fields
		$_GET['file_size_pass'] = 'this is test';
		$_GET['file_size_fail'] = 'this test should fail';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'file_size_pass' 	=> 'required|size:12',
			'file_size_fail' 	=> 'required|size:12',
		]);

		$errors = $validator->errors();
		echo count($errors) === 1? $this->passed() : $this->failed();
		return $errors;
	}

	public function test_unique_validation_rule()
	{
		// Create form fields
		$_GET['email_unique_pass'] = 'unique.email@gmail.com';
		$_GET['email_unique_fail'] = 'sadaf@gmail.com';

		$validator = new Validator;
		$validator->validate($this->method, $rules = [
			'email_unique_pass' 	=> 'required|email|unique:users,email',
			'email_unique_fail' 	=> 'required|email|unique:users,email',
		]);

		$errors = $validator->errors();
		echo count($errors) === 1? $this->passed() : $this->failed();
		return $errors;
	}






	public function test_validation_rule_does_not_exist()
	{
		// Create form fields
		$_GET['first_name'] = 'somedata';

		$validator = new Validator;

		try {
			$validator->validate($this->method, $rules = [
				'first_name' 	=> 'required|custom_rule', 	// custom_rule does not exist
			]);
			$this->failed();

			return "";

		} catch (\Exception $e) {
			$this->passed();

			return $e->getMessage();
		}
	}

	public function test_validation_custom_rule_exist()
	{
		// Create form fields
		$_GET['first_name'] = 'somedata';

		// Custom validator class extends Validator class
		$validator = new CustomValidator;

		try {
			$validator->validate($this->method, $rules = [
				'first_name' 	=> 'required|user_rule', 	// custom_rule does not exist
			]);
			$this->failed();

			return "";

		} catch (\Exception $e) {
			$this->passed();

			return $e->getMessage();
		}
	}

	public function test_validation_for_field_that_does_not_exist()
	{
		// Create form fields
		// THERE ARE NO FIELD TO VALIDATE.
		// Therefore validation for non existing field

		$validator = new Validator;

		try {
			$validator->validate($this->method, $rules = [
				'alien_field' 	=> 'required', 	// alien_field does not exist
			]);
			$this->passed();

			return "";

		} catch (\Exception $e) {
			$this->failed();

			return $e->getMessage();
		}
	}

	protected function passed()
	{
		echo "\033[1m\033[32m PASSED\033[0m\n";
	}

	protected function failed()
	{
		echo "\033[1m\033[31m FAILED\033[0m\n";
	}
}

// Tests
$validator_test = new ValidatorRuleTest(new Validator);

// TEST #1: test_validation_class_is_working_or_not
echo "TEST #1: test_validation_class_is_working_or_not:  ";
$result = $validator_test->test_validation_class_is_working_or_not();
print_r($result);

// TEST #2: test_validation_rule_does_not_exist
echo "TEST #2: test_validation_rule_does_not_exist:  ";
$validator_test->test_validation_rule_does_not_exist() . PHP_EOL;

echo "TEST #3: test_order_of_processing_rules\n";
$result = $validator_test->test_order_of_processing_rules();
print_r($result);

echo "TEST #4: test_alpha_validation_rule:  ";
$result = $validator_test->test_alpha_validation_rule();
print_r($result);

echo "TEST #5: test_alpha_dash_validation_rule:  ";
$result = $validator_test->test_alpha_dash_validation_rule();
print_r($result);

echo "TEST #6: test_alpha_num_validation_rule:  ";
$result = $validator_test->test_alpha_num_validation_rule();
print_r($result);

echo "TEST #7: test_alpha_space_validation_rule:  ";
$result = $validator_test->test_alpha_space_validation_rule();
print_r($result);

echo "TEST #8: test_confirmed_validation_rule:  ";
$result = $validator_test->test_confirmed_validation_rule();
print_r($result);

echo "TEST #9: test_email_validation_rule:  ";
$result = $validator_test->test_email_validation_rule();
print_r($result);

echo "TEST #10: test_exist_validation_rule:  ";
$result = $validator_test->test_exist_validation_rule();
print_r($result);

echo "TEST #11: test_in_validation_rule:  ";
$result = $validator_test->test_in_validation_rule();
print_r($result);

echo "TEST #12: test_max_validation_rule_for_number:  ";
$result = $validator_test->test_max_validation_rule_for_number();
print_r($result);

echo "TEST #13: test_max_validation_rule_for_string:  ";
$result = $validator_test->test_max_validation_rule_for_string();
print_r($result);

echo "TEST #14: test_min_validation_rule_for_number:  ";
$result = $validator_test->test_min_validation_rule_for_number();
print_r($result);

echo "TEST #15: test_min_validation_rule_for_string:  ";
$result = $validator_test->test_min_validation_rule_for_string();
print_r($result);

echo "TEST #16: test_optional_validation_rule:  ";
$result = $validator_test->test_optional_validation_rule();
print_r($result);

echo "TEST #17: test_phone_validation_rule:  ";
$result = $validator_test->test_phone_validation_rule();
print_r($result);

echo "TEST #18: test_required_validation_rule:  ";
$result = $validator_test->test_required_validation_rule();
print_r($result);

echo "TEST #19: test_size_validation_rule:  ";
$result = $validator_test->test_size_validation_rule();
print_r($result);

echo "TEST #20: test_size_validation_rule_for_string:  ";
$result = $validator_test->test_size_validation_rule_for_string();
print_r($result);

echo "TEST #21: test_unique_validation_rule:  ";
$result = $validator_test->test_unique_validation_rule();
print_r($result);

echo "TEST #22: test_validation_for_field_that_does_not_exist:  ";
$result = $validator_test->test_validation_for_field_that_does_not_exist();
print_r($result);




echo "SESSION BEFORE..";
print_r($_SESSION);
echo "\n\n\n\n";
var_dump(Validator::validationErrors());

echo "SESSION AFTER..";
print_r($_SESSION);
echo "\n\n\n\n";













echo PHP_EOL;