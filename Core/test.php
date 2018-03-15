<?php

namespace Development;
//Error reporting
error_reporting(E_ALL);
ini_set("display_errors", 1);

class Crud
{
	public $table = "";

	function __construct()
	{
		# code...
	}

	public function save($value='')
	{
		echo "$value";
		echo "From object - " . get_called_class();
	}

	public static function find($id)
	{
		// $this->save("called from this instance.");	// Fatal error: Using $this when not in object context

		// echo "Id = " . $id;
		// echo $this->$primary;
		// echo $this->primary;
		// echo get_called_class();	// methods called statically dont have class ??
		// echo "<br>" . get_class();
		// $called_class = explode("\\", get_called_class());
		$called_class = get_called_class();

		// echo "Called class : "; print_r($called_class);
		$stdObj = new \stdClass;
		$stdObj->name = "Rony";
		$stdObj->age = 21;
		// echo "<br>Class variables: <br>";
		// var_dump(get_object_vars($stdObj));
		$dynamic_vars = get_object_vars($stdObj);

		$obj = new $called_class;
		$obj->test = "testProperty";
		// Dynamically creating new properties
		foreach ($dynamic_vars as $key => $value) {
			$obj->$key = $value;
		}

		// $obj = get_class_vars($stdObj);
		return $obj;
	}
}

/**
 * extends Crud
 */
class Model extends Crud
{
	protected $primary = 1;

}


// $crud = new Crud;
// $crud->save("Testing get_called_class from object <br>");
// echo "<br>";
// $objModel = new Model;
// $objModel->save("Testing get_called_class from inherited object <br>");

$model = Model::find(6);

// echo "New object : " . $returned->primary;
// echo "<br>";
// $newRet = $returned->find(8);
// echo "<br>";
// echo "Crud->save() : " . $returned->save("Called from new object ");
echo "<br><br><br><br>";
var_dump($model);
echo "<br><br><br><br>";
echo $model->name;
// var_dump($returned2);
