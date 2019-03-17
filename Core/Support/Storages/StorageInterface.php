<?php

namespace Core\Support\Storages;

interface StorageInterface
{
	/**
	 * Sets value in storage by key
	 *
	 * @param $key  mixed
	 * @param $value  mixed
	 * @return void
	 */
	public function set($key, $value);

	/**
	 * Gets value from storage by key
	 *
	 * @param $key  mixed
	 * @return mixed | null  if $key does not exist then null else value
	 */
	public function get($key);

	/**
	 * Removes a key from the storage
	 *
	 * @param $key mixed  key that needs to be removed from the storage
	 * @return void
	 */
	public function unset($key);

	/**
	 * Checks given key exists in the storage
	 *
	 * @param $key  mixed
	 * @return boolean  true if $key exists else false
	 */
	public function exists($key);

	/**
	 * Returns entire storage
	 *
	 * @param void
	 * @return array
	 */
	public function all();

	/**
	 * Destroys storage
	 *
	 * @return void
	 */
	public function destroy();
}