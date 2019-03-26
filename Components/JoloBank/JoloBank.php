<?php

namespace Components\JoloBank;

use Components\Phurl\Phurl;
use Components\JoloBank\Entities\JoloAgent;
use Components\JoloBank\Entities\JoloBeneficiary;
use Components\JoloBank\Entities\JoloBankTransfer;
use Components\JoloBank\Exceptions\JoloEmptyParamsException;
use Components\JoloBank\Exceptions\JoloUnknownEntityException;

/**
 * JoloBank class
 * Jolo is an API provider for Bank transfer and mobile recharge 
 * This class handles Bank transfer API calls
 */
class JoloBank
{
	protected $phurl;				/* Phurl object for making http request */
	protected $params;				/* Given params for a request 			*/
	protected $jolo_response;		/* Jolo response came back on request 	*/

	use JoloAttributes;
	use JoloApiMethods;

	/**
	 * Instantiate JoloBank instance
	 *
	 * @param $phurl Phurl object  Handles http request
	 * @param $api_key string  API key of jolosoft account
	 * @param $api_mode int  Mode 1 = Production, 0 = Development
	 * @return JoloBank
	 */
	public function __construct(Phurl $phurl, $api_key, $api_mode = 1)
	{
		$this->phurl 	= $phurl;
		$this->api_key 	= $api_key;
		$this->api_mode = ($api_mode === 0 || $api_mode === 1)? $api_mode : 0;
	}

	/**
	 * Creates JoloBank Entity (Agent, Beneficiary, Bank Transfer)
	 *  to make bank transfer, one must create Agent -> Beneficiary under agent
	 *  -> Transfer money to registered beneficiary
	 *
	 * @param $jolo_entity JoloAgent | JoloBeneficiary | JoloBankTransfer
	 * @return boolearn  true if success on creation else false
	 */
	public function create($jolo_entity)
	{
		// Check jolo entity is known entity
		$this->validateJoloEntity($jolo_entity);

		// Prepare correct method name for given $jolo_entity object
		// for e.g. $jolo_entity is JoloAgent then $method_name will be
		// createJoloAgent. For any given entity format is createJolo<Entity>
		$method_name = $this->prepareMethod($jolo_entity);

		// $validate_method_name = validateCreateJolo<Entity>
		$validate_method_name = "validate".ucfirst($method_name);

		// Check Validate method for constructed method name exists
		if (!method_exists($this, $validate_method_name)) {
			throw new \Exception("Method $method_name does not exist");
		}

		// Call the appropriate validate method for checking required params
		// for create api call, if fails then throw exception
		if (!$this->{$validate_method_name}($jolo_entity->allAttributes())) {
			throw new \Exception("Given parameters miss match. check doc for required params.");
		}

		// If method name for create does not exist in this class then
		// throw error. All the create API's are in JoloApiMethods trait
		if (!method_exists($this, $method_name)) {
			throw new \Exception("Method $method_name does not exist");
		}

		// Call the create Api method and pass Jolo entity object
		return $this->{$method_name}($jolo_entity);
	}

	/**
	 * Verify Jolo entities are successfully created or not. Jolo entities
	 *  are JoloAgent, JoloBeneficiary and JoloBankTransfer
	 *
	 * @param $entity string  'agent' | 'beneficiary' | 'transfer'
	 * @param $params array  Parameters for corresponding entity
	 * @return boolean  true if verification is successful else false
	 */
	public function verify($entity, array $params)
	{
		// Construct method name that corresponds to existing validation
		// method for verify api, constructed name will look like:
		// validateVerifyJolo<Entity>
		// validateVerifyJoloAgent, validateVerifyJoloBeneficiary
		$method_name = $this->prepareMethod($entity, $prefix = 'verify');

		// $validate_method_name = validateVerifyJolo<Entity>
		$validate_method_name = "validate".ucfirst($method_name);

		// Check Validate method for constructed method name exists
		if (!method_exists($this, $validate_method_name)) {
			throw new \Exception("Method $method_name does not exist");
		}

		// Call the validate method for checking required params for verify
		// api call is given, if fails then throw exception
		if (!$this->{$validate_method_name}($params)) {
			throw new \Exception("Invalid params given for verify. check doc for required params.");
		}

		// Call the verify Api method with given parameters
		return $this->{$method_name}($params);
	}

	public function detail($entity, array $params)
	{
		// Construct method name that corresponds to existing validation
		// method for detail api, constructed name will look like:
		// validateDetailJolo<Entity>
		// validateDetailJoloAgent, validateDetailJoloBeneficiary
		$method_name = $this->prepareMethod($entity, $prefix = 'detail');

		// $validate_method_name = validateDetailJolo<Entity>
		$validate_method_name = "validate".ucfirst($method_name);

		// Check Validate method for constructed method name exists
		if (!method_exists($this, $validate_method_name)) {
			throw new \Exception("Method $method_name does not exist");
		}

		// Call the validate method for checking required params for verify
		// api call is given, if fails then throw exception
		if (!$this->{$validate_method_name}($params)) {
			throw new \Exception("Invalid params given for verify. check doc for required params.");
		}

		// Call the detail Api method with given parameters
		return $this->{$method_name}($params);
	}

	public function delete($entity, array $params)
	{
		// Construct method name that corresponds to existing validation
		// method for delete api, constructed name will look like:
		// validateDeleteJolo<Entity>
		// validateDeleteJoloAgent, validateDeleteJoloBeneficiary
		$method_name = $this->prepareMethod($entity, $prefix = 'delete');

		// $validate_method_name = validateDeleteJolo<Entity>
		$validate_method_name = "validate".ucfirst($method_name);

		// Check Validate method for constructed method name exists
		if (!method_exists($this, $validate_method_name)) {
			throw new \Exception("Method $method_name does not exist");
		}

		// Call the validate method for checking required params for verify
		// api call is given, if fails then throw exception
		if (!$this->{$validate_method_name}($params)) {
			throw new \Exception("Invalid params given for verify. check doc for required params.");
		}

		// Call the delete Api method with given parameters
		return $this->{$method_name}($params);
	}

	/**
	 * Checks given jolo entity object is instance of any of the known
	 *  entity or not. This is useful if user passes wrong JoloEntity object
	 *
	 * @param $jolo_entity JoloAgent | JoloBeneficiary | JoloBankTransfer
	 * @return mixed  boolean true on OK else throws exception
	 */
	protected function validateJoloEntity($jolo_entity)
	{
		if ($jolo_entity instanceof JoloAgent) {
			return true;
		}

		if ($jolo_entity instanceof JoloBeneficiary) {
			return true;
		}

		if ($jolo_entity instanceof JoloBankTransfer) {
			return true;
		}

		throw new JoloUnknownEntityException("Unknown Jolot Entity Object given");
	}

	/**
	 * Construct method name using given $entity object or string also
	 *  uses $prefix to prefix any string with method name. This is useful
	 *  for dynamically deciding which method to call for Jolo API request
	 *
	 * @param $entity JoloAgent | JoloBeneficiary | JoloBankTransfer | string
	 * @param $prefix string  Prefix for prefixing $prefix in method name
	 * @return string  Constructed method name
	 */
	protected function prepareMethod($entity, $prefix = '')
	{
		$method_name = '';
		if (is_object($entity)) {
			$reflect = new \ReflectionObject($entity);
			$method_name = 'create' . $reflect->getShortName();
		} else if (is_string($entity)) {
			$method_name = $prefix . 'Jolo' . ucfirst($entity);
		} else {
			throw new \Exception("Invalid entity name \"$entity\" given");
		}

		return $method_name;
	}

	/**
	 * Prepares array that exactly what is needed for each individual api
	 *  request.
	 *
	 * @param $params array  Key value pair of parameters for specific api
	 * @return array  All required parameters for a specific api request
	 */
	protected function prepareParamsForRequest($params)
	{
		// Renaming phone key to service as required by jolo api
		if (isset($params['phone'])) {
			$phone = $params['phone'];
			unset($params['phone']);
			$params['service'] = $phone;
		}

		// Adding api_key in params as required by jolo api for every request
		$params['key'] = $this->api_key;

		// Adding api mode key (test or production)
		$params['mode'] = $this->api_mode;

		return $params;
	}

	/**
	 * Validates required parameters for creating new agent
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateCreateJoloAgent(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params['agent']
		);
	}

	/**
	 * Validates required parameters for creating new beneficiary
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateCreateJoloBeneficiary(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params['beneficiary']
		);
	}

	/**
	 * Validates required parameters for creating new bank transfer
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateCreateJoloBankTransfer(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params['transfer']
		);
	}

	/**
	 * Validates required parameters for verifying agent is created or not
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateVerifyJoloAgent(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_verify['agent']
		);
	}

	/**
	 * Validates required parameters for verifying beneficiary is
	 *  created or not
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateVerifyJoloBeneficiary(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_verify['beneficiary']
		);
	}

	/**
	 * Validates required parameters for verifying Bank Transfer is
	 *  created or not
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateVerifyJoloTransfer(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_verify['transfer']
		);
	}

	/**
	 * Validates required parameters for fetching details of agent
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateDetailJoloAgent(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_detail['agent']
		);
	}

	/**
	 * Validates required parameters for fetching details of beneficiary
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateDetailJoloBeneficiary(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_detail['beneficiary']
		);
	}

	/**
	 * Validates required parameters for deleting a beneficiary
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateDeleteJoloBeneficiary(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_delete['beneficiary']
		);
	}

	/**
	 * General validation method for checking all keys of $given params
	 *  match with $required params
	 *
	 * @param $given array  Array of given parameters (by user)
	 * @param $required array  Array of all required parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateRequiredParams(array $given, array $required)
	{
		if (count($required) != count($given)) {
			return false;
			// throw new \Exception("Given parameters miss matched with required parameters");
		}

		foreach ($required as $key => $value) {
			if (!isset($given[$key])) {
				return false;
				// throw new \Exception("Parameter $key missing in given parameters");
			}
		}

		return true;
	}

	/**
	 * Returns last response of Jolo API request
	 *
	 * @return stdClass  Jolo response
	 */
	public function getResponse()
	{
		return $this->jolo_response;
	}

	/**
	 * Converts Jolo API response to stdClass object. On some error Jolo
	 *  API does not return json string, instead it returns plain single
	 *  line string which json_decode can not convert therefore this method
	 *  takes care of that
	 *
	 * @param $response string  Response came back from Jolo API request
	 * @return stdClass object
	 */
	protected function convertResponse($response)
	{
		$res_obj = json_decode($response);

		// If $response is not a json string then $json will be null
		// if null then make proper json object as Jolo API responses
		// in case of error. This is necessary because Jolo does not
		// response Json instead responses string in some error cases.
		if (is_null($res_obj) && is_string($response)) {
			$res_obj = new \stdClass;
			$res_obj->status = "FAILED";
			$res_obj->error = $response;
		}

		return $res_obj;
	}

	/**
	 * Performs actual API request to Jolo API server and handles all
	 *  different possibilities of responses that will come back
	 *
	 * @param $url string  Url to send request to
	 * @param $params array  Parameters that need to be sent
	 * @return boolean  true if API call succeeds or returns success else false
	 */
	protected function runApiRequest($url, array $params)
	{
		$ret = $this->phurl->withParams($params)->get($url);
		$this->jolo_response = $this->convertResponse($this->phurl->getResponse());

		return $ret && $this->checkResponseStatus($this->jolo_response);
	}

	/**
	 * Checks what is the status of response
	 *
	 * @param $response stdClass | string
	 * @return boolean  true if response returns SUCCESS status else false
	 */
	protected function checkResponseStatus($response)
	{
		if (is_object($response) && $response instanceof \stdClass) {
			return $response->status == "SUCCESS";
		}

		return false;
	}
}