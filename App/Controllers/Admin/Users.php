<?php

namespace App\Controllers\Admin;

/**
 * Users Controller
 */
class Users extends \Core\Controller
{

	/**
	 * Before filter
	 *
	 * @return boolean
	 */
	protected function before()
	{
		echo "(before) ";
		return true;
		// Make sure Admin user is logged in
		// Write authentication code here
	}

	/**
	 * Show index page of admin user
	 *
	 * @return void
	 */
	public function indexAction()
	{
		echo "User admin page from index action.";

		echo "<p>Route Parameters :</p>";
		echo "<pre>";
		echo htmlspecialchars(print_r($this->route_params, true));
		echo "</pre>";
	}


	/**
	 * After filter
	 *
	 * @return boolean
	 */
	protected function after()
	{
		// Execute code after executing method action
		echo " (after)";
	}
}
