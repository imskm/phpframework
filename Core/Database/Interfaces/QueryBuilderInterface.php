<?php

namespace Core\Database\Interfaces;

/**
 * QueryBuilderInterface interface
 */
interface QueryBuilderInterface
{
	public function getLimitString(array $limit);
	public function getOrderByString(array $order);
	
}