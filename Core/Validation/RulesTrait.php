<?php

namespace Core\Validation;

trait RulesTrait
{
	/**
	 * @var array $allowedRules   stores rules that will be allowed
	 */
	protected $allowedRules = [
		"alpha"				=> true,
		"alpha_dash"		=> true,
		"alpha_num"			=> true,
		"alpha_space"		=> true,
		"confirmed"			=> true,
		"digits"			=> true,
		"email"				=> true,
		"exist"				=> true,
		"in"				=> true,
		"integer"			=> true,
		"max"				=> true,
		"min"				=> true,
		"numeric"			=> true,
		"optional"			=> true,
		"phone"				=> true,
		"required"			=> true,
		"size"				=> true,
		"unique"			=> true,
	];

	/**
	 * @var array $exceptionalRules  stores rules that are treaded exceptionally
	 */
	protected $exceptionalRules = [
		"optional"			=> true,
	];



	protected function isRuleAllowed($rule)
	{
		return array_key_exists($rule, $this->allowedRules);
	}

	protected function isRuleExceptional($rule)
	{
		return array_key_exists($rule, $this->exceptionalRules);
	}


	protected function alpha($field, $data)
	{
		$pattern = "/^[a-zA-Z]+$/";

		if(!preg_match($pattern, $data)) {
			$this->setError($field, __FUNCTION__, "$field should contain alphabets only.");
			return false;
		}

		return true;
	}

	protected function alpha_dash($field, $data)
	{
		$pattern = "/^[a-zA-Z-]+$/";
		
		if(!preg_match($pattern, $data)) {
			$this->setError($field, __FUNCTION__, "$field should contain alphabets and dashes only.");
			return false;
		}

		return true;
	}

	protected function alpha_num($field, $data)
	{
		$pattern = "/^[a-zA-Z0-9]+$/";
		
		if(!preg_match($pattern, $data)) {
			$this->setError($field, __FUNCTION__, "$field should contain alphabets and numbers only.");
			return false;
		}

		return true;
	}

	protected function alpha_space($field, $data)
	{
		$pattern = "/^[a-zA-Z ]+$/";
		
		if(!preg_match($pattern, $data)) {
			$this->setError($field, __FUNCTION__, "$field should contain alphabets and spaces only.");
			return false;
		}

		return true;
	}

	protected function confirmed($field, $data, $confirm)
	{
		$confirmWith = $this->getInputValue($confirm);

		if($data !== $confirmWith) {
			$this->setError($field, __FUNCTION__, "$field does not match with $confirm.");
			return false;
		}

		return true;
	}

	protected function digits($field, $data)
	{
		$pattern = "/^[0-9]+$/";
		
		if(!preg_match($pattern, $data)) {
			$this->setError($field, __FUNCTION__, "$field should be number.");
			return false;
		}

		return true;
	}

	protected function email($field, $data)
	{
		if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
			$this->setError($field, __FUNCTION__, "Email address is not valid.");
    		return false;
		}
		
		return true;
	}

	protected function exist($field, $data, $args)
	{
		// $args[0] => Table, $args[1] => column
		$parts = explode(",", $args);
		if (count($parts) !== 2) {
			throw new \Exception("Invalid argument to ".__FUNCTION__." rule.");
		}

		$sql = sprintf("SELECT 1 FROM %s WHERE %s = :%s", $parts[0], $parts[1], $parts[1]);
		if(!$this->dbQuickCheck($sql, ["$parts[1]" => $data])) {
			$this->setError($field, __FUNCTION__, "$data does not exist.");
			return false;
		}

		return true;
	}

	protected function in($field, $data, $args)
	{
		$lists = explode(",", $args);

		if(count($lists) === 0) {
			throw new \Exception("List of value is empty in the \"".__FUNCTION__."\" rule.");
		}

		if(!in_array($data, $lists)) {
			$this->setError($field, __FUNCTION__, "$field does not has given matching value.");
			return false;
		}

		return true;
	}

	protected function integer($field, $data)
	{
		$options = [
			'flags' => FILTER_FLAG_ALLOW_OCTAL, FILTER_FLAG_ALLOW_HEX
		];

		if (filter_var($data, FILTER_VALIDATE_INT, $options) === false) {
			$this->setError($field, __FUNCTION__, "$field is not integer.");
			return false;
		}

		return true;
	}

	protected function max($field, $data, $args)
	{
		// If $data is a number then compare it like number
		if(is_numeric($data) && (float) $data > (float) $args) {
			$this->setError($field, __FUNCTION__, "$field must not exceeds $args.");
			return false;
		}

		// If $data is a string then check the string lenth exceeds limit
		if(!is_numeric($data) && is_string($data) && isset($data[(int) $args])) {
			$this->setError($field, __FUNCTION__, "$field length must not exceed $args characters.");
			return false;
		}

		return true;
	}

	protected function min($field, $data, $args)
	{
		// If $data is a number then compare it like number
		if(is_numeric($data) && (float) $data < (float) $args) {
			$this->setError($field, __FUNCTION__, "$field must be atleast $args.");
			return false;
		}

		// If $data is a string then check the string lenth
		if(!is_numeric($data) && is_string($data) && !isset($data[((int) $args) - 1])) {
			$this->setError($field, __FUNCTION__, "$field length must be atleast $args characters.");
			return false;
		}

		return true;
	}

	protected function numeric($field, $data)
	{
		if(!is_numeric($data)) {
			$this->setError($field, __FUNCTION__, "$field should be number.");
			return false;
		}

		return true;
	}

	protected function optional($field, $data)
	{
		// If $data is empty then don't set error because it's optional
		if(trim($data) === '') {
			return false;
		}

		return true;
	}

	protected function phone($field, $data)
	{
		$pattern = "/^[0-9]{10}$/";

		if(!preg_match($pattern, $data)) {
			$this->setError($field, __FUNCTION__, "Phone number is not valid.");
			return false;
		}

		return true;
	}

	protected function required($field, $data)
	{
		$data = str_replace(" ", "", $data);

		if(!$data) {
			$this->setError($field, __FUNCTION__, "$field is required");
			return false;
		}

		return true;
	}

	protected function size($field, $data, $args)
	{
		if (is_numeric($data) && (float) $data !== (float) $args) {
			$this->setError($field, __FUNCTION__, "Size of $field should be equal to $args.");
			return false;
		}

		if (!is_numeric($data) && is_string($data) && strlen($data) !== (int)$args) {
			$this->setError($field, __FUNCTION__, "Size of $field should be equal to $args");
			return false;
		}

		return true;
	}

	protected function unique($field, $data, $args)
	{
		// $args[0] => Table, $args[1] => column
		$parts = explode(",", $args);
		if (count($parts) !== 2) {
			throw new \Exception("Invalid argument to ".__FUNCTION__." rule.");
		}

		$sql = sprintf("SELECT 1 FROM %s WHERE %s = :%s", $parts[0], $parts[1], $parts[1]);
		if($this->dbQuickCheck($sql, ["$parts[1]" => $data])) {
			$this->setError($field, __FUNCTION__, "$field $data is not unique.");
			return false;
		}

		return true;
	}


	/**
	 * Helper method for quick check existence of data in db
	 */
	protected function dbQuickCheck($sql, array $params = [])
	{
		$db = \Core\Database\Connector::getConnection();
		$st = $db->prepare($sql);

		foreach ($params as $param => $value) {
			if (is_null($value)) {
				$st->bindValue(":$param", $value, \PDO::PARAM_NULL);
			} else if (is_bool($value)) {
				$st->bindValue(":$param", $value, \PDO::PARAM_BOOL);
			} else if (is_numeric($value) && is_int($value + 0)) {
				$st->bindValue(":$param", $value, \PDO::PARAM_INT);
			} else {
				$st->bindValue(":$param", $value, \PDO::PARAM_STR);
			}
		}

		return $st->execute() && (bool)$st->fetch();
	}
}