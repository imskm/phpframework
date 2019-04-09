<?php

namespace Components\JoloRecharge;

/**
 * JoloRechargeOperator class
 * Made easy to fetch Operator using this class, check valid operator
 */
class JoloRechargeOperator
{
	private static $operators = [
		/* ----------------------------------------------------------
		 * Prepaid mobile recharge operators
		 * ----------------------------------------------------------
		 */
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

		/* ----------------------------------------------------------
		 * Postpaid mobile recharge operators
		 * ----------------------------------------------------------
		 */
		'APOS'	=>	'Airtel',
		'BPOS'	=>	'BSNL',
		'IPOS'	=>	'Idea',
		'JPOS'	=>	'Jio',
		'VPOS'	=>	'Vodafone',
		'DGPOS'	=>	'Tata Docomo GSM',

		/* ----------------------------------------------------------
		 * DTH recharge operators
		 * ----------------------------------------------------------
		 */
		'VT'	=>	'Videocon D2H',
		'SD'	=>	'Sun DTH',
		'BT'	=>	'Reliance Big TV DTH',
		'TS'	=>	'Tata Sky DTH',
		'DT'	=>	'Dish DTH',
		'AD'	=>	'Airtel DTH',

		/* ----------------------------------------------------------
		 * Landline and Broadband bill payment not implemented
		 * ----------------------------------------------------------
		 */
	];

	public static function all()
	{
		return self::$operators;
	}

	public static function getByCode($code)
	{
		if (self::isExists($code)) {
			return "";
		}

		return self::$operators[$code];
	}

	public static function isExists($code)
	{
		return array_key_exists($code, self::$operators);
	}

	
}