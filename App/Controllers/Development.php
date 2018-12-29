<?php

namespace App\Controllers;

use \Core\View;
use \Tests\Test;
use \Core\Database;
use App\Models\Post;
use App\Models\User;
use \Core\Validator;


/**
 * Image Controller
 */
class Development extends \Core\Controller
{

	public function index()
	{
		View::render("Development/validate.php");
	}

	public function validate()
	{

		$validator = new Validator;


		// Validating optional fields
		$validator->validate($_SERVER["REQUEST_METHOD"], [
					"post_id" => "required|numeric|exist:posts,id",
					"user_message" => "optional|alpha_space",
					"user_name" => "required|alpha_space",
					"user_mail" => "required|email",
					"phone" => "required|phone",
					"password" => "required|min:8|max:30"
				]);



		// $validate->validate($_SERVER["REQUEST_METHOD"], ["user_name" => "required|alpha"]);
		// $validate->validate($_SERVER["REQUEST_METHOD"], ["user_mail" => "required|email"]);
		// $validate->validate($_SERVER["REQUEST_METHOD"], ["user_message" => "required"]);
		// $validate->validate($_SERVER["REQUEST_METHOD"], ["phone" => "required|phone"]);
		// $validate->validate($_SERVER["REQUEST_METHOD"], ["custno" => "required|numeric|max:10"]);
		// $validate->validate($_SERVER["REQUEST_METHOD"], ["confirm_password" => "confirmed:password"]);
		// $validate->validate($_SERVER["REQUEST_METHOD"], ["gender" => "required|in:1"]);
		// $validate->validate($_SERVER["REQUEST_METHOD"], ["post_id" => "required|numeric|exist:posts,id"]);


		var_dump($validator->errors);
		print_r($validator->validatedFields);

		// echo "<p>Done image scaling";
		// echo "<p>Hello from index action of Image controller.";
	}

	public function updateView()
	{
		View::render("Development/update.php");
	}
	public function update()
	{
		echo "Update action";
	}

	public function db()
	{
		$posts = Database::from("posts")->select(["id", "title", "content"])->orderBy("id", "desc")->limit(5)->get();
		// $sql = Database::from("post");
		// $sql = Database::get();
		// var_dump($posts);

		foreach ($posts as $post) {
			echo $post->id . " " . $post->title . " <----> " . $post->content . "<br>";
		}

	}

	public function paginate()
	{

		$posts = Post::paginate(2);

		foreach ($posts as $post) {
			echo $post->title;
			echo "<br>$post->content<br>";
		}

	}

	public function helpers()
	{
		// echo $_SERVER["SERVER_NAME"];
		echo path_for("/development/validate/");
	}

	public function test()
	{
		$test = new Test;
		$test->doTesting();
	}

	public function rawSqlTest()
	{
		// $sql = "SELECT * FROM users WHERE id = :id";
		$sql = "SELECT * FROM users WHERE name LIKE :name";
		$users = User::raw($sql, [
			// 'id' => 8,
			'name' => "%%%ES%",
		])->get();

		var_dump($users);
	}
}
