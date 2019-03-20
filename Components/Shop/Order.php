<?php

namespace Components\Shop;

use Components\Shop\NullCoupon;
use Components\Shop\Contracts\CouponInterface;
use Components\Shop\Contracts\ProductsInterface;

/**
 * Order class
 */
class Order
{
	private $products;
	private $coupon;
	private $charges = [];

	public function __construct(ProductsInterface $products)
	{
		$this->products = $products;
		$this->coupon = new NullCoupon;
	}

	/**
	 * Calculates gross total of the order
	 * gross total = sum of product price (not discounted) on whole
	 *
	 * @return float
	 */
	public function grossTotal()
	{
		return $this->products->totalPrice();
	}

	/**
	 * Calculates sub total (order_amount - discount) of the order
	 *
	 * @return float  sub total
	 */
	public function subTotal()
	{
		return $this->grossTotal() - $this->discount();
	}

	/**
	 * Calculates net total (subtotal + charges - coupon_discount)
	 *
	 * @return float  net total
	 */
	public function total()
	{
		return $this->subTotal() + $this->charges() - $this->coupon->discount();
	}

	public function discount()
	{
		return $this->products->totalDiscount();
	}

	/**
	 * Gets coupon discount
	 *
	 * @return float  discount amount
	 */
	public function couponDiscount()
	{
		return $this->coupon->discount();
	}

	/**
	 * Calculates discount (discount + CouponDiscount + Others)
	 * 
	 * @return float  total discount on this order
	 */
	public function totalDiscount()
	{
		$this->discount() + $this->coupon->discount();
	}

	/**
	 * Apply a coupon to this order
	 *
	 * @return void
	 */
	public function applyCoupon(CouponInterface $coupon)
	{
		$this->coupon = $coupon;
	}

	/**
	 * adding any additional charges like tax
	 *
	 * @return void
	 */
	public function addCharge($charge_key, $charge, $charge_name = "")
	{
		$this->charges[$charge_key] = [
			'charge' 		=> $charge,
			'charge_name' 	=> $charge_name
		];
	}

	/**
	 * Gets single charge by charge key
	 *
	 * @return array
	 */
	public function getCharge($charge_key)
	{
		return $this->isChargeExists($charge_key)?
				$this->charges[$charge_key] :
				[];
	}

	/**
	 * Checks given charge key exists or not
	 *
	 * @param $charge_key string  an identifier for charge
	 * @return boolean  true if charge_key exist else false
	 */
	public function isChargeExists($charge_key)
	{
		return isset($this->charges[$charge_key]);
	}


	/**
	 * Calculates all charges added by addCharge method
	 *
	 * @return float  sum of charges
	 */
	public function charges()
	{
		$charges = 0;
		foreach ($this->charges as $charge) {
			$charges += $charge['charge'];
		}

		return $charges;
	}

	public function hasCoupon()
	{
		return !($this->coupon instanceof NullCoupon);
	}
}