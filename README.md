# phpframework
My own personal PHP MVC Framework

# Model Interface
```php
User::all()->count();
$user = User::all();
$user = User::find(12);
$user = User::where("email", "muktar@gmail.com");
print_r($user->first());
echo 'Count : ' . $user->count();
```

##### __Saving model__
```php
$user = new User;
$user->name = "Test User";
$user->email = "test@gmail.com";
$user->password = password_hash("test", PASSWORD_DEFAULT);
$user->save();
```

##### __Accessing ID of Last saved model__
```php
$user->lastID();
```

##### __Updating existing record__
```php
$user = User::find(2);
$user->password = password_hash("waqar", PASSWORD_DEFAULT);
$user->name = "Waqar Azam";
$user->save();
```

##### __Deleting existing record__
```php
$user = User::find(2);
if ($user->count() && $user->delete()) {
	echo "DELETED 2";
} else {
	echo "FAILD";
}
```