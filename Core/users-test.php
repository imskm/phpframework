<?php
session_start();

//Error reporting
error_reporting(E_ALL);
ini_set("display_errors", 1);
/*************************************
* Including helpers.php file
* It has render function
* to render the header and footer
* file.
**************************************/
require_once(__DIR__ . '/../App/Models/UsersModel.php');

/*--------------------------------------------------
 | INSERT
 | Yes it worked! Yea!
 |__________________________________________________
 |
 | take care of updated_at field value

$user = new Users();

$user->first_name = "Jak";
$user->last_name = "Sparow";
$user->dob = "1989-06-13";
$user->gender = "1";
$user->address_1 = "Kankinara";
$user->address_2 = "";
$user->state_id = 2;
$user->pin = "743290";
$user->country_id = 1;
$user->aadhaar = "897465465461";
$user->email = "xxxx@xxxx.com";
$user->phone = "9331920045";
$user->photo = "/assets/img/user_avatar.jpg";
$user->married = 1;
$user->father_name = "xxxxxx";

$user->save();
*/

/*--------------------------------------------------
 | UPDATE
 | Yes it worked too! Yea!
 |__________________________________________________
 |
 | take care of updated_at field value
$user = Users::find(12);
// print_r($result);
$user->first_name = "Updated";
$user->save();

*/
