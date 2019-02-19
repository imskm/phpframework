# Validator class API

## User defined custom rules
User must define a method in his extended Validator class in this way:
Let say user want his custom rule for password validation, there for the method name msut be suffixed with "Rule" like this:
```php
protected function passwordRule($field, $data)
{
	//

	if ($validation_fails) {
		$this->setError($field, $data, $message = "this is the error message");
		return false;
	}

	return true;
}
```
and user must set error if validation fails and return false, returns true on validation passes.