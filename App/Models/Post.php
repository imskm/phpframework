<?php

namespace App\Models;

use PDO;

/**
 * Post Model
 *
 * PHP version 5.4
 */
class Post extends \Core\Model
{
	protected $table = "posts";
	/**
	 * Get all the post from the database as an associative array
	 *
	 * @return array
	 */
	public static function getAll()
	{
		try {
			$db = static::getDB();
			$stmt = $db->query("SELECT * FROM posts ORDER BY id");
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $results;
		} catch (PDOException $e) {
			echo $e-getMessage();
		}

	}
}
