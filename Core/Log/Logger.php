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
		$this->writeLog(__FUNCTION__, $message);
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

	protected function writeLog($level, $message)
	{
		$fmt_message = $this->formatMessage($level, $message);

		$fh = fopen($this->storage_path, "a");


		fclose($fh);
	}

	public function formatMessage($level, $message)
	{
		$debug_info 	= debug_backtrace();
		$debug_info 	= $debug_info[count($debug_info) - 1];
		$severity 		= $level;
		$timestamp 		= date("Y-m-d\TH:i:s");
		$machine 		= "-";
		$app_name 		= "-";
		$file_name 		= $debug_info['file'];
		$line_no 		= $debug_info['line'];
		$proc_id 		= "-";
		$msg_id 		= "-";
		$msg 			= $message;

		$fmt_message = "<$severity> $timestamp $machine $file_name($line_no) $msg";

		return $fmt_message;
	}
}