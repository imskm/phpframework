<?php

namespace Components\Shop;

use Components\Storages\SessionStorage;

/**
 * CartBag class
 * Manages cart for short time (temporary)
 */
class CartBag
{
	private $storage_name = 'shopping_cart';
	private $storage;

	public function __construct()
	{
		$this->storage = new SessionStorage($this->storage_name);
	}

	public function get($key)
	{
		return $this->storage->get($key);
	}

	public function add($key, array $value)
	{
		$this->storage->set($key, $value);
	}

	public function remove($key)
	{
		$this->storage->unset($key);
	}

	public function update($key, $value)
	{
		$this->storage->set($key, $value);
	}

	public function itemExists($key)
	{
		return $this->storage->exists($key);
	}

	public function all()
	{
		return $this->storage->all();
	}

	public function destroy()
	{
		$this->storage->destroy();
	}

	public function refresh()
	{
		// 
	}
}