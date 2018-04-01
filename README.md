# phpframework
My own personal PHP MVC Framework

# Model Interface
--
User::all()->count();

$user = User::all();

$user = User::find(12);

$user = User::where("email", "muktar@gmail.com");

print_r($user->first());

echo '<br>Count : ' . $user->count();

## Saving model
$user = new User;
$user->name = "Test User";
$user->email = "test@gmail.com";
$user->password = password_hash("test", PASSWORD_DEFAULT);
$user->save();

## Accessing ID of Last saved model
$user->lastID();

## Updating existing record
$user = User::find(2);
$user->password = password_hash("waqar", PASSWORD_DEFAULT);
$user->name = "Waqar Azam";
$user->save();

## Deleting existing record
$user = User::find(2);
if ($user->count() && $user->delete()) {
	echo "DELETED 2";
} else {
	echo "FAILD";
}