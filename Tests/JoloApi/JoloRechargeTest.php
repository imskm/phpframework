<?php

require __DIR__ . '/../init_test_mode.php';

use \Tests\BaseTest;
use Components\Phurl\Phurl;
use Components\JoloRecharge\JoloError;
use Components\JoloRecharge\JoloRecharge;
use Components\JoloRecharge\Entities\DthRecharge;
use Components\JoloRecharge\Entities\MobilePreRecharge;
use Components\JoloRecharge\Entities\MobilePostRecharge;

/**
 * JJolorRechargeTest class
 * Jolo recharge api test
 */
class JoloRechargeTest extends BaseTest
{
	protected $jolo_recharge;

	public function __construct(JoloRecharge $jolo_recharge)
	{
		$this->jolo_recharge = $jolo_recharge;

		/**
		 * My JOLO APIs
		 *
		 *
		
		// 1 Create Mobile Prepaid Recharge
		$result = $this->jolo_recharge->create(new MobilePreRecharge([
			'phone' 	=> '',
			'amount'	=> '',
			'orderid' 	=> '',
			'operator' 	=> '',
		]));

		// 2 Create Mobile Postpaid Recharge
		$result = $this->jolo_recharge->create(new MobilePostRecharge([
			'phone' 	=> '',
			'amount'	=> '',
			'orderid' 	=> '',
			'operator' 	=> '',
		]));

		// 3 Create DTH Recharge
		$result = $this->jolo_recharge->create(new DthRecharge([
			'dthid' 	=> '',
			'amount'	=> '',
			'orderid' 	=> '',
			'operator' 	=> '',
		]));

		// 4 Check status of specific recharge transaction by orderid (created by you)
		//   prepaid   = Mobile prepaid & datacard recharge
		//   postpaid  = Mobile postpaid & datacard recharge
		//   dth  = DTH recharge
		//   balance = Jolo API balance
		// 
		//   These are not implemented for now
		//   landline  = Landline bill payment
		//   electricity  = Electricity bill payment
		//   gas  = Gas bill payment
		//   insurance  = Insurance premium payment
		$result = $this->jolo_recharge->check('prepaid', [
			'txn'		=> '',
		]);

		// 5 Check Jolo API Balance
		$result = $this->jolo_recharge->check('balance');

		// 6 Find operator and cirlce of mobile number and dth subscriber ID
		$result = $this->jolo_recharge->detail('mobile');
		$result = $this->jolo_recharge->detail('dth');

		// 7 Find plans of mobile number and dth subscriber ID for a circle
		$result = $this->jolo_recharge->detail('mobile_plan');
		$result = $this->jolo_recharge->detail('dth_plan');




		// OR 2.1 Verify agent
		$result = $this->jolo->verify('agent', [
			'phone' => '',
			'otp'	=> ''
		]);

		// OR 3.1 Get agent details
		$result = $this->jolo->detail('agent', [
			'phone' => '',
		]);


		// 9.1 Check Jolo Api Balance
		//     no parameters are required for balance check, but api key is
		//     required and it will be sent be JoloBank class
		$result = $this->jolo->check('balance');

		// 10.1 Check bank account by transferring a small amount
		$result = $this->jolo->check('bank', [
			'phone'						=> '',
			'beneficiary_account_no'	=> '',
			'beneficiary_ifsc'			=> '',
			'orderid'					=> '',
		]);


		*/
	}

	public function test_jolo_recharge_class_intantiated_properly()
	{
		if (!($this->jolo_recharge instanceof JoloRecharge)) {
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_jolo_api_create_mobile_recharge()
	{
		$result = $this->jolo_recharge->create(new MobilePreRecharge([
			'phone' 	=> '9992223335',
			'amount'	=> 10,	/* amount range 4 to 25000 */
			'orderid' 	=> '123',
			'operator' 	=> 'JO',		// Jo = Jio
		]));


		if (!$result) {
			$this->failed();
			$failed_res = $this->jolo_recharge->getResponse();
			$failed_res->error = JoloError::getByCode($failed_res->error);
			print_r($failed_res);
			return false;
		}
		$this->passed();
		print_r($this->jolo_recharge->getResponse());

		return true;
	}

	public function test_jolo_api_create_mobile_recharge_0_amount_fails()
	{
		$result = $this->jolo_recharge->create(new MobilePreRecharge([
			'phone' 	=> '9992223335',
			'amount'	=> 0,
			'orderid' 	=> '1234',
			'operator' 	=> 'JO',		// Jo = Jio
		]));


		if ($result) {
			$this->failed();
			$failed_res = $this->jolo_recharge->getResponse();
			$failed_res->error = JoloError::getByCode($failed_res->error);
			print_r($failed_res);
			return false;
		}
		$this->passed();
		print_r($this->jolo_recharge->getResponse());

		return true;
	}

	public function test_jolo_api_create_mobile_post_recharge()
	{
		$result = $this->jolo_recharge->create(new MobilePostRecharge([
			'phone' 	=> '9992223335',
			'amount'	=> 25000,
			'orderid' 	=> '12345',
			'operator' 	=> 'JPOS',		// JPOS = Jio Postpaid
		]));


		if (!$result) {
			$this->failed();
			$failed_res = $this->jolo_recharge->getResponse();
			$failed_res->error = JoloError::getByCode($failed_res->error);
			print_r($failed_res);
			return false;
		}
		$this->passed();
		print_r($this->jolo_recharge->getResponse());

		return true;
	}

	public function test_jolo_api_create_dth_recharge()
	{
		$result = $this->jolo_recharge->create(new DthRecharge([
			'dthid' 	=> '3001915457',
			'amount'	=> 100,
			'orderid' 	=> '123456',
			'operator' 	=> 'AD',		// AD = Airtel DTH
		]));


		if (!$result) {
			$this->failed();
			$failed_res = $this->jolo_recharge->getResponse();
			$failed_res->error = JoloError::getByCode($failed_res->error);
			print_r($failed_res);
			return false;
		}
		$this->passed();
		print_r($this->jolo_recharge->getResponse());

		return true;
	}

	public function test_jolo_api_dth_recharge_fails_for_invalid_operator()
	{
		$result = $this->jolo_recharge->create(new DthRecharge([
			'dthid' 	=> '93319254',
			'amount'	=> 100,
			'orderid' 	=> '1236',
			'operator' 	=> 'AX',		// AX invalid operator code
		]));


		if ($result) {
			$this->failed();
			$failed_res = $this->jolo_recharge->getResponse();
			$failed_res->error = JoloError::getByCode($failed_res->error);
			print_r($failed_res);
			return false;
		}
		$this->passed();
		print_r($this->jolo_recharge->getResponse());

		return true;
	}

	public function test_jolo_api_mobile_prepaid_status_check()
	{
		$result = $this->jolo_recharge->check('prepaid', [
			'txn'		=> '123',   /* orderid that you generated not Jolo */
		]);

		if (!$result) {
			$this->failed();
			$failed_res = $this->jolo_recharge->getResponse();
			$failed_res->error = JoloError::getByCode($failed_res->error);
			print_r($failed_res);
			return false;
		}
		$this->passed();
		print_r($this->jolo_recharge->getResponse());

		return true;
	}

	public function test_jolo_api_mobile_postpaid_status_check()
	{
		$result = $this->jolo_recharge->check('postpaid', [
			'txn'		=> '12345',   /* orderid that you generated not Jolo */
		]);

		if (!$result) {
			$this->failed();
			$failed_res = $this->jolo_recharge->getResponse();
			$failed_res->error = JoloError::getByCode($failed_res->error);
			print_r($failed_res);
			return false;
		}
		$this->passed();
		print_r($this->jolo_recharge->getResponse());

		return true;
	}

	public function test_jolo_api_dth_status_check()
	{
		$result = $this->jolo_recharge->check('dth', [
			'txn'		=> '123456',   /* orderid that you generated not Jolo */
		]);

		if (!$result) {
			$this->failed();
			$failed_res = $this->jolo_recharge->getResponse();
			$failed_res->error = JoloError::getByCode($failed_res->error);
			print_r($failed_res);
			return false;
		}
		$this->passed();
		print_r($this->jolo_recharge->getResponse());

		return true;
	}

	public function test_jolo_api_balance_check()
	{
		$result = $this->jolo_recharge->check('balance');

		if (!$result) {
			$this->failed();
			$failed_res = $this->jolo_recharge->getResponse();
			$failed_res->error = JoloError::getByCode($failed_res->error);
			print_r($failed_res);
			return false;
		}
		$this->passed();
		print_r($this->jolo_recharge->getResponse());

		return true;
	}

	public function test_jolo_api_mobile_prepaid_recharge_dispute_reporting()
	{
		$result = $this->jolo_recharge->dispute('prepaid', [
			'txn'		=> '123',   /* orderid that you generated not Jolo */
		]);

		if (!$result) {
			$this->failed();
			$failed_res = $this->jolo_recharge->getResponse();
			$failed_res->error = JoloError::getByCode($failed_res->error);
			print_r($failed_res);
			return false;
		}
		$this->passed();
		print_r($this->jolo_recharge->getResponse());

		return true;
	}

	public function test_jolo_api_mobile_postpaid_recharge_dispute_reporting()
	{
		$result = $this->jolo_recharge->dispute('postpaid', [
			'txn'		=> '12345',   /* orderid that you generated not Jolo */
		]);

		if (!$result) {
			$this->failed();
			$failed_res = $this->jolo_recharge->getResponse();
			$failed_res->error = JoloError::getByCode($failed_res->error);
			print_r($failed_res);
			return false;
		}
		$this->passed();
		print_r($this->jolo_recharge->getResponse());

		return true;
	}

	public function test_jolo_api_dth_recharge_dispute_reporting()
	{
		$result = $this->jolo_recharge->dispute('dth', [
			'txn'		=> '123456',   /* orderid that you generated not Jolo */
		]);

		if (!$result) {
			$this->failed();
			$failed_res = $this->jolo_recharge->getResponse();
			$failed_res->error = JoloError::getByCode($failed_res->error);
			print_r($failed_res);
			return false;
		}
		$this->passed();
		print_r($this->jolo_recharge->getResponse());

		return true;
	}

	public function test_jolo_api_detect_detail_of_mobile_operator_and_circle()
	{
		$result = $this->jolo_recharge->detail('mobile', [
			'mob'		=> '9876543210',   /* mobile number */
		]);

		if (!$result) {
			$this->failed();
			$failed_res = $this->jolo_recharge->getResponse();
			$failed_res->error = JoloError::getByCode($failed_res->error);
			print_r($failed_res);
			return false;
		}
		$this->passed();
		print_r($this->jolo_recharge->getResponse());

		return true;
	}

	public function test_jolo_api_detect_detail_of_dth_operator_and_circle()
	{
		$result = $this->jolo_recharge->detail('dth', [
			'mob'		=> '93319254',   /* dth subscriber id */
		]);

		if (!$result) {
			$this->failed();
			$failed_res = $this->jolo_recharge->getResponse();
			$failed_res->error = JoloError::getByCode($failed_res->error);
			print_r($failed_res);
			return false;
		}
		$this->passed();
		print_r($this->jolo_recharge->getResponse());

		return true;
	}

	public function test_jolo_api_get_detail_of_mobile_plan_and_offer()
	{
		$result = $this->jolo_recharge->detail('mobile_plan', [
			'opt'		=> 28,  	/* operator code (airtel) */
			'cir'		=> 3,   	/* circle code (kolkata) */
		]);

		if (!$result) {
			$this->failed();
			$failed_res = $this->jolo_recharge->getResponse();
			$failed_res->error = JoloError::getByCode($failed_res->error);
			print_r($failed_res);
			return false;
		}
		$this->passed();
		print_r($this->jolo_recharge->getResponse());

		return true;
	}

	public function test_jolo_api_get_detail_of_dth_plan_and_offer()
	{
		$result = $this->jolo_recharge->detail('dth_plan', [
			'opt'		=> 97,   /* operator code (dish tv) */
		]);

		if (!$result) {
			$this->failed();
			$failed_res = $this->jolo_recharge->getResponse();
			$failed_res->error = JoloError::getByCode($failed_res->error);
			print_r($failed_res);
			return false;
		}
		$this->passed();
		print_r($this->jolo_recharge->getResponse());

		return true;
	}



	
}


$test = new JoloRechargeTest(new JoloRecharge(new Phurl, $api_key = "167405610330016", $userid = 'skm_im07', $mode = 0));

// TEST #1 : test_jolo_recharge_class_intantiated_properly
echo "TEST #1: test_jolo_recharge_class_intantiated_properly: ";
$test->test_jolo_recharge_class_intantiated_properly();

// TEST #2 : test_jolo_api_create_mobile_recharge
echo "TEST #2: test_jolo_api_create_mobile_recharge: ";
$test->test_jolo_api_create_mobile_recharge();

$test3 = new JoloRechargeTest(new JoloRecharge(new Phurl, $api_key = "167405610330016", $userid = 'skm_im07', $mode = 0));
// TEST #3 : test_jolo_api_create_mobile_recharge_0_amount_fails
echo "TEST #3: test_jolo_api_create_mobile_recharge_0_amount_fails: ";
$test3->test_jolo_api_create_mobile_recharge_0_amount_fails();

$test4 = new JoloRechargeTest(new JoloRecharge(new Phurl, $api_key = "167405610330016", $userid = 'skm_im07', $mode = 0));
// TEST #4 : test_jolo_api_create_mobile_post_recharge
echo "TEST #4: test_jolo_api_create_mobile_post_recharge: ";
$test4->test_jolo_api_create_mobile_post_recharge();

$test5 = new JoloRechargeTest(new JoloRecharge(new Phurl, $api_key = "167405610330016", $userid = 'skm_im07', $mode = 0));
// TEST #5 : test_jolo_api_create_dth_recharge
echo "TEST #5: test_jolo_api_create_dth_recharge: ";
$test5->test_jolo_api_create_dth_recharge();

$test6 = new JoloRechargeTest(new JoloRecharge(new Phurl, $api_key = "167405610330016", $userid = 'skm_im07', $mode = 0));
// TEST #6 : test_jolo_api_dth_recharge_fails_for_invalid_operator
echo "TEST #6: test_jolo_api_dth_recharge_fails_for_invalid_operator: ";
$test6->test_jolo_api_dth_recharge_fails_for_invalid_operator();


/////////////////////////////
// This test 7, 8, 9, 10, 11, 12 fails because of test mode.
// So If you see Red FAILED for this test then don't worry it is fine.
$test7 = new JoloRechargeTest(new JoloRecharge(new Phurl, $api_key = "167405610330016", $userid = 'skm_im07', $mode = 0));
// TEST #7 : test_jolo_api_mobile_prepaid_status_check
echo "TEST #7: test_jolo_api_mobile_prepaid_status_check: ";
$test7->test_jolo_api_mobile_prepaid_status_check();
$test8 = new JoloRechargeTest(new JoloRecharge(new Phurl, $api_key = "167405610330016", $userid = 'skm_im07', $mode = 0));
// TEST #8 : test_jolo_api_mobile_postpaid_status_check
echo "TEST #8: test_jolo_api_mobile_postpaid_status_check: ";
$test8->test_jolo_api_mobile_postpaid_status_check();

$test9 = new JoloRechargeTest(new JoloRecharge(new Phurl, $api_key = "167405610330016", $userid = 'skm_im07', $mode = 0));
// TEST #9 : test_jolo_api_dth_status_check
echo "TEST #9: test_jolo_api_dth_status_check: ";
$test9->test_jolo_api_dth_status_check();

$test10 = new JoloRechargeTest(new JoloRecharge(new Phurl, $api_key = "167405610330016", $userid = 'skm_im07', $mode = 0));
// TEST #10 : test_jolo_api_mobile_prepaid_recharge_dispute_reporting
echo "TEST #10: test_jolo_api_mobile_prepaid_recharge_dispute_reporting: ";
$test10->test_jolo_api_mobile_prepaid_recharge_dispute_reporting();

$test11 = new JoloRechargeTest(new JoloRecharge(new Phurl, $api_key = "167405610330016", $userid = 'skm_im07', $mode = 0));
// TEST #11 : test_jolo_api_mobile_postpaid_recharge_dispute_reporting
echo "TEST #11: test_jolo_api_mobile_postpaid_recharge_dispute_reporting: ";
$test11->test_jolo_api_mobile_postpaid_recharge_dispute_reporting();

$test12 = new JoloRechargeTest(new JoloRecharge(new Phurl, $api_key = "167405610330016", $userid = 'skm_im07', $mode = 0));
// TEST #12 : test_jolo_api_dth_recharge_dispute_reporting
echo "TEST #12: test_jolo_api_dth_recharge_dispute_reporting: ";
$test12->test_jolo_api_dth_recharge_dispute_reporting();



$test13 = new JoloRechargeTest(new JoloRecharge(new Phurl, $api_key = "167405610330016", $userid = 'skm_im07', $mode = 0));
// TEST #13 : test_jolo_api_balance_check
echo "TEST #13: test_jolo_api_balance_check: ";
$test13->test_jolo_api_balance_check();


$test14 = new JoloRechargeTest(new JoloRecharge(new Phurl, $api_key = "167405610330016", $userid = 'skm_im07', $mode = 0));
// TEST #14 : test_jolo_api_detect_detail_of_mobile_operator_and_circle
echo "TEST #14: test_jolo_api_detect_detail_of_mobile_operator_and_circle: ";
$test14->test_jolo_api_detect_detail_of_mobile_operator_and_circle();

$test15 = new JoloRechargeTest(new JoloRecharge(new Phurl, $api_key = "167405610330016", $userid = 'skm_im07', $mode = 0));
// TEST #15 : test_jolo_api_detect_detail_of_dth_operator_and_circle
echo "TEST #15: test_jolo_api_detect_detail_of_dth_operator_and_circle: ";
$test15->test_jolo_api_detect_detail_of_dth_operator_and_circle();

$test16 = new JoloRechargeTest(new JoloRecharge(new Phurl, $api_key = "167405610330016", $userid = 'skm_im07', $mode = 0));
// TEST #16 : test_jolo_api_get_detail_of_mobile_plan_and_offer
echo "TEST #16: test_jolo_api_get_detail_of_mobile_plan_and_offer: ";
$test16->test_jolo_api_get_detail_of_mobile_plan_and_offer();

$test17 = new JoloRechargeTest(new JoloRecharge(new Phurl, $api_key = "167405610330016", $userid = 'skm_im07', $mode = 0));
// TEST #17 : test_jolo_api_get_detail_of_dth_plan_and_offer
echo "TEST #17: test_jolo_api_get_detail_of_dth_plan_and_offer: ";
$test17->test_jolo_api_get_detail_of_dth_plan_and_offer();



echo "\n\n";

/**
 * ----------------------------------------------------------------------------
 * Very Important Notes
 * ----------------------------------------------------------------------------
 *
 * 1. 
 *
 */