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

* Add relationship feature in Model class
	-- 

* Add AndWhere and OrWhere functionality in Model. So that developer can select with more than one where condition.
	-- done

* Add Session::exists() method
	-- done

* Add update functionality on getting data using where() method also as I did in find() method.
	--

* Remove throw Exception code from Validator class
  When a required field is not present in the $\_POST variable, Validator class throws exception. instead of throwing the error just set the error in error variable stating the required filed is missing.
  -- 

* Add the following feature to Model class
	1. Fetching data from db using ORDER BY
	1. Add PAGINATE method to fetch record by LIMIT AND OFFSET

	--

* Rethink on the design of Method
	1. What each method should return?
	1. Each method should return data / object according to function that mathod carries
