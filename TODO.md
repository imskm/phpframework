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
	1. Each method should return data / object according to function that mathod carries

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




