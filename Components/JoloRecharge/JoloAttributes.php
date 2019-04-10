<?php

namespace Components\JoloRecharge;

trait JoloAttributes
{
	private $api_base_url = 'https://joloapi.com';
	private $api_key;
	private $api_userid;			/* Jolo account user id */
	private $api_mode; 				/* test = 0, live = 1 */
	private $resp_type = 'json';	/* Response type json OR text */

	/**
	 * Read Doc if any doubts
	 * https://joloapi.com/docs.php
	 */

	/**
	 * Recharge API urls
	 * Leading / (slash) is must DO NOT DELETE IT
	 */
	private $mob_pre_rech_url	= '/api/recharge.php'; /* Mobile & Datacard */
	private $mob_post_rech_url	= '/api/cbill.php';    /* Mobile & Datacard */
	private $dth_rech_url		= '/api/recharge.php';
	private $land_broad_url		= '/api/cbill.php';	/* Landline & Broadband */

	/**
	 * Jolo API balance checking URL
	 */
	private $balance_check_url  = '/api/balance.php';  /* API balance check URL */

	/**
	 * Recharge status check URL for all types of recharge
	 * Mobile Prepaid Recharge, Mobile Postpaid Recharge, DTH Recharge, etc.
	 */
	private $recharge_check_url = '/api/rechargestatus_client.php';
	private $recharge_check_jolo_url = '/api/rechargestatus.php'; /* Jolo orderid */

	/**
	 * Report a dispute in recharge to this url using Jolo order Id (txn)
	 * You can report dispute transaction of last 10 days only.
	 */
	private $recharge_dispute_url = '/api/dispute_client.php';
	private $recharge_dispute_jolo_url = '/api/dispute.php'; /* Jolo orderid */

	/**
	 * Find operator and circle of a mobile number or DTH subscriber ID
	 */
	private $mobile_finder_url 	= '/api/findoperator.php';
	private $dth_finder_url 	= '/api/finddth.php';

	/**
	 * Find plan and offer by operator and circle code for mobile
	 * or DTH subscriber ID
	 */
	private $mobile_plan_url 	= '/api/findplan.php';
	private $dth_plan_url 		= '/api/findplandth.php';

	/**
	 * Jolo API required params for creating agent, beneficiary
	 */
	private $required_params = [
		'mob_pre_recharge' => [
			'phone' 			=> true,  /* phone number to be recharged */
			'amount' 			=> true,  /* amount to be recharged */
			'orderid' 			=> true,
			'operator' 			=> true,  /* Operator code like JO for Jio */
		],
		'mob_post_recharge'=> [
			'phone' 			=> true,  /* phone number to be recharged */
			'amount' 			=> true,  /* amount to be recharged */
			'orderid' 			=> true,
			'operator' 			=> true,  /* Operator code like JO for Jio */
		],
		'dth_recharge'	   => [
			'dthid' 			=> true,  /* dth id of customer to be recharged */
			'amount' 			=> true,  /* amount to be recharged */
			'orderid' 			=> true,
			'operator' 			=> true,  /* Operator code like JO for Jio */
		],
	];

	/**
	 * Jolo API required params for transaction status check
	 */
	private $required_params_status = [
		'balance' => [
			// No params is required for balance checking
			// required params are automatically set by JoloRecharge
		],
		'mob_pre_recharge' => [
			'txn' 			=> true,   /* Your order id (not jolo order id) */
		],
		'mob_post_recharge'=> [
			'txn' 			=> true,
		],
		'dth_recharge'	   => [
			'txn' 			=> true,
		],
	];

	/**
	 * Jolo API required params for transaction dispute
	 */
	private $required_params_dispute = [
		'mob_pre_recharge' => [
			'txn' 			=> true,   /* Your order id (not jolo order id) */
		],
		'mob_post_recharge'=> [
			'txn' 			=> true,
		],
		'dth_recharge'	   => [
			'txn' 			=> true,
		],
	];

	/**
	 * Jolo API required params for operator & circle and plan & offer finder
	 * View the README doc for optinal params
	 *  some examples are: max, amt, and typ
	 */
	private $required_params_detail = [
		'mobile' => [
			'mob' 			=> true,   /* Mobile to find operator & circle */
		],
		'dth'=> [
			'mob' 			=> true,   /* DTH ID to find operator & circle */
		],
		'mobile_plan' => [
			'opt' 			=> true,   /* Operator code */
			'cir' 			=> true,   /* Circle code */
		],
		'dth_plan'=> [
			'opt' 			=> true,   /* Operator code */
		],
	];

	/**
	 * Jolo Mobile recharge API service types for transaction status check
	 */
	private $service_types = [
		'prepaid'		=> 1,	     /* Mobile prepaid recharge */
		'dth'			=> 2,	     /* DTH recharge */
		'postpaid'		=> 4,	     /* Mobile postpaid recharge */

		// Other services are not implemented yet
	];

	private $CUSTOM_ERROR_CODE  = 999;       /* custom error code. Read JoloError */

}