<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Post;

/**
 * Posts Controller
 *
 * PHP version 5.4
 */

class Posts extends \Core\Controller
{

	/**
	 * Show Index page
	 *
	 * @return void
	 */
	public function index()
	{
		$posts = Post::getAll();

		View::render("Posts/index.php", [
			"posts"	=> $posts
		]);

		Post::paginate(5);



		/*
		Post::update([
			"id_update" => 1,
			"title_update" => "Testing update method."
		],
		[
			"id" => 1
		]);
		*/
		echo "<br>";
	}

	/**
	 * Show the add new page
	 *
	 * @return void
	 */
	public function addNewAction()
	{
		echo "Hello from addNew action in the Posts controller";
		$post = new Post;
		// property name should match with columns of the table.
		$post->id = 6;
		$post->title = "Test Perform no. 6";
		$post->save();
	}

	/**
	 * Edit the existing post
	 *
	 * @return void
	 */
	public function editAction()
	{
		echo "<p>Hello from edit action in the Posts controller</p>";
		echo "<p>Route params:</p>";
		echo htmlspecialchars(print_r($this->route_params, true));
		echo "<br>" . $this->route_params["id"];
	}

	/**
	 * Delete record from the existing post
	 *
	 * @return void
	 */
	public function destroyAction()
	{
		echo "<p>Hello from destroy action in the Posts controller</p>";
		//echo "<p>Route params:</p>";
		//echo htmlspecialchars(print_r($this->route_params, true));

		$post = new Post;

		$post->id = $this->route_params["id"];
		$post->title = "Testing delete";

		$post->delete(["id" => $post->id]);
	}

	// Testing show posts by id.
	public function show()
	{
		$post = Post::find($this->route_params["id"]);
		echo $post->title . "<br>";
		//var_dump($post);
	}

	/**
	 * Action filter - calling before() method before action method
	 *
	 * @return void
	 */
	protected function before()
	{
		echo "(before) ";
		return true;
	}

	/**
	 * Action filter - calling before() method before action method
	 *
	 * @return void
	 */
	protected function after()
	{
		echo " (after)";
	}
}
