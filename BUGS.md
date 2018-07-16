## Changes made

* Core\View.php			: Added base template rendering support
  Usage :
  	define constanat template __BASE_TEMPLATE__ that you want to use as base template for the current view in the view page that you want to render out to the browser.
	example : define("__BASE_TEMPLATE__", "dir/base_template_file");



## TODO : FRAMEWORK
	1. Write extend($templatename, $templatepath) function that will define the constant for base template to include. Instead of using define() function for
	2. Create a helper file that will contain helper functions such as :
		path_for(sting) : creates url




## C PROGRAM FOR CREATING WEBAPP PAGE
	It will be a support hand for me in the Developement of Webapp.

	* Controller creator

## BACK SLASH IN PATH_FOR() FUNCTION
	* Back slash in string treats as escape character

## CURRENT VIEW CLASS DOES NOT SUPPORT OBJECT ARGUMENT
	* Make the View Class versatile so that it can pass agrs as array, object, object array, and normal singleton variable

## FINDBY METHOD IN MODEL CLASS
	* FindBy() method does not support array of objects

## ACTION FILTER HAS SECURITY ISSUE
	* When a request to route user/profile is made, this request should go through before() method and it does because the action method has Action suffix to it. But any one can add this suffix in the url while requesting for that route and he will bypass the before() methods security checks
	example
		uri : user/profile			--> failed to call 		--> before() called		--> User::profileAction			--> OK
		uri : user/profile-action	--> User::prfileAction --> don't go through before() action filter				--> dangerous
	* FIX :
		don't add Action suffix to method
		INSTEAD define the action method as private
	
	* ISSUE :
		Don't define a function as protected method, it crashes the webserver. Don't know why.

## BUG IN THE VIEW CLASS
	* When no template and content is defined in the view page then no output is given to the browser.
	* FIX
		It should check for the available content if it is avialable in the content variable then it should output it to client no matter if the user defines a section or not. If content is present then output it.
		
		Some time user may test his view without using any template and section, he can simple add the entire html in a single view and want to take a look on the page.

## TRYING TO ACCESS UNDEFINED PROPERTY XYZ EXCEPTION
	* This Exception happens when a property is having a value of NULL then Update() mehtod of THE MOdel CLASS throws exception

## BUG FOUND IN VALIDATOR CLASS
	* if(preg_match($pattern, $this->validateValue && $this->size(10))) {
	* at line number 241
	* Required method does not work properly. It does not detects empty fields. It just checks the presence of the field.
