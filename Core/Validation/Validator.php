<?php
namespace Core\Validation;

use \Core\Database\Connector;
use \Core\Validation\RulesTrait;

/**
 * Validator Class
 *  Class contains function for validating user input
 */
class Validator
{
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
	 * @var string $redirectUrl   stores URL for redirection if validation fails
	 */
	protected $redirectUrl;

	/**
	 * @var string $reqMethod   stores request method (GET | POST)
	 */
	protected $reqMethod;

	/**
	 * Bail out. If set to true then validation will stop as soon as it finds
	 * first field that doesn't passes current rule
	 * If set to false then it will continue to test all rules even if some
	 * rules doesn't pass.
	 * This property can be overridden by user in his child class
	 */
	protected $bail = false;

	/**
	 * @var array 				array of error messages of failed rules
	 * structure:
	 * 	[
	 *		'field_name1' => [
	 *				'rule1' => 'error message of rule1',
	 *				'rule2' => 'error message of rule2',
	 *			],
	 *		'field_name2' => [
	 *				'rule1' => 'error message of rule1',
	 *				'rule2' => 'error message of rule2',
	 *			],
	 * 	]
	 */
	public $errors;

	/**
	 * Custom error property name defined by user (it must be custom_messages)
	 */
	protected $user_messages_property_name = 'custom_messages';

	/**
	 * HTML Message template defined by user
	 */
	protected $user_message_template_name = 'message_template';

	/**
	 * Reflection object
	 */
	protected $reflection;

	public function __construct()
	{
		// PHP's Reflection mechanism, used to query info about $this object
		// Needed to check for user defined property existence check, such as
		// custom_messages and message_template
		$this->reflection = new \ReflectionObject($this);
	}

	use RulesTrait;

	public function validate($request, array $rules)
	{
		// 1. Setting request method
		$this->setRequestMethod($request);

		// 2. Itterating over fields to be validated
		// 2.1. Cheking if $rules is empty
		if(!$rules) {
			throw new \Exception("Empty rules is illegal. Please provide validation rules.");
		}

		// 2.2 Now itterating over fields
		foreach ($rules as $field => $rule_string) {

			// Sets the $validateField/Value property with current value of the field.
			$this->setCurrentValidation($field);

			//  Extract the rules and store it to $rules property
			$this->checkRules($rule_string);

			// Itterate over each rule and call the method
			foreach ($this->rules as $rule => $params) {

				// If rule has parameter then call the rule with parameter
				if($params) {

					$method 	= $rule;
					$argument 	= $this->rules[$rule];

					// calling the function with argument
					if(!$this->$method($this->validateField, $this->validateValue, $argument)) {

						// TODO
						if ($this->bail) break;
					}

				// else rule has no argument
				} else {

					$method 	= $rule;

					// Exceptional case checking for exceptional rules
					if($this->isRuleExceptional($rule)) {
						if(!$this->$method($this->validateField, $this->validateValue))
							if ($this->bail) break;
					}

					// calling method without argument
					if(!$this->$method($this->validateField, $this->validateValue)) {
						// Break out of loop and don't check the next rule
						// since first rule is not passed then we don't check
						// for next rule on the current input field

						// TODO: we can do something from users pov then break
						if ($this->bail) break;
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

				// If rule has argument then store rule with argument
				// $matches[2] is argument of rule
				if( isset($matches[2]) ) {
					$this->rules[ $matches[1] ] = $matches[2];

				} else {	// else just store rule with null argument
					$this->rules[ $matches[1] ] = null;
				}

			} else {
				$rule = $rule_part;
				// echo "Rule $rule <br>";
				$this->rules[ $rule_part ] = null;
			}

			// Checkin if rule exist or not
			// TODO: suffix "rule" in $rule then check the method exist
			//       user must create rule suffixed with "rule" word
			if(!$this->isRuleAllowed($rule)) {
				// check if user's rule exist or not
				$custom_rule = $rule."Rule";
				if (!$this->userMethodExist($custom_rule)) {
					throw new \Exception("Rule " . $rule . " Not found.");
				}
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

			case "PUT":
				if(!isset($_PUT[$field])) {
					throw new \Exception("$field input field not found.");
				}

			default :
				throw new \Exception("$this->reqMethod not found.");
		}

		return true;
	}

	protected function setError($field, $rule, $message)
	{
		// If user has defined custom_messages property then store user's
		// custom message
		if ($this->userPropertyExist($this->user_messages_property_name)) {
			$this->setCustomMessage($field, $rule, $message);
			return;
		}

		// Else store default message generated by Validator class
		$this->setDefaultMessage($field, $rule, $message);

	}

	protected function userMethodExist($method)
	{
		return $this->reflection->hasMethod($method);
	}

	protected function userPropertyExist($property)
	{
		return $this->reflection->hasProperty($property);
	}

	protected function setDefaultMessage($field, $rule, $message)
	{
		$this->errors[$field][$rule] = $message;
	}

	protected function setCustomMessage($field, $rule, $default_message = "")
	{
		// Check if user defined custom message doesn't exist for $rule rule
		// then drop back to default message
		if (!isset($this->{$this->user_messages_property_name}[$field][$rule])) {
			$this->setDefaultMessage($field, $rule, $default_message);
			return;
		}

		$this->errors[$field][$rule] = $this
										->{$this->user_messages_property_name}
										[$field][$rule];
	}
}
