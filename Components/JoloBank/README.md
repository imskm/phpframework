# Jolo API integration
Jolo is an API provider for money transfer in bank and mobile recharge. This Jolo package allows easy integration of Jolo API and a very developer friendly API for rapid development of recharge system and money transfer.
API provider website https://jolosoft.com (for DMT), https://joloapi.com (for recharge)

## Dependencies
* Phurl package (developed by https://github.com/imskm)
* Phurl package depends on cURL

## Create JoloBank object
```php
// Import Phurl class
use Components\Phurl\Phurl;
use Components\JoloBank\JoloBank;


// Instantiate JoloBank instance
// $phurl Phurl object  Handles http request
// $api_key string  API key of jolosoft account
// $api_mode int  Mode 1 = Production, 0 = Development
$jolo_bank = new JoloBank(new Phurl, $api_key = "250xxxxxxxxx", $api_mode = 0);

```

## Create an Agent (required by Jolo API)
```php
// Import Phurl class
use Components\Phurl\Phurl;
use Components\JoloBank\JoloBank;

$jolo_bank = new JoloBank(new Phurl, $api_key = "250xxxxxxxxx", $api_mode = 0);

// Create an agent
// All the parameters are required and key must match as given in this example
// $result is boolean, true if agent created succefully else false
// Note: Address must be full address of agent else short address will be considered
//       as invalid by Jolo
$result = $jolo_bank->create(new JoloAgent([
	'phone' 	=> '9876543210',
	'name'		=> 'Super Man',
	'email' 	=> 'superman@gmail.com',
	'address' 	=> 'House no, Road, Locality, P.O.-xx, PIN-123456, State',
]));

// Check agent created
if ($result === true) {
	// agent created, so do what you want here (may be set message)
}

// Get response, came back from Jolo
$response = $jolo_bank->getResponse();

// Check if otp key has value 1 in $response object. If value is 1 that means an OTP
// is send to phone number of agent and agent must verify same for completing agent
// creation process. If value is 0 then OTP is not sent to agent and verification is
// not needed.
if ($response->otp === 1) {
	// do something to take otp from user using web interface and send it to agnet
	// verification Jolo api route
}
```

## Verify Agent if OTP value is 1 in Agent signup response
```php
// Import Phurl class
use Components\Phurl\Phurl;
use Components\JoloBank\JoloBank;

$jolo_bank = new JoloBank(new Phurl, $api_key = "250xxxxxxxxx", $api_mode = 0);

// Verify agent using OTP
// All the parameters are required and key must match as given in this example
// $result is boolean, true if agent verification was successful else false
// Note: OTP in test mode is always 1234
$result = $jolo_bank->verify('agent', [
	'phone' => '9876543210',
	'otp'   => '1234'
]);

// Check if verification was successful
if ($result === true) {
	// Agent verified
}

```

## Fetch details of registered Agent
```php
// Import Phurl class
use Components\Phurl\Phurl;
use Components\JoloBank\JoloBank;

$jolo_bank = new JoloBank(new Phurl, $api_key = "250xxxxxxxxx", $api_mode = 0);

// Fetch details of agent using agent's registered phone number
// All the parameters are required and key must match as given in this example
// $result is boolean, true if agent detail returned successfully else false
$result = $jolo_bank->detail('agent', [
	'phone'		=> '9876543210',
]);

// Check if fetch was successful
if ($result === true) {
	// Agent details fethed
}

// Get the details of agent came back as response
// $agent is stdClass agent. Every response is converted to stdClass object
// Read Jolo API doc for structure and properties of agent object
$agent = $jolo_bank->getResponse();

```

## Create a Beneficiary (required by Jolo API)
Beneficiary creation is necessary for transfering money to beneficiary account. Beneficiary is created under an Agent. An agent can have multiple beneficiary
```php
// Import Phurl class
use Components\Phurl\Phurl;
use Components\JoloBank\JoloBank;

$jolo_bank = new JoloBank(new Phurl, $api_key = "250xxxxxxxxx", $api_mode = 0);

// Create a beneficiary
// All the parameters are required and key must match as given in this example
// $result is boolean, true if beneficiary created succefully else false
$result = $jolo_bank->create(new JoloBeneficiary([
	'phone' 				=> '9876543210',	/* regd. agent phone number */
	'beneficiary_name'		=> 'Spider Man',
	'beneficiary_ifsc' 		=> '5454554',
	'beneficiary_account_no'=> '98464654654654',
]));

// Check beneficiary created
if ($result === true) {
	// beneficiary created, you need to store beneficiary id for latter
	// beneficiary id is needed for money transfer
	// Get response, came back from Jolo
	$response = $jolo_bank->getResponse();

	// Save the beneficiary id in database
	$user->beneficiarySave(					/* (some kind of saving call) */
		$response->beneficiaryid
	);
}


// Check if otp key has value 1 in $response object. If value is 1 that means
// an OTP is send to phone number of agent and agent must verify same for
// completing beneficiary creation process. If value is 0 then OTP is not sent
// to beneficiary and verification is not needed.
if ($response->otp === 1) {
	// do something to take otp from user using web interface and send it to beneficiary
	// verification Jolo api route
}
```

## Verify Beneficiary if OTP value is 1 in Beneficiary creation response
```php
// Import Phurl class
use Components\Phurl\Phurl;
use Components\JoloBank\JoloBank;

$jolo_bank = new JoloBank(new Phurl, $api_key = "250xxxxxxxxx", $api_mode = 0);

// Verify beneficiary using OTP
// All the parameters are required and key must match as given in this example
// $result is boolean, true if beneficiary verification was successful else false
// Note: OTP in test mode is always 1234
$result = $jolo_bank->verify('beneficiary', [
	'phone' 		=> '9876543210',
	'beneficiaryid' => '98464654654654_5454554',
	'otp'   		=> '1234'
]);

// Check if verification was successful
if ($result === true) {
	// beneficiary verified
}

```

## Fetch details of registered Beneficiary
```php
// Import Phurl class
use Components\Phurl\Phurl;
use Components\JoloBank\JoloBank;

$jolo_bank = new JoloBank(new Phurl, $api_key = "250xxxxxxxxx", $api_mode = 0);

// Fetch details of beneficiary using beneficiary's beneficiary id
// All the parameters are required and key must match as given in this example
// $result is boolean, true if beneficiary detail returned successfully else false
$result = $jolo_bank->detail('beneficiary', [
	'beneficiaryid'		=> '98464654654654_5454554',
]);

// Check if fetch was successful
if ($result === true) {
	// Beneficiary details fethed
}

// Get the details of beneficiary came back as response
// $beneficiary is stdClass beneficiary. Every response is converted to stdClass
// object Read Jolo API doc for structure and properties of beneficiary object
$beneficiary = $jolo_bank->getResponse();

```

## Create a Bank transfer
Transfer money from your Jolo Api balance to any bank a/c which is registered by agent as beneficiary. Only registered beneficiaries are allowed to make money transfer

```php
// Import Phurl class
use Components\Phurl\Phurl;
use Components\JoloBank\JoloBank;

$jolo_bank = new JoloBank(new Phurl, $api_key = "250xxxxxxxxx", $api_mode = 0);

// Create a money transfer to beneficiary's bank account
// All the parameters are required and key must match as given in this example
// $result is boolean, true if transfer was successful else false
$result = $jolo_bank->create(new JoloBankTransfer([
	'phone' 			=> '9876543210',		/* regd. agent phone number */
	'beneficiaryid'		=> '98464654654654_5454554',
	'orderid' 			=> '123',				/* set by my website */
	'amount'			=> 4000,				/* amount to transfer */
	'remarks'			=> 'new bank transfer test',
]));

// Check transfer created
if ($result === true) {
	// transfer created, you need to store transfer id for latter
	// transfer id is needed for transfer status check
	// Get response, came back from Jolo
	$response = $jolo_bank->getResponse();

	// Save the transfer id in database
	// Sample response structure, Read Jolo docs for detail response
	// stdClass Object
	// (
	//     [status] => SUCCESS
	//     [error] => 
	//     [service] => 9876543210
	//     [beneficiaryid] => 98464654654654_5454554
	//     [orderid] => 123
	//     [txid] => A201903261899962522
	//     [amount] => 4000
	//     [charged] => 4014.16
	//     [bankid] => 2839510454
	//     [balance] => 0.00
	//     [time] => March 26 2019 06:57:52 PM
	//     [desc] => Transfer completed successfully
	// )
	$user->transactinIdSave($response);	/* (some kind of saving call) */
}


```

## Verify a Bank transfer
You can verify a transaction was successful or not, by using this API call

```php
// Import Phurl class
use Components\Phurl\Phurl;
use Components\JoloBank\JoloBank;

$jolo_bank = new JoloBank(new Phurl, $api_key = "250xxxxxxxxx", $api_mode = 0);

// Verify a money transfer
// All the parameters are required and key must match as given in this example
// $result is boolean, true if verification was successful without any error
// else false
$result = $jolo_bank->verify('transfer', [
	'txn'	=> 'A2019031056546555',	/* transaction # came as res */
]);

// Check verification of transfer
if ($result === true) {
	// transfer verified
}


```

## Check Jolo API balane
Fetch your Jolo API account balance. It always shows funds left in your Jolo API account.

```php
// Import Phurl class
use Components\Phurl\Phurl;
use Components\JoloBank\JoloBank;

$jolo_bank = new JoloBank(new Phurl, $api_key = "250xxxxxxxxx", $api_mode = 0);

// Check Jolo API balance
// For this API call no parameter is required. The only parameter is api_key
// which is sent by JoloBank class, you don't have to worry about it
// $result is boolean, true if check was successful without any error
// else false
$result = $jolo_bank->check('balance');

// Check verification of transfer
if ($result === true) {
	// transfer verified
}

// Get response, came back from Jolo, response contains balance left in your
// Jolo API account. Response structure is
// stdClass Object
// (
//     [status] => SUCCESS
//     [error] => 0
//     [balance] => 0.00
//     [time] => March 29 2019 05:26:18 PM
// )
$response = $jolo_bank->getResponse();

```

## Check correctness of bank account of a beneficiary
This JoloBank class API uses Jolo API to check given bank account is correct or not by transferring Rs. 1 bank account. Use it to check correctness of beneficiary bank account.
*Jolo charges Rs. 4.54 (as on 29-03-2019) for performing this operation*.

```php
// Import Phurl class
use Components\Phurl\Phurl;
use Components\JoloBank\JoloBank;

$jolo_bank = new JoloBank(new Phurl, $api_key = "250xxxxxxxxx", $api_mode = 0);

// Check bank account is correct or not along with IFSC code
// All the parameters are required and key must match as given in this example
// $result is boolean, true if check was successful without any error
// else false
$result = $jolo_bank->check('bank', [
	'phone' 			=> '9876543210',	/* regd. agent phone number */
	'beneficiary_account_no'=> '98464654654654_5454554',
	'beneficiary_ifsc' 	=> '5454554',				
	'orderid'			=> 123,				/* set by my website */
]);

// Check bank account check was successful
if ($result === true) {
	// bank account check completed successful
}

// Get response, came back from Jolo, response contains transfer details
//  with beneficiary bank account details
// stdClass Object
// (
//     [status] => SUCCESS
//     [error] => 
//     [service] => 9992223335
//     [actualname] => Test Test
//     [orderid] => 123
//     [txid] => A201903291714836324
//     [amount] => 1
//     [charged] => 4.54
//     [bankid] => TEST81241816000
//     [balance] => 0.00
//     [time] => March 29 2019 05:26:18 PM
//     [desc] => Bank account & ifsc is correct
//     [beneficiary_account_no] => 98464654654654_5454554
//     [beneficiary_ifsc] => 5454554
// )
$response = $jolo_bank->getResponse();

```

## An example code
This example code shows how you can integrate JoloBank package and use it to create Agent, create Beneficiary, check correctness of Beneficiary bank account and finally transferring money to beneficiary bank account.
*Ofcourse you will perform this task seperately in seperate page in real life application. I am just demonstrating it how it works together*.

```php
// Import Phurl class
use Components\Phurl\Phurl;
use Components\JoloBank\JoloBank;
use Components\JoloBank\Entities\JoloAgent;
use Components\JoloBank\Entities\JoloBeneficiary;
use Components\JoloBank\Entities\JoloBankTransfer;


// 1. Instantiate JoloBank instance
$jolo_bank = new JoloBank(new Phurl, $api_key = "2509865423156", $api_mode = 1);

// 2. Create an agent
$result = $jolo_bank->create(new JoloAgent([
	'phone' 	=> '9876543210',
	'name'		=> 'Super Man',
	'email' 	=> 'superman@gmail.com',
	'address' 	=> 'House no, Road, Locality, P.O.-xx, PIN-123456, State',
]));

// 3. Check if agent creation failed then log error and exit app / redirect
if ($result === false) {
	// log / set error message
	// redirect / exit app
}

// 4. If otp is 1 then redirect to otp verification page and perform further
// operation after this if check
// But for demonstration purpose let say otp is not sent and (verification is not required)
// The code for verication is:
// $result = $jolo_bank->verify('agent', [
// 	'phone' => '9876543210',
// 	'otp'   => '1234'
// ]);

// Check if verification was successful
// if ($result === true) {
// 	// Agent verified
// }


// 5. Create a beneficiary
$result = $jolo_bank->create(new JoloBeneficiary([
	'phone' 				=> '9876543210',	/* regd. agent phone number */
	'beneficiary_name'		=> 'Spider Man',
	'beneficiary_ifsc' 		=> '5454554',
	'beneficiary_account_no'=> '98464654654654',
]));

// 6. Check if beneficiary creation failed then log error and exit app / redirect
if ($result === false) {
	// log / set error message
	// redirect / exit app
}

$response = $jolo_bank->getResponse();
// Save the beneficiary id in database
// Entire sturcture of the beneficiary
// stdClass Object
// (
//     [status] => SUCCESS
//     [error] => 
//     [service] => 9992223335
//     [beneficiaryid] => 98464654654654_5454554
//     [time] => March 29 2019 05:26:16 PM
//     [desc] => OTP is sent on mobile 9992223335
//     [otp] => 1
// )
$user->beneficiarySave(					/* (some kind of database saving call) */
	$response->beneficiaryid
);

// 7. If otp is 1 then redirect to otp verification page and perform further
// operation after this if check
// But for demonstration purpose let say otp is not sent and (verification is not required)
// The code for verication is:
// $result = $jolo_bank->verify('beneficiary', [
// 	'phone' 		=> '9876543210',
// 	'beneficiaryid' => '98464654654654_5454554',
// 	'otp'   		=> '1234'
// ]);

// Check if verification was successful
// if ($result === true) {
// 	// Beneficiary verified
// }
// Note: It is mandatory to verify beneficiary if otp in response is 1. If not verified then
//       you can not use it to make money transfer


// 8. Create a money transfer to beneficiary's bank account
$result = $jolo_bank->create(new JoloBankTransfer([
	'phone' 			=> '9876543210',		/* regd. agent phone number */
	'beneficiaryid'		=> '98464654654654_5454554',
	'orderid' 			=> '123',				/* set by my website */
	'amount'			=> 3000,				/* amount to transfer */
	'remarks'			=> 'new bank transfer test',
]));

// Check transfer created
if ($result === true) {
	// transfer created, you need to store transfer id for latter
	// transfer id is needed for transfer status check
	// Get response, came back from Jolo
	$response = $jolo_bank->getResponse();

	// Save the transfer id in database
	// Sample response structure, Read Jolo docs for detail response
	// stdClass Object
	// (
	//     [status] => SUCCESS
	//     [error] => 
	//     [service] => 9876543210
	//     [beneficiaryid] => 98464654654654_5454554
	//     [orderid] => 123
	//     [txid] => A201903261899962522
	//     [amount] => 4000
	//     [charged] => 4014.16
	//     [bankid] => 2839510454
	//     [balance] => 0.00
	//     [time] => March 26 2019 06:57:52 PM
	//     [desc] => Transfer completed successfully
	// )
	$user->transactinIdSave($response);	/* (some kind of saving call) */
}

// DONE

```