<?php

namespace Components\Shop\Contracts;

use Components\Shop\Contracts\ProductInterface;

interface ProductsInterface
{
	public function addProduct(ProductInterface $product);
	public function totalPrice();
	public function totalDiscount();
	public function all();
}