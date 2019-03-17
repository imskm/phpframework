<?php

namespace Core\Support\Storages;

use \Core\Support\Storages\StorageInterface;

/**
 * SessionStorage class
 */
class SessionStorage implements StorageInterface
{
	/**
	 * @var $storage string  Session storage name
	 */
	protected $storage;

	/**
	 * Instantiate Class with storage name so that multiple storage
	 * can be created and managed
	 *
	 * @param $stroage string
	 */
	public function __construct($storage)
	{
		$this->checkStorageNameIsValid($storage);
		$this->storage = $storage;

		// If storage does not exist then Create empty session storage
		if (!isset($_SESSION[$this->storage])) {
			$_SESSION[$this->storage] = [];
		}
	}

	/**
	 * Gets value from storage by key
	 *
	 * @param $key  mixed
	 * @return mixed | null  if $key does not exist then null else value
	 */
	public function get($key)
	{
		if (!$this->exists($key)) {
			return null;
		}

		return $_SESSION[$this->storage][$key];
	}

	/**
	 * Sets value in storage by key
	 *
	 * @param $key  mixed
	 * @param $value  mixed
	 * @return void
	 */
	public function set($key, $value)
	{
		$_SESSION[$this->storage][$key] = $value;
	}

	/**
	 * Removes a key from the storage
	 *
	 * @param $key mixed  key that needs to be removed from the storage
	 * @return void
	 */
	public function unset($key)
	{
		if (!$this->exists($key)) {
			return;
		}

		unset($_SESSION[$this->storage][$key]);
	}

	/**
	 * Checks given key exists in the storage
	 *
	 * @param $key  mixed
	 * @return boolean  true if $key exists else false
	 */
	public function exists($key)
	{
		return isset($_SESSION[$this->storage][$key]);
	}

	/**
	 * Returns entire storage
	 *
	 * @param void
	 * @return array
	 */
	public function all()
	{
		return $_SESSION[$this->storage];
	}

	/**
	 * Destroys storage
	 *
	 * @return void
	 */
	public function destroy()
	{
		if (isset($_SESSION[$this->storage])) {
			unset($_SESSION[$this->storage]);
		}
	}

	protected function checkStorageNameIsValid($storage_name)
	{
		if (is_numeric($storage_name)) {
			throw new \Exception('Invalid stroage name "'. $storage_name .'". Only string allowed');
		}
	}

}