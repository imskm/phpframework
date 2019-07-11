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

	public static function __callStatic($name, $args)
	{
		if (!is_callable([self::$logger, $name])) {
			throw new \Exception(
				"Method $name is not callable from outside of object "
				. get_class(self::$logger));
		}

		return self::getLoggerInstance()->{$name}($args[0]);
	}
}