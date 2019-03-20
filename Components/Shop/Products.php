<?php

namespace Components\Shop;

use Components\Shop\Contracts\ProductInterface;
use Components\Shop\Contracts\ProductsInterface;

/**
 * Products class
 */
class Products implements ProductsInterface
{
	private $products = [];

	/**
	 * Add product
	 *
	 * @return void
	 */
	public function addProduct(ProductInterface $product)
	{
		$this->products[] = $product;
	}

	/**
	 * Calculates total price
	 * Asumes price is for single unit returned by product obj
	 * 
	 * @return float  sum of prices
	 */
	public function totalPrice()
	{
		$total_price = 0;
		foreach ($this->products as $product) {
			$total_price += $product->price() * $product->quantity();
		}

		return $total_price;
	}

	/**
	 * Calculates total discount
	 * Asumes discount is for single unit returned by product obj
	 * 
	 * @return float  sum of discounts
	 */
	public function totalDiscount()
	{
		$total_discount = 0;
		foreach ($this->products as $product) {
			$total_discount += $product->discount()  * $product->quantity();
		}

		return $total_discount;
	}

	/**
	 * Returns all products that are added
	 *
	 * @return array  array of products
	 */
	public function all()
	{
		return $this->products;
	}

	/**
	 * Checks if any product is added
	 *
	 * @return boolean  true if any product exists else false
	 */
	public function hasAny()
	{
		return $this->products ? true : false;
	}
}