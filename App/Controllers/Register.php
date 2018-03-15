<?php

namespace App\Controllers;

use \Core\View;


/**
* Register Controller
*/
class Register extends \Core\Controller
{
	public function index()
	{
		View::render("Register/index.php");
	}

	public function store()
	{
		
	}
}