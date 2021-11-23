<?php
/**
 *  This file contains some sample code.
 *  It describes how you can interact with the API
 */
require_once 'NScAPIWrapper.php';

$objAPI = new NScAPIWrapper("YOUR PRIVATE KEY", "YOUR USER ID", "YOUR WEBSTORE URL", "YOUR SYNC USERNAME", "YOUR SYNC PASSWORD");


echo $objAPI->getCustomerDetails("you@domain.com").PHP_EOL;

exit;
$arrParameters = array(
    'email' => 'you@yourself.com',
    'ship-firstname' => 'Jane',
    'ship-lastname' => 'Jane',
    'ship-fullname' => 'Jane Brook',
    'ship-company' => '',
    'ship-address1' => '36 Bridge St Row',
    'ship-address2' => '',
    'ship-city' => 'Chester',
    'ship-zippostcode' => 'CH1 1NN',
    'ship-state' => 'Cheshire',
    'ship-statecode' => 'UK',
    'ship-country' => 'United Kingdom',
    'ship-countrycode' => 'UK',
    'ship-telephone' => '01244400318',
    'ship-fax' => '01789204015',
    'ship-email' => 'you@yourself.net',
    'products' => 'JWW003:3,JWW002:1,JWC001:1',
    'time' => time(),
    'userid' => $objAPI->getUserID()
);


// Uncomment one of the following lines to test different functions.

//$getShippingOptionsArgs = "email=franclin.foping@nitrosell.net&ship-firstname=Jane&ship-lastname=Jane&ship-fullname=Jane+Brooks&ship-company=&ship-address1=36+Bridge+St+Row&ship-address2=&ship-city=Chester&ship-zippostcode=CH1+1NN&ship-state=Cheshire&ship-statecode=UK&ship-country=United+Kingdom&ship-countrycode=UK&ship-telephone=01244400318&ship-fax=01789204015&ship-email=franclin.foping%40nitrosell.net&products=JWW003%3A3%2CJWW002%3A1%2CJWC001%3A1&time=".time()."&userid=".$objAPI->getUserID();

//echo $objAPI->getShippingOptions($getShippingOptionsArgs).PHP_EOL;

//echo $objAPI->getShippingAndTax($getShippingOptionsArgs).PHP_EOL;

//echo $objAPI->getShippingAndTax(http_build_query($arrParameters)).PHP_EOL;


//$orderParameters = "email=franclin.foping@nitrosell.net&cust-username=tester&cust-firstname=Test&cust-lastname=Testing&cust-address1=10+White+Friars&cust-city=Manchester&cust-country=United+Kingdom&cust-zippostcode=M1+3EU&ship-firstname=Jane&ship-lastname=Jane&ship-fullname=Jane+Brooks&ship-company=&ship-address1=36+Bridge+St+Row&ship-address2=&ship-city=Chester&ship-zippostcode=CH1+1NN&ship-state=Cheshire&ship-statecode=UK&ship-country=United+Kingdom&ship-countrycode=UK&ship-telephone=01244400318&ship-fax=01789204015&ship-email=franclin.foping%40nitrosell.net&products=JWW003%3A3%2CJWW002%3A1%2CJWC001%3A1&time=".  time()."&shippingid=163&tenderid=10&userid=".$objAPI->getUserID();

$arrOrderParameters = array(
    'email' => 'you@nitrosell.com',
    'cust-username' => 'tester',
    'cust-lastname' => 'Testing',
    'cust-address1' => '10 White Friars',
    'cust-city' => 'Manchester',
    'cust-country' => 'United Kingdom',
    'cust-zippostcode' => 'M1 3EU',
    'ship-firstname' => 'Jane',
    'ship-lastname' => 'Jane',
    'ship-fullname' => 'Jane Brook',
    'ship-company' => '',
    'ship-address1' => '36 Bridge St Row',
    'ship-address2' => '',
    'ship-city' => 'Chester',
    'ship-zippostcode' => 'CH1 1NN',
    'ship-state' => 'Cheshire',
    'ship-statecode' => 'UK',
    'ship-country' => 'United Kingdom',
    'ship-countrycode' => 'UK',
    'ship-telephone' => '01244400318',
    'ship-fax' => '01789204015',
    'ship-email' => 'you@nitrosell.com',
    'products' => 'JWW003:3,JWW002:1,JWC001:1',
    'time' => time(),
    'shippingid' => '163',
    'tenderid' => '10',
    'userid' => $objAPI->getUserID()
);

//echo $objAPI->insertWebOrders(http_build_query($arrOrderParameters)).PHP_EOL;

// We update a given product with new stock, code, name, and price.
$arrOrderParameters = array(
  array(
    "product_id" => 1, // please ensure that this product ID corresponds to the actual product to be updated
    "product_stock" => 100,
    "product_code" => "New SKU",
    "product_name" => "New prod name",
    "product_price" => 10,
  )
);

// uncomment the line below to test products updating
//echo $objAPI->updateProducts(http_build_query($arrOrderParameters)).PHP_EOL;

?>
