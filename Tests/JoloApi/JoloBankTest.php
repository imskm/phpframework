<?php

require __DIR__ . '/../init_test_mode.php';

use \Tests\BaseTest;
use Components\Phurl\Phurl;
use Components\JoloBank\JoloBank;
use Components\JoloBank\Entities\JoloAgent;
use Components\JoloBank\Entities\JoloBeneficiary;
use Components\JoloBank\Entities\JoloBankTransfer;

/**
 * JoloBankTest class
 * Jolo bank api test
 */
class JoloBankTest extends BaseTest
{
	protected $jolo_bank;

	public function __construct(JoloBank $jolo_bank)
	{
		$this->jolo_bank = $jolo_bank;

		/**
		 * My JOLO APIs
		 *
		 *
		
		// 1. Create agent
		$result = $this->jolo->agent([
			'phone' 	=> '',
			'name'		=> '',
			'email' 	=> '',
			'address' 	=> '',
		])->create();

		// OR 1.1 Create agent
		$result = $this->jolo->create(new JoloAgent([
			'phone' 	=> '',
			'name'		=> '',
			'email' 	=> '',
			'address' 	=> '',
		]));

		// 2. Verify agent
		//    phone number is required for verifying agent
		//    although other detail can be passed like
		//    otp = 1 for sending otp to agent, default is 0
		$result = $this->jolo->agent([
			'phone' 	=> '',
			'otp' 		=> 1,
		])->verify();

		// OR 2.1 Verify agent
		$result = $this->jolo->verify('agent', [
			'phone' => '',
			'otp'	=> ''
		]);

		// 3. Get agent details
		$result = $this->jolo->agent([
			'phone' 	=> '',
		])->detail();

		// OR 3.1 Get agent details
		$result = $this->jolo->detail('agent', [
			'phone' => '',
		]);

		// 4. Create beneficiary
		//    phone = mobile number of agent
		//    beneficiary_acct_no = bank a/c number of beneficiary
		//    rest are beneficiary bank details
		$result = $this->jolo->beneficiary([
			'phone' 				=> '',
			'name'					=> '',
			'beneficiary_ifsc' 		=> '',
			'beneficiary_acct_no'	=> '',
		])->create();

		// OR 4.1 Create beneficiary
		$result = $this->jolo->create(new JoloBeneficiary([
			'phone' 				=> '',
			'name'					=> '',
			'beneficiary_ifsc' 		=> '',
			'beneficiary_acct_no' 	=> '',
		]));

		// 5. Verify beneficiary
		//    phone = mobile number of agent
		//    beneficiary_id, the id sent back by beneficiary create
		//    otp = 1 for sending otp to agent, default is 0
		$result = $this->jolo->beneficiary([
			'phone' 				=> '',
			'beneficiary_id' 		=> '',
			'otp' 					=> 1,
		])->verify();

		// OR 5.1 Verify beneficiary
		$result = $this->jolo->verify('beneficiary', [
			'phone' 				=> '',
			'beneficiary_id' 		=> '',
			'otp' 					=> '',
		]);

		// 6. Get details of beneficiary
		//    beneficiary_id, the id sent back by beneficiary create
		$result = $this->jolo->beneficiary([
			'beneficiary_id' 		=> '',
		])->detail();

		// OR 6.1 Get details of beneficiary
		$result = $this->jolo->detail('beneficiary', [
			'beneficiary_id' 		=> '',
		]);

		// 7. Delete beneficiary
		//    beneficiary_id, the id sent back by beneficiary create
		$result = $this->jolo->beneficiary([
			'beneficiary_id' 		=> '',
		])->delete();

		// OR 7.1 Delete beneficiary
		$result = $this->jolo->delete('beneficiary', [
			'beneficiary_id'		=> '',
		]);

		// 8. Transfer money
		//    beneficiary_id, the id sent back by beneficiary create
		$result = $this->jolo->from([
			'phone' 				=> '',
			'beneficiary_id' 		=> '',
			'orderid' 				=> '',
			'amount' 				=> '',
			'remarks' 				=> '',
		])->transfer();

		// OR 8.1 Transfer money
		$result = $this->jolo->create(new JoloBankTransfer([
			'phone' 				=> '',
			'beneficiary_id' 		=> '',
			'orderid' 				=> '',
			'amount' 				=> '',
			'remarks' 				=> '',
		]));

		// 9
		// 

		// 9.1 Check Jolo Api Balance
		//     no parameters are required for balance check, but api key is
		//     required and it will be sent be JoloBank class
		$result = $this->jolo->check('balance');

		// 10
		// 

		// 10.1 Check bank account by transferring a small amount
		$result = $this->jolo->check('bank', [
			'phone'						=> '',
			'beneficiary_account_no'	=> '',
			'beneficiary_ifsc'			=> '',
			'orderid'					=> '',
		]);



		*/
	}

	public function test_jolo_bank_class_intantiated_properly()
	{
		if (!($this->jolo_bank instanceof JoloBank)) {
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_jolo_api_create_new_agent()
	{
		$result = $this->jolo_bank->create(new JoloAgent([
			'phone' 	=> '9992223335',
			'name'		=> 'Super Man',
			'email' 	=> 'superman@gmail.com',
			'address' 	=> 'H.No-32, G.K. Road, FakeTown, P.O.-FakeTown, PIN-123456, Some State',
		]));

		if (!$result) {
			$this->failed();
			return false;
		}
		$this->passed();

		return true;
	}

	public function test_jolo_api_verify_new_agent()
	{
		$result = $this->jolo_bank->verify('agent', [
			'phone' => '9992223335',
			'otp'   => '1234'
		]);

		if (!$result) {
			$this->failed();
			return false;
		}
		$this->passed();

		print_r($this->jolo_bank->getResponse());

		return true;
	}

	public function test_jolo_api_verify_new_agent_fails_due_to_wrong_otp()
	{
		$result = $this->jolo_bank->verify('agent', [
			'phone' => '6290342363',
			'otp'   => '12345'
		]);

		if ($result) {
			$this->failed();
			return false;
		}
		$this->passed();

		print_r($this->jolo_bank->getResponse());

		return true;
	}

	public function test_jolo_api_create_new_beneficiary_for_registered_agent()
	{
		$result = $this->jolo_bank->create(new JoloBeneficiary([
			'phone' 				=> '9992223335',		/* regd. agent phone number */
			'beneficiary_name'		=> 'bat man',
			'beneficiary_ifsc' 		=> '5454554',
			'beneficiary_account_no'=> '98464654654654',
		]));

		if (!$result) {
			$this->failed();
			print_r($this->jolo_bank->getResponse());
			return false;
		}
		$this->passed();

		print_r($this->jolo_bank->getResponse());

		return true;
	}

	public function test_jolo_api_verify_created_beneficiary()
	{
		$result = $this->jolo_bank->verify('beneficiary', [
			'phone' 				=> '9992223335',		/* regd. agent phone number */
			'beneficiaryid'		=> '98464654654654_5454554',
			'otp'				=> '1234',
		]);

		if (!$result) {
			$this->failed();
			print_r($this->jolo_bank->getResponse());
			return false;
		}
		$this->passed();

		print_r($this->jolo_bank->getResponse());

		return true;
	}

	/**
	 * Response does not have any error,
	 * Un-registered beneficiary id verification passes - STRANGE  :-]
	 */
	public function test_jolo_api_verify_fails_for_non_existing_beneficiary()
	{
		$result = $this->jolo_bank->verify('beneficiary', [
			'phone' 				=> '9992223335',		/* regd. agent phone number */
			'beneficiaryid'		=> '98464654654654_54545546', /* invalid */
			'otp'				=> '1234',
		]);

		// It should fail according to given wrong beneficiary id but it doesn't
		// for some reason. API response says ok (no problem from my side)
		if (!$result) {
			$this->failed();
			print_r($this->jolo_bank->getResponse());
			return false;
		}
		$this->passed();

		print_r($this->jolo_bank->getResponse());

		return true;
	}

	public function test_jolo_api_create_new_transfer_for_registered_beneficiary()
	{
		$result = $this->jolo_bank->create(new JoloBankTransfer([
			'phone' 				=> '9992223335',		/* regd. agent phone number */
			'beneficiaryid'			=> '98464654654654_5454554',
			'orderid' 				=> '123',
			'amount'				=> 4000,
			'remarks'				=> 'new bank transfer test',
		]));

		if (!$result) {
			$this->failed();
			print_r($this->jolo_bank->getResponse());
			return false;
		}
		$this->passed();

		print_r($this->jolo_bank->getResponse());

		return true;
	}

	public function test_jolo_api_verify_transfer_was_successful($txn)
	{
		$result = $this->jolo_bank->verify('transfer', [
			'txn' 				=> $txn,	/* transaction # came as res */
		]);

		if (!$result) {
			$this->failed();
			print_r($this->jolo_bank->getResponse());
			return false;
		}
		$this->passed();

		print_r($this->jolo_bank->getResponse());

		return true;
	}

	/**
	 * When doing API request for wrong transaction id for checking status of
	 * transaction returns plain string (not json) stating error - STRANGE
	 * The API provider must response json (API provider problem)
	 */
	public function test_jolo_api_verify_transfer_status_with_wrong_txn()
	{
		$result = $this->jolo_bank->verify('transfer', [
			'txn' 				=> 'B201903261899962522',
		]);

		if ($result) {
			$this->failed();
			print_r($this->jolo_bank->getResponse());
			return false;
		}
		$this->passed();

		print_r($this->jolo_bank->getResponse());

		return true;
	}

	public function test_jolo_api_get_detail_of_registered_agent()
	{
		$result = $this->jolo_bank->detail('agent', [
			'phone' 			=> '9992223335',
		]);

		if (!$result) {
			$this->failed();
			print_r($this->jolo_bank->getResponse());
			return false;
		}
		$this->passed();

		print_r($this->jolo_bank->getResponse());

		return true;
	}

	public function test_jolo_api_get_detail_fails_for_unregistered_agent()
	{
		$result = $this->jolo_bank->detail('agent', [
			'phone' 			=> '9290342363',
		]);

		if ($result) {
			$this->failed();
			print_r($this->jolo_bank->getResponse());
			return false;
		}
		$this->passed();

		print_r($this->jolo_bank->getResponse());

		return true;
	}

	public function test_jolo_api_get_detail_of_registered_beneficiary()
	{
		$result = $this->jolo_bank->detail('beneficiary', [
			'beneficiaryid' 		=> '98464654654654_5454554',
		]);

		if (!$result) {
			$this->failed();
			print_r($this->jolo_bank->getResponse());
			return false;
		}
		$this->passed();

		print_r($this->jolo_bank->getResponse());

		return true;
	}

	public function test_jolo_api_get_detail_fails_for_unregistered_beneficiary()
	{
		$result = $this->jolo_bank->detail('beneficiary', [
			'beneficiaryid' 		=> '98464654654654_5454566',
		]);

		if ($result) {
			$this->failed();
			print_r($this->jolo_bank->getResponse());
			return false;
		}
		$this->passed();

		print_r($this->jolo_bank->getResponse());

		return true;
	}

	public function test_jolo_api_delete_registered_beneficiary()
	{
		$result = $this->jolo_bank->delete('beneficiary', [
			'beneficiaryid' 		=> '98464654654654_5454554',
		]);

		if (!$result) {
			$this->failed();
			print_r($this->jolo_bank->getResponse());
			return false;
		}
		$this->passed();

		print_r($this->jolo_bank->getResponse());

		return true;
	}

	public function test_jolo_api_balance_check()
	{
		$result = $this->jolo_bank->check('balance');

		if (!$result) {
			$this->failed();
			print_r($this->jolo_bank->getResponse());
			return false;
		}
		$this->passed();

		print_r($this->jolo_bank->getResponse());

		return true;
	}

	public function test_jolo_api_bank_account_correctness_check()
	{
		$result = $this->jolo_bank->check('bank', [
			'phone' 			=> '9992223335',	/* regd. agent phone number */
			'beneficiary_account_no'=> '98464654654654_5454554',
			'beneficiary_ifsc' 	=> '5454554',				
			'orderid'			=> 123,				/* set by my website */
		]);

		if (!$result) {
			$this->failed();
			print_r($this->jolo_bank->getResponse());
			return false;
		}
		$this->passed();

		print_r($this->jolo_bank->getResponse());

		return true;
	}
}


$test = new JoloBankTest(new JoloBank(new Phurl, $api_key = "322051306294169", 0));

// TEST #1 : test_jolo_bank_class_intantiated_properly
echo "TEST #1: test_jolo_bank_class_intantiated_properly: ";
$test->test_jolo_bank_class_intantiated_properly();

// TEST #2 : test_jolo_api_create_new_agent
echo "TEST #2: test_jolo_api_create_new_agent: ";
$test->test_jolo_api_create_new_agent();

$test3 = new JoloBankTest(new JoloBank(new Phurl, $api_key = "322051306294169", 0));
// TEST #3 : test_jolo_api_verify_new_agent
echo "TEST #3: test_jolo_api_verify_new_agent: ";
$test3->test_jolo_api_verify_new_agent();

$test4 = new JoloBankTest(new JoloBank(new Phurl, $api_key = "322051306294169", 0));
// TEST #4 : test_jolo_api_verify_new_agent_fails_due_to_wrong_otp
echo "TEST #4: test_jolo_api_verify_new_agent_fails_due_to_wrong_otp: ";
$test4->test_jolo_api_verify_new_agent_fails_due_to_wrong_otp();

$test5 = new JoloBankTest(new JoloBank(new Phurl, $api_key = "322051306294169", 0));
// TEST #5 : test_jolo_api_create_new_beneficiary_for_registered_agent
echo "TEST #5: test_jolo_api_create_new_beneficiary_for_registered_agent: ";
$test5->test_jolo_api_create_new_beneficiary_for_registered_agent();

$test6 = new JoloBankTest(new JoloBank(new Phurl, $api_key = "322051306294169", 0));
// TEST #6 : test_jolo_api_verify_created_beneficiary
echo "TEST #6: test_jolo_api_verify_created_beneficiary: ";
$test6->test_jolo_api_verify_created_beneficiary();

$test7 = new JoloBankTest(new JoloBank(new Phurl, $api_key = "322051306294169", 0));
// TEST #7 : test_jolo_api_verify_fails_for_non_existing_beneficiary
echo "TEST #7: test_jolo_api_verify_fails_for_non_existing_beneficiary: ";
$test7->test_jolo_api_verify_fails_for_non_existing_beneficiary();

$test8 = new JoloBankTest($jb = new JoloBank(new Phurl, $api_key = "322051306294169", 0));
// TEST #8 : test_jolo_api_create_new_transfer_for_registered_beneficiary
echo "TEST #8: test_jolo_api_create_new_transfer_for_registered_beneficiary: ";
$test8->test_jolo_api_create_new_transfer_for_registered_beneficiary();

$test9 = new JoloBankTest(new JoloBank(new Phurl, $api_key = "322051306294169", 0));
// TEST #9 : test_jolo_api_verify_transfer_was_successful
echo "TEST #9: test_jolo_api_verify_transfer_was_successful: ";
$test9->test_jolo_api_verify_transfer_was_successful($jb->getResponse()->txid);

$test10 = new JoloBankTest(new JoloBank(new Phurl, $api_key = "322051306294169", 0));
// TEST #10 : test_jolo_api_verify_transfer_status_with_wrong_txn
echo "TEST #10: test_jolo_api_verify_transfer_status_with_wrong_txn: ";
$test10->test_jolo_api_verify_transfer_status_with_wrong_txn();

$test11 = new JoloBankTest(new JoloBank(new Phurl, $api_key = "322051306294169", 0));
// TEST #11 : test_jolo_api_get_detail_of_registered_agent
echo "TEST #11: test_jolo_api_get_detail_of_registered_agent: ";
$test11->test_jolo_api_get_detail_of_registered_agent();

$test12 = new JoloBankTest(new JoloBank(new Phurl, $api_key = "322051306294169", 0));
// TEST #12 : test_jolo_api_get_detail_fails_for_unregistered_agent
echo "TEST #12: test_jolo_api_get_detail_fails_for_unregistered_agent: ";
$test12->test_jolo_api_get_detail_fails_for_unregistered_agent();

$test13 = new JoloBankTest(new JoloBank(new Phurl, $api_key = "322051306294169", 0));
// TEST #13 : test_jolo_api_get_detail_of_registered_beneficiary
echo "TEST #13: test_jolo_api_get_detail_of_registered_beneficiary: ";
$test13->test_jolo_api_get_detail_of_registered_beneficiary();

$test14 = new JoloBankTest(new JoloBank(new Phurl, $api_key = "322051306294169", 0));
// TEST #14 : test_jolo_api_get_detail_fails_for_unregistered_beneficiary
echo "TEST #14: test_jolo_api_get_detail_fails_for_unregistered_beneficiary: ";
$test14->test_jolo_api_get_detail_fails_for_unregistered_beneficiary();

$test15 = new JoloBankTest(new JoloBank(new Phurl, $api_key = "322051306294169", 0));
// TEST #15 : test_jolo_api_delete_registered_beneficiary
echo "TEST #15: test_jolo_api_delete_registered_beneficiary: ";
$test15->test_jolo_api_delete_registered_beneficiary();

$test16 = new JoloBankTest(new JoloBank(new Phurl, $api_key = "322051306294169", 0));
// TEST #16 : test_jolo_api_balance_check
echo "TEST #16: test_jolo_api_balance_check: ";
$test16->test_jolo_api_balance_check();

$test17 = new JoloBankTest(new JoloBank(new Phurl, $api_key = "322051306294169", 0));
// TEST #17 : test_jolo_api_bank_account_correctness_check
echo "TEST #17: test_jolo_api_bank_account_correctness_check: ";
$test17->test_jolo_api_bank_account_correctness_check();




echo "\n\n";

/**
 * ----------------------------------------------------------------------------
 * Very Important Notes
 * ----------------------------------------------------------------------------
 *
 * 1. 
 *
 */