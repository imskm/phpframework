<?php

namespace Components\JoloRecharge;

/**
 * JoloRechargeOperator class
 * Made easy to fetch Operator using this class, check valid operator
 */
class JoloRechargeOperator
{
	/**
	 * @var array Prepaid mobile recharge operators
	 */
	private static $operators_prepaid = [
		'AT'	=>	'Airtel',
		'BS'	=>	'BSNL',
		'BSS'	=>	'BSNL Special',
		'IDX'	=>	'Idea',
		'JO'	=>	'Jio',
		'VF'	=>	'Vodafone',
		'TD'	=>	'Tata Docomo GSM',
		'TDS'	=>	'Tata Docomo GSM Special',
		'MTD'	=>	'MTNL Delhi',
		'MTDS'	=>	'MTNL Delhi Special',
		'MTM'	=>	'MTNL Mumbai',
		'MTMS'	=>	'MTNL Mumbai Special',
	];

	/**
	 * @var array Postpaid mobile recharge operators
	 */
	private static $operators_postpaid = [
		'APOS'	=>	'Airtel',
		'BPOS'	=>	'BSNL',
		'IPOS'	=>	'Idea',
		'JPOS'	=>	'Jio',
		'VPOS'	=>	'Vodafone',
		'DGPOS'	=>	'Tata Docomo GSM',

	];

	/**
	 * @var array DTH recharge operators
	 */
	private static $operators_dth = [
		'VT'	=>	'Videocon D2H',
		'SD'	=>	'Sun DTH',
		'BT'	=>	'Reliance Big TV DTH',
		'TS'	=>	'Tata Sky DTH',
		'DT'	=>	'Dish DTH',
		'AD'	=>	'Airtel DTH',
	];
	/* ----------------------------------------------------------
	 * Landline and Broadband bill payment not implemented
	 * ----------------------------------------------------------
	 */


	/**
	 * @var array Prepaid mobile recharge operators (number code)
	 *      used in for fetching plans of an oeprator (only prepaid)
	 */
	private static $operators_mobile_id = [
		'AT'	=>	28,
		'BS'	=>	3,
		'BSS'	=>	3,
		'IDX'	=>	8,
		'JO'	=>	29,
		'VF'	=>	22,
		'TD'	=>	17,
		'TDS'	=>	17,
		'MTD'	=>	20,
		'MTDS'	=>	20,
		'MTM'	=>	6,
		'MTMS'	=>	6,
	];

	/**
	 * @var array DTH recharge operators (number code)
	 */
	private static $operators_dth_id = [

		'VT'	=>	95,
		'SD'	=>	98,
		'BT'	=>	96,
		'TS'	=>	94,
		'DT'	=>	97,
		'AD'	=>	93,
	];

	/**
	 * @var array  of circle codes
	 */
	private static $operators_circles = [
		 // 0 => 'None',
		 1 => 'Delhi/NCR',
		 2 => 'Mumbai',
		 3 => 'Kolkata',
		 4 => 'Maharashtra',
		 5 => 'Andhra Pradesh',
		 6 => 'Tamil Nadu',
		 7 => 'Karnataka',
		 8 => 'Gujarat',
		 9 => 'Uttar Pradesh (E)',
		10 => 'Madhya Pradesh',
		11 => 'Uttar Pradesh (W)',
		12 => 'West Bengal',
		13 => 'Rajasthan',
		14 => 'Kerala',
		15 => 'Punjab',
		16 => 'Haryana',
		17 => 'Bihar & Jharkhand',
		18 => 'Orissa',
		19 => 'Assam',
		20 => 'North East',
		21 => 'Himachal Pradesh',
		22 => 'Jammu & Kashmir',
		23 => 'Chennai',
	];

	public static function allPrepaid()
	{
		return self::$operators_prepaid;
	}

	public static function allPostpaid()
	{
		return self::$operators_postpaid;
	}

	public static function allDth()
	{
		return self::$operators_dth;
	}

	public static function allCircles()
	{
		return self::$operators_circles;
	}

	public static function getPrepaidByCode($code)
	{
		if (self::isPrepaidExist($code)) {
			return "";
		}

		return self::$operators_prepaid[$code];
	}

	public static function getPostpaidByCode($code)
	{
		if (self::isPostpaidExist($code)) {
			return "";
		}

		return self::$operators_postpaid[$code];
	}

	public static function getDthByCode($code)
	{
		if (self::isDthExist($code)) {
			return "";
		}

		return self::$operators_dth[$code];
	}

	public static function isPrepaidExist($code)
	{
		return array_key_exists($code, self::$operators_prepaid);
	}

	public static function isPostpaidExist($code)
	{
		return array_key_exists($code, self::$operators_postpaid);
	}

	public static function isDthExist($code)
	{
		return array_key_exists($code, self::$operators_dth);
	}

	/**
	 * Gets operator number code
	 *
	 * @param $code string
	 * @return int  on success valid operator id, on failure 0
	 */
	public static function getPrepaidOperatorId($code)
	{
		if (!self::isPrepaidExist($code)) {
			return 0;	/* None */
		}

		return self::$operators_mobile_id[$code];
	}

	/**
	 * Gets DTH operator number code
	 *
	 * @param $code string
	 * @return int  on success valid operator id, on failure 0
	 */
	public static function getDthOperatorId($code)
	{
		if (self::isDthExist($code)) {
			return 0;	/* None */
		}

		return self::$operators_dth_id[$code];
	}

	public static function getCircle($code)
	{
		if (!array_key_exists($code, self::$operators_circles)) {
			return "";
		}

		return self::$operators_circles[$code];
	}
}
