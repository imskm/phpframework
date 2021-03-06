* Add validation for date
	-- 

* Add Request class to handle request params
	-- 

* Required validation fails when input is set to 0 (zero)
	-- 

* If a route has url pattern like this controller/id/action then don't allow route controller/action to dispatch to this address. becase it may break the app
	-- 

* Model calss does not allow another model to be instantiated due to way the getInstance() method is written. For this reason we can not create two instance of two different models.
	Solution: change the getInstance() method to use associative of array of class and its instance. So the request for new class is made it first checks if the called class has already been initialized or not if so then return the instance otherwise create it store it then return it.
	-- 

* Develop Database class which will be capable of building all type of select query.
	-- 


* Add Session::exists() method
	-- done

* Remove throw Exception code from Validator class
  When a required field is not present in the $\_POST variable, Validator class throws exception. instead of throwing the error just set the error in error variable stating the required filed is missing.
  -- 

* Add the following feature to Model class
	1. Fetching data from db using ORDER BY
	1. Add PAGINATE method to fetch record by LIMIT AND OFFSET
	1. Fix the count() method of Model class
	1. Add update functionality on getting data using where() method also as I did in find() method.
	1. Add __RELATIONSHIP feature__ in Model class
	1. Add AndWhere and OrWhere functionality in Model. So that developer can select with more than one where condition.
	1. Aadd all the options that a select sql query carries in fetching records from db.
		all()->takeOld($number_of_items = 10)
		all()->takeNew($number_of_items = 10)



* Rethink on the design of Method
	1. What each method should return?
	1. Each method should return data / object according to function that method carries

* Add unset() mehtod in Base calss to allow user to unset a property in Base class
* Add fails() method in Validator class
* Remove BUG from validator class
			var_dump($this->rules);
			return ;

* Found a VERY SERIOUS BUG - Error.php class does not show error for FATAL ERROR when I was extending a class which was missing.

* In Validation class, unique() mehtod and other mehtods that need to access database uses old way to connect to database. Correct this.
* Remove line 74 from Error.php Core class and add this line (new View(VIEW_PATH))->render("$code.php"); as our View rendering class has changed.
* Add response json encoded data in View class
* Test Validator class's required method on input as 0. It emmit error

---
# New Requirements and Development of Framework (25.08.2018)
---
## Model
* Re-design the Model class in such a way that when a record is fetched by a User Model class then the db result must be the object of User Model class so that further operation imposed by model methods can be performed on the db result data. It is very much required for the easy CRUD operation. Currently the Core Model class does not has this feature and I did not thought of this problem, and I was doing the OOP totaly wrong way. Now I realised what I was doing wrong. So correct it ASAP.

## Routing
* Add strict routing system. If a route is setup with id param then don't dispatch the route to the respective controller if id param is missing.
* Routing : Variable routing does not work sometime. When the sequence of route add method is changed then it works. Sample code. Add admin route does not get dispatched thorws error. And when the sequence is changed (admin goes up and login goes down) it works.
```
	$router->add('login', ['controller' => 'Auth', 'action' => 'login', 'namespace' => 'Auth']);
	$router->add('auth/{action}', ['controller' => 'Auth', 'namespace' => 'Auth']);
	$router->add('{controller}', ['action' => 'index']);

	$router->add('admin', ['controller' => 'Home', 'action' => 'index', 'namespace' => 'Admin']);
	$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
	$router->add('admin/{controller}/{id:\d+}/{action}', ['namespace' => 'Admin']);
```










# BUGS

## Model Bugs
* On line 95, inapropriate checking of class existence, use isset() instead.
* first() methods blindly tries to return 0th element from the result set even when it is not present.

## Validator Bugs
* Validation which need to access database is using old Connection class change it to new Connector class
* Validation class is not working correctly, some of the issues are:
	- When a required rules is added with other rules then the validation sequence is not performed as expected. Example: "required|email", error says email is not valid instead of "email is required"
	- Validation of max, min rules and other can be optimized using isset(), check like isset(elem[size + 1]) then max boundary exceeded
	- required rule on non existing field causes exception which is not right for validation because of this other validation erros are destroyed
	- currently some rules like max, min, and size are static, meaning it only works for string type, It should work on other type also like int, float double
	- size rule should also work on file size checking
	- currently Validation class does not have any validation rules for file upload
	- Validation class does not have any good API to check for specific error on a field. This will help in showing and checking error on individual field

## Error and Exception Handler
* Current `Error and Exception handler` does not handle `PHP WARNING` and `PHP NOTICE`. So Improve your Error and Exception handler class. It must catch the `WARNING` and `NOTICE` as well and immidiately stop execution and show the fatal error.


