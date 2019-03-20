<?php

namespace Components\Shop\Contracts;

interface ProductInterface
{
	/**
	 * Returns current quantity in cart for this product
	 *
	 * @return int  quantity(ies) of this product
	 */
	public function quantity();

	/**
	 * Returns price of this product (for 1 pc)
	 *
	 * @return float  price of this product
	 */
	public function price();

	/**
	 * Returns discount of this product (for 1 pc)
	 *
	 * @return float  discount of this product
	 */
	public function discount();

	/**
	 * Returns price after discount of this product (for 1 pc)
	 *
	 * @return float  discounted price
	 */
	public function discountedPrice();
}