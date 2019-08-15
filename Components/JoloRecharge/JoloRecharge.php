<?php

namespace Components\JoloRecharge;

use Components\Phurl\Phurl;
use Components\JoloRecharge\Entities\DthRecharge;
use Components\JoloRecharge\Entities\MobilePreRecharge;
use Components\JoloRecharge\Entities\MobilePostRecharge;
use Components\JoloRecharge\Exceptions\JoloEmptyParamsException;
use Components\JoloRecharge\Exceptions\JoloUnknownEntityException;

/**
 * JoloRecharge class
 * Jolo is an API provider for Bank transfer and mobile recharge 
 * This class handles Recharge API calls
 */
class JoloRecharge
{
	protected $phurl;				/* Phurl object for making http request */
	protected $params;				/* Given params for a request 			*/
	protected $jolo_response;		/* Jolo response came back on request 	*/

	use JoloAttributes;
	use JoloApiMethods;

	/**
	 * Instantiate JoloRecharge instance
	 *
	 * @param $phurl Phurl object  Handles http request
	 * @param $api_key string  API key of joloapi account
	 * @param $api_mode int  Mode 1 = Production, 0 = Development
	 * @return JoloRecharge
	 */
	public function __construct(Phurl $phurl, $api_key, $api_userid, $api_mode = 1)
	{
		$this->phurl 		= $phurl;
		$this->api_key 		= $api_key;
		$this->api_userid 	= $api_userid;
		$this->api_mode 	= ($api_mode === 0 || $api_mode === 1)? $api_mode : 0;

		// Setting the appropriate base api url for live / test mode
		if ($this->api_mode === 1) {
			$this->api_base_url = $this->api_base_url_live;
		} else {
			$this->api_base_url = $this->api_base_url_test;
		}
	}

	/**
	 * Creates JoloRecharge Entity (Agent, Beneficiary, Bank Transfer)
	 *  to make bank transfer, one must create Agent -> Beneficiary under agent
	 *  -> Transfer money to registered beneficiary
	 *
	 * @param $jolo_entity JoloAgent | JoloBeneficiary | JoloRechargeTransfer
	 * @return boolearn  true if success on creation else false
	 */
	public function create($jolo_entity)
	{
		// Check jolo entity is known entity
		$this->validateJoloEntity($jolo_entity);

		// Prepare correct method name for given $jolo_entity object
		// for e.g. $jolo_entity is MobilePreRecharge then $method_name will be
		// createMobilePreRecharge. For any given entity format is create<Entity>
		$method_name = $this->prepareMethod($jolo_entity);

		// $validate_method_name = validateCreate<Entity>
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
	 * Get the details of an API call
	 * used for fetching plans and offers of Mobile number or DTH within a
	 * circle.
	 *
	 * @param $entity string
	 * @param $params array
	 * @return boolean  true if request is successful else false
	 */
	public function detail($entity, array $params)
	{
		// Construct method name that corresponds to existing validation
		// method for detail api, constructed name will look like:
		// validateDetail<Entity>
		// validateDetailMobile, validateDetailDth, validateDetailMobilePlan
		$method_name = $this->prepareMethod($entity, $prefix = 'detail');

		// $validate_method_name = validateDetail<Entity>
		$validate_method_name = "validate".ucfirst($method_name);

		// Check Validate method for constructed method name exists
		if (!method_exists($this, $validate_method_name)) {
			throw new \Exception("Method $method_name does not exist");
		}

		// Call the validate method for checking required params for verify
		// api call is given, if fails then throw exception
		if (!$this->{$validate_method_name}($params)) {
			var_dump($validate_method_name);
			var_dump($params);
			throw new \Exception("Invalid params given for verify. check doc for required params.");
		}

		// Call the detail Api method with given parameters
		return $this->{$method_name}($params);
	}

	/**
	 * Report for dispute of any previous recharge transaction
	 *  call this API when recharge shown successful but recharge on mobile
	 *  was never done.
	 */
	public function dispute($entity, array $params)
	{
		// Construct method name that corresponds to existing validation
		// method for dispute api, constructed name will look like:
		// validateDispute<Entity>
		// validateDisputePrepaid, validateDisputePostpaid, validateDisputeDth
		$method_name = $this->prepareMethod($entity, $prefix = 'dispute');

		// $validate_method_name = validateDispute<Entity>
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

		// Call the dispute Api method with given parameters
		return $this->{$method_name}($params);
	}

	/**
	 * Check Jolo API balance and Tansaction status
	 *
	 * @param $entity string  'prepaid' | 'postpaid' view doc for all
	 * @param $params array  Parameters for corresponding entity
	 * @return boolean  true if check is successful else false
	 */
	public function check($entity, array $params = [])
	{
		// Construct method name that corresponds to existing validation
		// method for check api, constructed name will look like:
		// validateCheck<Entity>
		// validateCheckPrepaid, validateCheckPostpaid
		$method_name = $this->prepareMethod($entity, $prefix = 'check');

		// $validate_method_name = validateCheck<Entity>
		$validate_method_name = "validate".ucfirst($method_name);

		// Check Validate method for constructed method name exists
		if (!method_exists($this, $validate_method_name)) {
			throw new \Exception("Method $method_name does not exist");
		}

		// Call the validate method for checking required params for check
		// api call is given, if fails then throw exception
		if (!$this->{$validate_method_name}($params)) {
			throw new \Exception("Invalid params given for check. check doc for required params.");
		}

		// Call the check Api method with given parameters
		return $this->{$method_name}($params);
	}

	/**
	 * Checks given jolo entity object is instance of any of the known
	 *  entity or not. This is useful if user passes wrong JoloEntity object
	 *
	 * @param $jolo_entity MobilePreRecharge | MobilePostRecharge | DthRecharge
	 * @return mixed  boolean true on OK else throws exception
	 */
	protected function validateJoloEntity($jolo_entity)
	{
		if ($jolo_entity instanceof MobilePreRecharge) {
			return true;
		}

		if ($jolo_entity instanceof MobilePostRecharge) {
			return true;
		}

		if ($jolo_entity instanceof DthRecharge) {
			return true;
		}

		throw new JoloUnknownEntityException("Unknown Jolot Entity Object given");
	}

	/**
	 * Construct method name using given $entity object or string also
	 *  uses $prefix to prefix any string with method name. This is useful
	 *  for dynamically deciding which method to call for Jolo API request
	 *
	 * @param $entity MobilePreRecharge | MobilePostRecharge | DthRecharge | string
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
			$method_name = $prefix . $this->prepareSnakeCase($entity);
		} else {
			throw new \Exception("Invalid entity name \"$entity\" given");
		}

		return $method_name;
	}

	/**
	 * Construct method name using given $entity string
	 *  this method called by prepareMethod(). It handles snake case string
	 *
	 * @param $entity string
	 * @return string  Constructed method name
	 */
	protected function prepareSnakeCase($entity)
	{
		$parts = explode("_", $entity);
		if (count($parts) === 1) {
			return $entity;
		}
		$method_name = array_shift($parts);

		$parts = array_map(function($part) {
			return ucfirst($part);
		}, $parts);
		
		return $method_name . implode("", $parts);
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

		// Renaming dthid key to service as required by jolo api
		if (isset($params['dthid'])) {
			$dthid = $params['dthid'];
			unset($params['dthid']);
			$params['service'] = $dthid;
		}

		// Adding api_key in params as required by jolo api for every request
		$params['key'] = $this->api_key;

		// Adding api mode key (test or production)
		$params['mode'] = $this->api_mode;

		// Adding userid in params as required by jolo api for every request
		$params['userid'] = $this->api_userid;

		// Adding response type in params as required by jolo api for every
		// request. type = json OR type = text
		$params['type'] = $this->resp_type;
		
		return $params;
	}

	/**
	 * Validates required parameters for creating new prepaid mobile recharge
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateCreateMobilePreRecharge(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params['mob_pre_recharge']
		);
	}

	/**
	 * Validates required parameters for creating new postpaid mobile recharge
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateCreateMobilePostRecharge(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params['mob_post_recharge']
		);
	}

	/**
	 * Validates required parameters for creating new DTH recharge
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateCreateDthRecharge(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params['dth_recharge']
		);
	}

	/**
	 * Validates required parameters for balance check
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateCheckBalance(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_status['balance']
		);
	}

	/**
	 * Validates required parameters for checking status of prepaid recharge
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateCheckPrepaid(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_status['mob_pre_recharge']
		);
	}

	/**
	 * Validates required parameters for checking status of postpaid recharge
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateCheckPostpaid(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_status['mob_post_recharge']
		);
	}

	/**
	 * Validates required parameters for checking status of DTH recharge
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateCheckDth(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_status['dth_recharge']
		);
	}

	/**
	 * Validates required parameters for mobile prepaid recharge dispute
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateDisputePrepaid(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_dispute['mob_pre_recharge']
		);
	}

	/**
	 * Validates required parameters for mobile postpaid recharge dispute
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateDisputePostpaid(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_dispute['mob_post_recharge']
		);
	}

	/**
	 * Validates required parameters for dth recharge dispute
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateDisputeDth(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_dispute['dth_recharge']
		);
	}

	/**
	 * Validates required parameters for mobile operator and circle finder
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateDetailMobile(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_detail['mobile']
		);
	}

	/**
	 * Validates required parameters for DTH operator and circle finder
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateDetailDth(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_detail['dth']
		);
	}

	/**
	 * Validates required parameters for mobile plan and offer finder
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateDetailMobilePlan(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_detail['mobile_plan']
		);
	}

	/**
	 * Validates required parameters for DTH plan and offer finder
	 *
	 * @param $params array  Array of parameters
	 * @return boolean  true if validation passes else false
	 */
	protected function validateDetailDthPlan(array $params)
	{
		return $this->validateRequiredParams(
			$params,
			$this->required_params_detail['dth_plan']
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
		if (is_null($response) || (is_null($res_obj) && is_string($response))) {
			$res_obj = new \stdClass;
			$res_obj->status = "FAILED";
			$res_obj->error = $this->CUSTOM_ERROR_CODE;

		// If $res_obj is correctly converted to stdClass from JSON and
		// status property does not exist then set status property
		// This case only occurs when operator or plan finder api is called
		} else if ($res_obj instanceof \stdClass && !isset($res_obj->status)) {
			$res_obj->status = "SUCCESS";

		// If $res_obj is array of stdClass then create propper response object
		} else if (is_array($res_obj)) {
			$obj = new \stdClass;
			$obj->plans = $res_obj;
			$obj->status = "SUCCESS";
			$res_obj = null;
			$res_obj = $obj;
			$obj = null;
		}

		// If Recharge Dispute API call was performed then status will
		// be REPORTED if Jolo able to report it to operator successfully
		// Therefore I am changing status from REPORTED to SUCCESS. Because
		// it unifies status checking process in checkResponseStatus function
		if ($res_obj->status == "REPORTED") {
			$res_obj->status = "SUCCESS";
		}

		return $res_obj;
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