<?php

namespace Components\Shop;

use Components\Shop\Contracts\CouponInterface;

/**
 * NullCoupon class
 */
class NullCoupon implements CouponInterface
{
	public function discount()
	{
		return 0;
	}
}