<?php

namespace App\Controllers;

/**
 * Home Controller
 */
class Home extends \Core\Controller
{
	/**
	 * Show the index page
	 *
	 * @return void
	 */
	public function index()
	{
		//echo "<p>Hello from the index action in the Home controller.</p>";
		//echo "<pre>". htmlspecialchars(print_r($_GET, true)) ."</pre>";

		$this->view->render("Home/index.php", [
			"name" => "Mukhtar",
			"colours" => ["Red", "Green", "Yellow"]
		]);
	}
}
