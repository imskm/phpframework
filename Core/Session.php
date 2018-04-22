<?php

namespace Core;

/**
 * Session class : Handles session
 */
class Session
{

	public static function setFlash($key, $message)
	{
		$_SESSION["FLASH_MSG"][$key] = $message;
	}

	public static function hasFlash($key)
	{
		return isset($_SESSION["FLASH_MSG"][$key]);
	}

	public static function flash($key)
	{
		if( ! isset($_SESSION["FLASH_MSG"][$key]) ) {
			throw new \Exception("Flash Key : $key not found");
		}

		$message = $_SESSION["FLASH_MSG"][$key];
		unset($_SESSION["FLASH_MSG"][$key]);
		return $message;
	}

	public static function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	public static function get($key)
	{
		return $_SESSION[$key] ? $_SESSION[$key] : null;
	}

	public static function delete($key)
	{
		if( isset($_SESSION[$key]) ) {
			unset($_SESSION[$key]);
		}
	}

	public static function destroy()
	{
		session_destroy();
	}

	public static function exist($key)
	{
		return isset($_SESSION[$key]);
	}
}
