<?php
namespace Core;

/**
 * Validator Class
 *  Class contains function for validating user input
 */
class Validator
{
	/**
	 * @var array $errors   Contains errors message if any
	 */
	public $errors = [];

	/**
	 * @var string $validateField   stores field (one) to be validated
	 */
	protected $validateField;

	/**
	 * @var string $validateValue   stores value (one) of the field to be validated
	 */
	protected $validateValue;

	/**
	 * @var array $validatedFields   stores validated field as key and value as value
	 */
	public $validatedFields = [];

	/**
	 * @var array $rules   stores rules that will be performed on input field
	 */
	protected $rules = [];

	/**
	 * @var array $allowedRules   stores rules that will be allowed
	 */
	protected $allowedRules = ["required", "optional", "alpha", "alpha_space", "alpha_num", "alpha_dash", "numeric", "digits", "integers", "min", "max", "email", "phone", "size", "confirmed", "in", "exist", "unique"];

	/**
	 * @var array $exceptionalRules  stores rules that are treaded exceptionally
	 */
	protected $exceptionalRules = ["optional"];

	/**
	 * @var string $redirectUrl   stores URL for redirection if validation fails
	 */
	protected $redirectUrl;

	/**
	 * @var string $reqMethod   stores request method (GET | POST)
	 */
	protected $reqMethod;

	public function __construct()
	{
		$this->redirectUrl = $_SERVER["HTTP_REFERER"];
	}

	public function validate($request = "GET", $rules)
	{
		// echo "OK<br>";
		// echo "$method<br>";
		// echo "Rules before : <br>";
		// var_dump($rules);

		// 1. Setting request method
		$this->setRequestMethod($request);

		// 2. Itterating over fields to be validated

		// 2.1. Cheking if $rules is array
		if(!is_array($rules)) {
			throw new \Exception("Rules are expected to be an array. String given");
		}

		// 2.2 Now itterating over fields
		foreach ($rules as $field => $rule_string) {

			// Sets the $validateField/Value property with current value of the field.
			$this->setCurrentValidation($field);

			//  Extract the rules and store it to $rules property
			$this->checkRules($rule_string);

			// Itterate over each rule and call the method
			foreach ($this->rules as $rule => $params) {

				if($params) { // rule has argument

					$method 	= $rule;
					$argument 	= $this->rules[$rule];

					// calling the function with argument
					if(!$this->$method($argument)) {

						// TODO
					}

				} else {	// rule has no argument

					$method 	= $rule;

					// Exceptional case checking for exceptional rules
					if(in_array($rule, $this->exceptionalRules)) {
						if($this->$method()) continue; else break;
					}

					// calling method without argument
					if(!$this->$method()) {

						// TODO
					}

				}
			}

			// Storing the validated value to the validatedFields property
			$this->validatedFields[$this->validateField] = trim($this->validateValue);

			// Resetting the $rules property
			$this->rules = array();
		}

		return true;
	}

	public function hasError($key)
	{
		return isset($this->errors[$key]) ? true : false;
	}

	protected function required()
	{
		$output = str_replace(" ", "", $this->validateValue);
		// echo "<br>Required function called for validating $this->validateField => $this->validateValue";
		if(empty($output)) {
			$this->errors[$this->validateField] = "$this->validateField is required.";
			return false;
		}
		return true;
	}

	protected function optional()
	{

		if(trim($this->validateValue) == '') {
			return false;
		}

		return true;
	}

	protected function min($number)
	{
		if(!is_numeric($number)) {
			throw new \Exception("Argument of min rule must be integer. String given.");
		}

		// if user input field is string then check the length of the string
		if(is_string($this->validateValue)) {
			if(strlen($this->validateValue) >= $number ) {
				return true;
			}
		}
		// Storing error message
		$this->errors[$this->validateField] = "$this->validateField length must be atleast $number characters.";
		// echo "<br>min function called for validating $this->validateField with argument value $number";
		return false;
	}

	protected function max($number)
	{
		if(!is_numeric($number)) {
			throw new \Exception("Argument of min rule must be integer. String given.");
		}

		// if user input field is string then check the length of the string
		if(is_string($this->validateValue)) {
			if(strlen($this->validateValue) <= $number) {
				return true;
			}
		}
		// Storing error message
		$this->errors[$this->validateField] = "$this->validateField length must not exceed $number characters.";
		return false;
	}

	protected function alpha()
	{
		$pattern = "/^[a-zA-Z]+$/";
		if(preg_match($pattern, $this->validateValue)) {
			return true;
		}
		$this->errors[$this->validateField] = "$this->validateField should contain alphabets only.";
		return false;
	}

	protected function alpha_space()
	{
		$pattern = "/^[a-zA-Z ]+$/";
		if(preg_match($pattern, $this->validateValue)) {
			return true;
		}
		$this->errors[$this->validateField] = "$this->validateField should contain alphabets only.";
		return false;
	}

	protected function alpha_num()
	{
		$pattern = "/^[a-zA-Z0-9]+$/";
		if(preg_match($pattern, $this->validateValue)) {
			return true;
		}
		$this->errors[$this->validateField] = "$this->validateField should contain alphabets and numbers only.";
		return false;
	}

	protected function numeric()
	{
		if(!is_numeric($this->validateValue)) {
			$this->errors[$this->validateField] = "$this->validateField should be numeric.";
			return false;
		}
		return true;
	}

	protected function digits()
	{
		$pattern = "/^[0-9]+$/";
		if(preg_match($pattern, $this->validateValue)) {
			return true;
		}
		$this->errors[$this->validateField] = "$this->validateField should be number.";
		return false;
	}

	protected function phone()
	{
		$pattern = "/^[0-9]+$/";
		if(preg_match($pattern, $this->validateValue && $this->size(10))) {
			return true;
		}
		$this->errors[$this->validateField] = "Phone number is not valid.";
		return false;
	}

	protected function email()
	{
		if (filter_var($this->validateValue, FILTER_VALIDATE_EMAIL)) {
    		return true;
		}
		$this->errors[$this->validateField] = "Email address is not valid.";
		return false;
	}

	protected function size($size)
	{
		if(strlen($this->validateValue) == $size) {
			return true;
		}
		$this->errors[$this->validateField] = "Size of $this->validateField should be equal to $size.";
		return false;
	}

	protected function confirmed($confirm)
	{
		$confirmWith = $this->getInputValue($confirm);
		if($this->validateValue == $confirmWith) {
			return true;
		}

		$this->errors[$this->validateField] = "$this->validateField does not match with $confirm.";
		return false;
	}

	protected function in($data)
	{
		if(empty($data)) {
			throw new \Exception("List is of value is empty in the IN rule.");
		}

		$lists = explode(",", $data);
		if(in_array($this->validateValue, $lists)) {
			return true;
		}

		$this->errors[$this->validateField] = "$this->validateField does not has given matching value.";
		return false;
	}

	protected function exist($data)
	{
		// $data[0] => Table, $data[1] => column
		$parts = explode(",", $data);
		$db = Connection::getDB();
		$sql = sprintf("SELECT 1 FROM %s WHERE %s = :%s", $parts[0], $parts[1], $parts[1]);
		$st = $db->prepare($sql);
		$st->bindValue(":$parts[1]", $this->validateValue);

		if($st->execute()) {
			if($st->fetch()) {
				return true;
			}
		}

		$this->errors[$this->validateField] = "$this->validateValue does not exist.";
		return false;
	}

	protected function unique($data)
	{
		// $data[0] => Table, $data[1] => column
		$parts = explode(",", $data);
		$db = Connection::getDB();
		$sql = sprintf("SELECT 1 FROM %s WHERE %s = :%s", $parts[0], $parts[1], $parts[1]);
		$st = $db->prepare($sql);
		$st->bindValue(":$parts[1]", $this->validateValue);

		if($st->execute()) {
			if(!$st->fetch()) {
				return true;
			}
		}

		$this->errors[$this->validateField] = "$this->validateField $this->validateValue is not unique.";
		return false;
	}

	protected function checkRules($rules)
	{
		$rules = str_replace(" ", "", $rules);
		$rules_parts = explode("|", $rules);

		// $pattern = "/^([a-z_]+):([a-z0-9,]+)$/";
		$pattern = "/^([a-z_]+):(.+)$/";

		foreach ($rules_parts as $rule_part) {

			if(preg_match($pattern, $rule_part, $matches)) {
				$rule = $matches[1];
				// echo "Rule $rule <br>";

				if( isset($matches[2]) ) {
					$this->rules[ $matches[1] ] = $matches[2];
				} else {
					$this->rules[ $matches[1] ] = null;
				}

			} else {
				$rule = $rule_part;
				// echo "Rule $rule <br>";
				$this->rules[ $rule_part ] = null;
			}

			// Checkin if rule exist or not
			if(!in_array($rule, $this->allowedRules)) {
				throw new \Exception("Rule " . $rule . " Not found.");
			}

		}

		// echo "<br>==============<br>";
		// var_dump($this->rules);
		// exit;
		return true;
	}

	protected function getInputValue($field = "")
	{
		switch ($this->reqMethod) {
			case 'GET':
				if(!empty($field)) return $_GET[$field];
				return isset($_GET[$this->validateField])? $_GET[$this->validateField] : "";
				break;
			case 'POST':
				if(!empty($field)) return $_POST[$field];
				return isset($_POST[$this->validateField])? $_POST[$this->validateField] : "";
				break;
			default :
				throw new \Exception("$this->reqMethod not found.");
		}
	}

	protected function setRequestMethod($request)
	{
		$this->reqMethod = strtoupper($request);
	}

	protected function setCurrentValidation($field)
	{

		switch ($this->reqMethod) {

			case "GET":
				if(!isset($_GET[$field])) {
					throw new \Exception("$field input field not found.");
				}

				$this->validateField = $field;
				$this->validateValue = $_GET[$field];

				break;

			case "POST":
				if(!isset($_POST[$field])) {
					throw new \Exception("$field input field not found.");
				}

				$this->validateField = $field;
				$this->validateValue = $_POST[$field];
				break;

			default :
				throw new \Exception("$this->reqMethod not found.");
		}

		return true;
	}
}
