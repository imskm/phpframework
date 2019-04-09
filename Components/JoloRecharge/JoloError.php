<?php

namespace Components\JoloRecharge;

/**
 * JoloError class
 * Contains Jolo Recharge errors and error description, error can be fetched
 */
class JoloError
{
	/**
	 * Jolo recharge API error codes and their description
	 *
	 */
	private static $errors = [
		1    =>  "User ID / app key missing",
		2    =>  "Operator code missing",
		3    =>  "Service code missing",
		4    =>  "Amount code missing",
		5    =>  "Client order id missing",
		6    =>  "Operator code is invalid",
		7    =>  "Amount not accepted",
		8    =>  "Amount not accepted",
		9    =>  "Invalid amount",
		10   =>  "Invalid number",
		11   =>  "Invalid number",
		12   =>  "Invalid number",
		13   =>  "Amount not accepted",
		14   =>  "Amount not accepted",
		15   =>  "Amount not accepted",
		16   =>  "Amount not accepted",
		17   =>  "Invalid amount",
		18   =>  "Invalid number",
		19   =>  "Invalid number",
		20   =>  "No IP address linked",
		21   =>  "IP address not matched",
		22   =>  "Balance is less",
		23   =>  "Balance is less",
		24   =>  "Internal server error",
		25   =>  "Same number with same amount not allowed within 60 seconds",
		101  =>  "RECHARGE UNAVAILABLE",
		102  =>  "NO LONGER VALID RECHARGE",
		103  =>  "NOT ELIGIBLE FOR 4G PLAN",
		105  =>  "Recharge disabled on this operator temporarly",
		106  =>  "Operator not available temporarly",
		109  =>  "Transaction already in progress at operator end",
		112  =>  "Repeated recharge not allowed within 20 mins",
		113  =>  "Operator-provider internal error",
		129  =>  "Operator network error",
		131  =>  "Operator Code is unavailable",
		142  =>  "Operator-provider internal error",
		152  =>  "Amount not accepted",
		153  =>  "Amount not accepted",
		154  =>  "Invalid number",
		155  =>  "Retry after 5 minutes",
		162  =>  "Customer exceeded per day attempts on this number",
		163  =>  "Amount not accepted",
		164  =>  "This customer number is barred",
		165  =>  "Amount not accepted",
		166  =>  "Invalid number",
		167  =>  "Multiple transaction error from operator on this number",
		168  =>  "Temporary operator end error",
		169  =>  "Temporary operator end error",
		170  =>  "Temporary operator end error",
		171  =>  "Temporary operator end error",
		172  =>  "Operator is down at this moment",
		241  =>  "Request parameters are incomplete",
		246  =>  "Operator-provider internal error",
		247  =>  "Invalid service provider",
		248  =>  "Duplicate transaction ID",
		249  =>  "Try after 15 minutes",
		250  =>  "Invalid account number",
		251  =>  "Invalid amount",
		252  =>  "Denomination temporarily barred",
		253  =>  "Refill barred temporarily",
		254  =>  "Service provider error",
		255  =>  "Service provider downtime",
		262  =>  "System Error",
		300  =>  "Operator code not valid for your account",
		360  =>  "Unknown error",
		701  =>  "KYC not submitted by user",
		
		// This is custom error set by me. It is not Jolo's error code.
		// If jolo transaction orderid is archieved or wrong orderid
		// is given for status check or dispute report then Jolo will
		// return this error message in single line string.
		// So JoloRecharge converts this to custom error code. This unifies
		// this JoloRecharge package to handle this error as other errors
		// This is the only single case where Jolo responses with this single
		// line string and not json string
		999  =>  "Invalid orderid or transaction is archived",
	];

	public static function all()
	{
		return self::$errors;
	}

	public static function getByCode($code)
	{
		if (!self::isExist($code)) {
			return "";
		}

		return self::$errors[$code];
	}

	public static function isExist($code)
	{
		if (!array_key_exists($code, self::$errors)) {
			return false;
		}

		return true;
	}
}