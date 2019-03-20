<?php

namespace Components\Shop;

use Core\Session;
use Core\Database;

/**
 * CartValidation class
 * Validates operation of cart
 *   checks product exist in db before adding in cart
 *   checks qty is sufficient for adding new quantity
 */
class CartValidation
{
	public static function productExists($pid)
	{
		$product = Database::from('products')
					->select(['pid'])
					->where('pid', '=', $pid)
					->get();

		return $product ? true : false;
	}

	public static function updateQty($product_id, $qty)
	{
		/**
		 * Currently messages are stored directly in
		 * Session but in my framework use Validation class
		 */

		// If qty is given zero or negative then redirect
		if (!self::qtyIsValid($qty)) {
			Session::setFlash('error', 'Invalid quantity.');
			return false;
		}

		// If prduct does not exist in the cart then return false
		if (!self::isProductExistInCart($product_id)) {
			Session::setFlash('error', 'Product you are trying to update does not exist in cart.');
			return false;
		}

		// If insufficient stock then redirect with message
		if (!self::hasStock($product_id, $qty)) {
			Session::setFlash('error', 'Insufficient stock.');
			return false;
		}

		return true;
	}

	public static function qtyIsValid($qty)
	{
		return (int) $qty > 0 ? true : false;
	}

	public static function isProductExistInCart($product_id)
	{
		$cart = new CartBag;
		
		return $cart->get($product_id) ? true : false;
	}

	public static function hasStock($product_id, $qty)
	{
		$stock = Database::from("products")
					->select(["stock"])
					->where("pid", "=", $product_id)
					->get()
					->stock;

		return $stock >= (int) $qty ? true : false;
	}
}