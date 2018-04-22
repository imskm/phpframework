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

##### __Querying with AND clause__
```php
$user = User::where('email', 'sadaf@gmail.com')
			->andWhere('password', '12345678');

// Getting all results
$result = $user->get();

// Getting first result
$result = $user->first();
```

# View Interfaces

##### __Using a template file__
This should be used inside the view file.
```php
$this->use("template/file/path", "title"=> "Contact page");
```

##### __Declaring section inside a view__
Inside the view file
```php
$this->section('content');
	<div class="sidebar">
	...
	</div>
$this->endSection();
```

##### __Fetching the section__
Fetching and injecting the section in template file.
Inside template file.
```php
$this->fetchSection('content');
```


# Middlewares
To use my built-in middlewares we have to hook that middleware in before() protected mehtod of controller class.

Examples

##### __Using Guest midlleware in Auth Controller class__

```php
class controller ...
{
	protected function register()
	{
	}

	...

	protected function before()
	{
		return (new \App\Middlewares\GuestMiddleware)();
	}
}
```

##### __Using Auth midlleware in Auth Controller class__

```php
class controller ...
{
	protected function register()
	{
	}

	...

	protected function before()
	{
		return (new \App\Middlewares\AuthMiddleware)();
	}
}
```

# Authentication Interfaces
__Authentication class has some pre-built method that can handle Authenticated action for currently logged in user. They are :__

__Namespace of the class is App\Support\Authentication__

##### __Attempting login for the given login credential__
```php
Auth::attempt('email', 'password');
```

##### __Checking user is authenticated or not__
```php
Auth::check();
```

##### __Getting user_id of currently authenticated user__
```php
Auth::userId();
```

##### __Getting user info from database__
```php
Auth::user();
```

##### __Logout currently authenticated user__
```php
Auth::logout();
```
