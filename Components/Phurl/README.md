# Phurl API usage

**Phurl ver 1.0**
Phurl, a cURL based PHP HTTP client, made for making API calls from php code.
Very useful for rapidly building new interesting API based mobile recharge, money transfer, etc Apps.

## Import Phurl class
```php
use Phurl\Phurl;

// Or prefix namespace if Phurl is inside any other direcotry
// but you have to change the namespace in the Phurl code too
use Components\Phurl\Phurl;
```

## Phurl Instantiation
```php
$phurl = new Phurl($is_dev = true);

// $is_dev true if you are in development mode
//   shows verbose output on STDERR useful in debugging
// false by default for production. No output on STDERR
```

## Phurl GET request with no params
```php
$phurl = new Phurl($is_dev = true);
$phurl->get($url = 'https://jsonplaceholder.typicode.com/posts/1');
```

## Phurl GET request with params
```php
$phurl = new Phurl($is_dev = true);

// Send params as key value pair (associative array)
// Call withParams() methods before get() call
// Always call get() / post() / put() / delete() any HTTP method
// at last
// https://jsonplaceholder.typicode.com/posts?userId=1
$get_result = $phurl->withParams([
		'userId'	=> 1,
	])->get($url = 'https://jsonplaceholder.typicode.com/posts');
```


## A full Phurl GET request with params demo
```php
$phurl = new Phurl(true);

// Set params and make get request to $url
$get_result = $phurl->withParams([
		'userId'	=> 1,
	])->get($url = 'https://jsonplaceholder.typicode.com/posts');

// $get_result false on curl execution fails
if ($get_result === false) {

	// 

	return false;
}

// Get response came back from server
$response = $phurl->getResponse();
```