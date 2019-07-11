<?php

namespace Core\Log;

use Core\Log\Logger;

/**
 * Log Wrapper class of Logger
 */
class Log
{
	const STORAGE_PATH = ROOT . "/storage/logs/app.log";

	/**
	 * @var $logger Core\Log\Logger
	 */
	protected static $logger = null;

	public static function getLoggerInstance()
	{
		if (is_null(self::$logger)) {
			self::$logger = new Logger(self::STORAGE_PATH);
		}

		return self::$logger;
	}
}