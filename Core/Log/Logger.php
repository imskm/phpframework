<?php

namespace Core\Log;

use Core\Log\LoggerInterface;

/**
 * Logger Class
 */
class Logger implements LoggerInterface
{
	/**
	 * @var $storage_path string
	 */
	protected $storage_path;

	public function __construct($storage_path)
	{
		$this->storage_path = $storage_path;
	}

	public function emergency($message)
	{

	}

	public function alert($message)
	{

	}

	public function critical($message)
	{

	}
	public function error($message)
	{

	}
	public function warning($message)
	{

	}
	public function notice($message)
	{

	}
	public function info($message)
	{

	}
	public function debug($message)
	{

	}
}