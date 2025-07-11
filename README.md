WebStoreAPI Documentation
=========================

The API enables Web developers to integrate with retailers' Webstores to allow order import and customer insert from third-party platforms. It can also be used to perform a number of operations that are documented below. The API consists of eight endpoints which are detailed below:


*     GetCustomerDetails: this call allows you to retrieve the details of a customer given its email address;
*     AddNewCustomer: this call entitles you to add a new customer to your Webstore;
*     GetAllTenders: this call allows you to get the list of available tenders for a given WebStore;
*     GetTendersByKeywords: this call allows to search for a particular tender given its keyword;
*     GetItem: this call allows you to retrieve a set of items given their characteristics. This could be their keywords, their department ID and so forth;
*     GetShippingOptions: this call allows you to query the list of possible shipping options of a given basket;
*     GetTaxForAnOrder: this call allows you to get the applicable amount of tax for a given basket;
*     GetShippingAndTaxForAnOrder: this call is an aggregation of the previous two endpoints; it returns the applicable tax and the shipping options for a given basket;
*     GetReviews: this call allows you to fetch all the users' reviews or a specific one;
*     GetBasketContent: this call allows you to get the content of the shopping cart given its cookie ID;
*     Ping: this call allows you to check the validity of your credentials;
*     InsertWebOrder: this call allows you to insert a given order into the WebStore.
*     GetWebOrders: this call retrieves the new orders from your stores and sets them to DOWNLOADED

### Authentication
In order to submit those API calls, you will need to pass our authentication process. There are two ways to do so:
* By sending the HMAC hash of the data that you are sending using your private key;
* Or, by leveraging the HTTP Basic Authentication mechanism. You will need to use your NSc Sync credentials for that.

### Quickstart
Best way to start is to call the GET ping.json to verify connection.

You need to have your NSc Webstore Credentials ready. 

These can be found in an email titled: `"NSc Sync WebStore credentials for <Your Webstore URL>"`.
            
For example:

- NSc Sync Webstore URL: maciej.demostore.nitrosell.com
- NSc Sync Username: maciejdemostor
- NSc Sync Password: XXXXXXXXXX
            
Next, you can base64 encode your username and password in terminal:
            
`echo -n "<NSc Sync Username>:<NSc Sync Password>" | base64`

For example:

`echo -n "maciejdemostor:XXXXXXXXXX" | base64`

Copy the output and replace it in the curl call below:

`curl -w '%{http_code}\n' -H 'Authorization: Basic <Base64 Encoded Credentials>' https://api.nitrosell.com/<Webstore URL>/v1/ping.json`

For example:

`curl -w '%{http_code}\n' -H 'Authorization: Basic bWFjaWVqZGVtb3N0b3I6WFhYWFhYWFhYWA==' https://api.nitrosell.com/maciej.demostore.nitrosell.com/v1/ping.json`

If the request is successfull, your response will be:

`{"message":"hello World","time":"Wed, 25 May 2022 17:38:43 +0000","data":[]}
200`

If your credentials are wrong, your response will be:

`{"error":"Unable to login"}401`

Once connection is verified, you can use the same format for working with other API calls.

PHP Example:

```
$curl      = curl_init();
$sUsername = "<NSc Sync Username>"; // i.e. "maciejdemostor"
$sPassword = "<NSc Sync Password>"; // i.e. "XXXXXXXXXX"
 
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.nitrosell.com/<NSc Sync Webstore URL>/v1/ping.json', // i.e. 'https://api.nitrosell.com/maciej.demostore.nitrosell.com/v1/ping.json'
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic ' . base64_encode(sprintf('%s:%s', $sUsername, $sPassword))
  ),
));
 
$response = curl_exec($curl);
curl_close($curl);
```

### Retrieve the details of a given customer

The following table summarises the list of parameters for this endpoint

Table 1: GetCustomerDetails Request Fields


| Field Name    | Data Type     | Usage | Description |
| ------------- |:-------------:| ----- |:-----------:|
| __hash__      | String        | Required | This is the hash of the request. This is an HMAC-MD5 hash computed using your private key. The hash is used for authentication purposes. Please contact NitroSell Support Team to get one.|
| __userid__    | String        | Required | This is your unique UserID obtained upon registration. Please contact NitroSell Support Team to request one.|
| __time__      | Integer       | Required | This is the timestamp of the request. This is used by the server to ensure that the request is not obsolete.|
| __email__     | String        | Required | This is the email address of the customer you are looking for.|


Available URL query parameters (all compulsory):


* email: the email address of the customer whose details are being requested
* hash: the hash of the request. This is computed with your private key
* userid: your user ID
* time: the timestamp of the request

##### Request

**GET**

/customer.json?email=annabel@myself.com&hash=214bc0c8e5e622000a68d41802a533d6&userid=50c2080e091f9&time=1354893907

##### Response

**HTTP /1.1 OK**

```javascript
{
"customer_id":2,
"customer_name":"",
"customer_username":"0000002",
"customer_address1":"36 Bridge St Row",
"customer_address2":"",
"customer_city":"Chester",
"customer_state":"Cheshire",
"customer_country":"United Kingdom",
"status":200,
"message":"200 OK",
"parameters":     {"email":"annabel@myself.com",
            "hash":"3d4e3e8f52cbc9795c27c9aa18218e06",
            "userid":"50c2080e091f9",
            "time":"1354893907"},
"request":"GET"
}
```

### Add a new customer to your Webstore

The next table illustrates the parameters that will need to included as part of the call

Table 2: **AddNewCustomer** Request fields

| Field Name    | Data Type     | Usage | Description |
| ------------- |:-------------:| ----- |:-----------:|
| __hash__      | String        | Required | Please refer to the description provided in Table 1|
| __userid__    | String        | Required | Please refer to the description provided in Table 1|
| __time__      | Integer       | Required | Please refer to the description provided in Table 1|
|__firstname__  | String        | Required | Your new customer's first name |
|__lastname__   | String        | Required | Your new customer's last name  |
|__company__    | String        | Optional | Your new customer's company |
|__email__      | String        | Required | Your new customer's email address|
|__address1__   | String        | Required | The street address of your new customer|
|__address2__   | String        | Optional | The second line of the address of your new customer|
|__city__       | String        | Required | The city associated to your new customer's address|
|__zippostcode__   | String        | Required | Your new customer's zip code|
|__country__   | String        | Required | The country associated to your new customer's address|
|__state__   | String        | Required | The state associated to your new customer's address|
|__phonenumber__   | Integer        | Required | Your new customer's phone number|
|__faxnumber__   | Integer        | Optional | Your new customer's fax number|
|__password__   | String        | Required | Your new customer's password|
|__mailinglist__   | Boolean        | Required | Whether you want your new customer to be registered to your mailing list|


* hash: the hash of the request. This is computed with your private key
* userid: your user ID
* time: the timestamp of the request

##### Request

**POST**

/customer.json


##### Response

**HTTP /1.1 OK**

```javascript
{
"customer_id":2,
"customer_name":"",
"customer_username":"0000002",
"customer_address1":"36 Bridge St Row",
"customer_address2":"",
"customer_city":"Chester",
"customer_state":"Cheshire",
"customer_country":"United Kingdom",
"status":200,
"message":"200 OK",
"parameters":     {"email":"annabel@myself.com",
            "hash":"3d4e3e8f52cbc9795c27c9aa18218e06",
            "userid":"50c2080e091f9",
            "time":"1354893907"},
"request":"POST"
}
```


### Retrieve the list of all supported tenders

The following table summarises the list of parameters for this endpoint

Table 3: **GetAllTenders** Request Fields

| Field Name    | Data Type     | Usage | Description |
| ------------- |:-------------:| ----- |:-----------:|
| __hash__      | String        | Required | Please refer to the description provided in Table 1|
| __userid__    | String        | Required | Please refer to the description provided in Table 1|
| __time__      | Integer       | Required | Please refer to the description provided in Table 1|

Available URL query parameters (all compulsory)


* hash: the hash of the request. This is computed with your private key
* userid: your user ID
* time: the timestamp of the request

##### Request

**GET**

/tender.json?hash=7e8101c83575e2ec5c2da94d1597fbc1&userid=50c2080e091f9&time=1354893907


##### Response

**HTTP /1.1 OK**

```javascript
{
"tender":[
        {
         "tender_id":"1",
         "tender_name":"Cash",
         "tender_type":"0",
         "tender_order":"0",
         "tender_desc":"",
         "tender_kind":"0"
        },
        {
        "tender_id":"2",
        "tender_name":"Credit Cards",
        "tender_type":"0",
        "tender_order":"0",
        "tender_desc":"",
        "tender_kind":"0"
        },
        {
        "tender_id":"3",
        "tender_name":"Gift Voucher",
        "tender_type":"0",
        "tender_order":"0",
        "tender_desc":"",
        "tender_kind":"0"
        },
        {
        "tender_id":"10",
        "tender_name":"Manual (Trusted Only)",
        "tender_type":"0",
        "tender_order":"0",
        "tender_desc":"",
        "tender_kind":"0"
        },
        {
        "tender_id":"8",
        "tender_name":"Web Order",
        "tender_type":"0",
        "tender_order":"0",
        "tender_desc":"",
        "tender_kind":"0"
        }
      ],
"numberOfTenders":5,
"message":"200 OK",
"parameters":{
          "hash":"7e8101c83575e2ec5c2da94d1597fbc1",
          "userid":"50c2080e091f9",
          "time":"1354893907"
         },
"request":"GET"
}
```


### Retrieve a tender by its keywords

The following table summarises the list of parameters for this endpoint

Table 4: **GetTendersByKeywords** Request Fields

| Field Name    | Data Type     | Usage | Description |
| ------------- |:-------------:| ----- |:-----------:|
| __hash__      | String        | Required | Please refer to the description provided in Table 1|
| __userid__    | String        | Required | Please refer to the description provided in Table 1|
| __time__      | Integer       | Required | Please refer to the description provided in Table 1|
|__keywords__   | String        | Required | This indicates the keyword of the tender. If you don’t provide one, all the tenders will be returned.|

Available URL query parameters (all compulsory)


* hash: the hash of the request. This is computed with your private key
* userid: your user ID
* time: the timestamp of the request
* keywords: the keywords of the tender to be fetched


##### Request

**GET**

/tender.json?keyword=Credit&hash=2683824d2af315d1022e2ea2dca21ee1&userid=50c2080e091f9&time=1354893907/


##### Response

**HTTP /1.1 OK**

```javascript
{
"tender":
[
    {
    "tender_id":2,
    "tender_name":"Credit Cards",
    "tender_type":0,
    "tender_order":0,
    "tender_desc":"",
    "tender_kind":0
    }
],
"numberOfTenders":1,
"message":"200 OK",
"parameters":
    {
    "keyword":"Credit",
    "hash":"2683824d2af315d1022e2ea2dca21ee1",
    "userid":"50c2080e091f9",
    "time":1354893907
    },
"request":"GET"
}
```

### Retrieve an item given its keywords

The following table summarises the list of parameters for this endpoint

Table 5: **GetItem** Request Fields

| Field Name    | Data Type     | Usage | Description |
| ------------- |:-------------:| ----- |:-----------:|
| __hash__      | String        | Required | Please refer to the description provided in Table 1|
| __userid__    | String        | Required | Please refer to the description provided in Table 1|
| __time__      | Integer       | Required | Please refer to the description provided in Table 1|
|__keyword__    | String        | Optional | Keyword to be used for the search|
| departmentid  | Integer      | Optional | This indicates the department ID of the items to be fetched |
| categoryid   | Integer      | Optional | This indicates the category ID of the items to be fetched |
| price   | Integer      | Optional | This indicates the price of the items to be fetched |
| sort      | Integer       | Optional | The current page of the dataset, default to 0, that is the first page|
| count      | Integer       | Optional | The number of records to be returned, default to the number of items shown per page on your store|
Available URL query parameters (all compulsory)


* hash: the hash of the request. This is computed with your private key
* userid: your user ID
* time: the timestamp of the request
* keywords: the keywords of the tender to be fetched

N.B: *the departmentid, categoryid and price can be appended with the following operators: gte standing for greater than, le standing for less than. A typical usage would be to specify for instance the list of items whose departmentid is less than 50. In that case, one could specify this as a parameter to the request: product_departmentid_lt = 50 (Note the use of an underscore between the operator and departmentid).* 

##### Request

**GET**

/products.json?keyword=tie&hash=2683824d2af315d1022e2ea2dca21ee1&userid=50c2080e091f9&time=1354893907/


##### Response

**HTTP /1.1 OK**

```javascript
{
"tender":
[
    {
    "tender_id":2,
    "tender_name":"Credit Cards",
    "tender_type":0,
    "tender_order":0,
    "tender_desc":"",
    "tender_kind":0
    }
],
"numberOfTenders":1,
"message":"200 OK",
"parameters":
    {
    "keyword":"Credit",
    "hash":"2683824d2af315d1022e2ea2dca21ee1",
    "userid":"50c2080e091f9",
    "time":1354893907
    },
"request":"GET"
}
```


### Retrieve the shipping options for a given order 

The following table summarises the list of parameters for this endpoint

Table 6: **GetShippingOptions** Request Fields

| Field Name    | Data Type     | Usage | Description |
| ------------- |:-------------:| ----- |:-----------:|
| __hash__      | String        | Required | Please refer to the description provided in Table 1|
| __userid__    | String        | Required | Please refer to the description provided in Table 1|
| __time__      | Integer       | Required | Please refer to the description provided in Table 1|
| __email__   | String        | Required | This is the email address of the customer you are looking for|
| __ship-firstname__ | String      | Required | This is the first name that appears on the shipping address |
| __ship-lastname__ | String      | Required | This is the last name that appears on the shipping address |
| __ship-fullname__ | String      | Required | This is the full name that appears on the shipping address |
| __ship-company__ | String      | Required | This is the company name that appears on the shipping address |
| __ship-address1__ | String      | Required | This is the street address that appears on the shipping address |
| __ship-address2__ | String      | Required | This is the second line that appears on the shipping address |
| __ship-city__ | String      | Required | This is the city that appears on the shipping address |
| __ship-country__ | String      | Required | This is the country name that appears on the shipping address |
| __ship-telephone__ | String      | Required | This is the phone number that appears on the shipping address |
| __ship-fax__ | String      | Required | This is the fax number that appears on the shipping address |
| __ship-email__ | String      | Required | This is the email address that appears on the shipping address |
| __products__ | String      | Required | This is a comma separated list of products in the basket. It is in the form: product1:qty, product2:qty |


Available URL query parameters (all compulsory)


* email: the email of the customer placing the order
* ship-firstname: the first name of the customer registered at the shipping address of the order
* ship-lastname: the last name of the customer registered at the shipping address of the order
* ship-fullname: the full name of the customer registered at the shipping address of the order
* ship-company: the company name of the customer registered at the shipping address of the order
* ship-address1: the street address of the customer registered at the shipping address of the order
* ship-address2: the street address of the customer registered at the shipping address of the order
* ship-city: the city of the customer registered at the shipping address of the order
* ship-country: the country of the customer registered at the shipping address of the order
* ship-telephone: the phone number of the customer registered at the shipping address of the order
* ship-fax: the fax number of the customer registered at the shipping address of the order
* ship-email: the email of the customer registered at the shipping address of the order
* products: the list of products currently in the basket. This is a comma-separated list in the form productcode1:qty, productcode2:qty, …, productcoden:qty
* time: this is the timestamp of the request
* userid: this is the ID of the user making the request
* hash: this is the hash code associated with the request

##### Request

**POST**

/shipping.json


##### Response

**HTTP /1.1 OK**

```javascript
{
"shippingOptions":
    {
    "181":
        {
        "shipping_name":"Standard UK - Standard UK",
        "shipping_integrationservicecode":"",
        "shipping_integrationcarriercode":"",
        "shipping_methodid":"1",
        "shipping_countryshippingid":"181",
        "shipping_charge":"0.00",
        "shipping_interpolate":"0"
        },
    "175":
        {
        "shipping_name":"Express UK - Express UK",
        "shipping_integrationservicecode":"",
        "shipping_integrationcarriercode":"",
        "shipping_methodid":"15",
        "shipping_countryshippingid":"175",
        "shipping_charge":"7.95",
        "shipping_interpolate":"0"
        },
    "179":
        {
        "shipping_name":"Saturday UK - Saturday UK",
        "shipping_integrationservicecode":"",
        "shipping_integrationcarriercode":"",
        "shipping_methodid":"17",
        "shipping_countryshippingid":"179",
        "shipping_charge":"15.95",
        "shipping_interpolate":"0"
        }
},
"message":"200 OK",
"parameters":
    {
    "email":"franclin.foping@nitrosell.net",
    "ship-firstname":"Jane",
    "ship-lastname":"Jane",
    "ship-fullname":"Jane Brooks",
    "ship-company":"",
    "ship-address1":"36 Bridge St Row",
    "ship-address2":"",
    "ship-city":"Chester",
    "ship-zippostcode":"CH1 1NN",
    "ship-state":"Cheshire",
    "ship-statecode":"UK",
    "ship-country":"United Kingdom",
    "ship-countrycode":"UK",
    "ship-telephone":"01244400318",
    "ship-fax":"01789204015",
    "ship-email":"franclin.foping@nitrosell.net",
    "products":"SM38MU34C:3,MK25CRS:1,MH86OL32E:1",
    "time":"1354895083",
    "hash":"65b2f58926318c762f986784ba4a68ad",
    "userid":"50c2080e091f9"
},
"request":"POST"
}
```


### Retrieve the amount of tax applicable to a given order 

The following table summarises the list of parameters for this endpoint

Table 7: **GetTaxForAnOrder** Request Fields

| Field Name    | Data Type     | Usage | Description |
| ------------- |:-------------:| ----- |:-----------:|
| __hash__      | String        | Required | Please refer to the description provided in Table 1|
| __userid__    | String        | Required | Please refer to the description provided in Table 1|
| __time__      | Integer       | Required | Please refer to the description provided in Table 1|
| __email__   | String        | Required | This is the email address of the customer you are looking for|
| __ship-firstname__ | String      | Required | This is the first name that appears on the shipping address |
| __ship-lastname__ | String      | Required | This is the last name that appears on the shipping address |
| __ship-fullname__ | String      | Required | This is the full name that appears on the shipping address |
| __ship-company__ | String      | Required | This is the company name that appears on the shipping address |
| __ship-address1__ | String      | Required | This is the street address that appears on the shipping address |
| __ship-address2__ | String      | Required | This is the second line that appears on the shipping address |
| __ship-city__ | String      | Required | This is the city that appears on the shipping address |
| __ship-country__ | String      | Required | This is the country name that appears on the shipping address |
| __ship-telephone__ | String      | Required | This is the phone number that appears on the shipping address |
| __ship-fax__ | String      | Required | This is the fax number that appears on the shipping address |
| __ship-email__ | String      | Required | This is the email address that appears on the shipping address |
| __products__ | String      | Required | This is a comma separated list of products in the basket. It is in the form: product1:qty, product2:qty |


Available URL query parameters (all compulsory)


* email: the email of the customer placing the order
* ship-firstname: the first name of the customer registered at the shipping address of the order
* ship-lastname: the last name of the customer registered at the shipping address of the order
* ship-fullname: the full name of the customer registered at the shipping address of the order
* ship-company: the company name of the customer registered at the shipping address of the order
* ship-address1: the street address of the customer registered at the shipping address of the order
* ship-address2: the street address of the customer registered at the shipping address of the order
* ship-city: the city of the customer registered at the shipping address of the order
* ship-country: the country of the customer registered at the shipping address of the order
* ship-telephone: the phone number of the customer registered at the shipping address of the order
* ship-fax: the fax number of the customer registered at the shipping address of the order
* ship-email: the email of the customer registered at the shipping address of the order
* products: the list of products currently in the basket. This is a comma-separated list in the form productcode1:qty, productcode2:qty, …, productcoden:qty
* time: this is the timestamp of the request
* userid: this is the ID of the user making the request
* hash: this is the hash code associated with the request

##### Request

**POST**

/tax.json


##### Response

**HTTP /1.1 OK**

```javascript
{
            "tax":10,
            "basket":3,
            "message":"200 OK",
            "parameters":
            {
                        "email":"franclin.foping@nitrosell.net",
                        "ship-firstname":"John",
                        "ship-lastname":"John",
                        "ship-fullname":"John Brooks",
                        "ship-company":"",
                        "ship-address1":"66 Bridge St Row",
                        "ship-address2":"",
                        "ship-city":"Chester",
                        "ship-zippostcode":"CH1 1NN",
                        "ship-state":"Cheshire",
                        "ship-statecode":"UK",
                        "ship-country":"United Kingdom",
                        "ship-countrycode":"UK",
                        "ship-telephone":"01244400318",
                        "ship-fax":"01244400318",
                        "ship-email":"franclin.foping@nitrosell.net",
                        "products":"SM38MU34C:3,MK25CRS:1,MH86OL32E:1","time":"1354895083",
                        "hash":"65b2f58926318c762f986784ba4a68ad",
                        "userid":"50c2080e091f9"
            },
            "request":"POST"
}
```


### Retrieve the amount of tax and shipping options applicable to a given order 

The following table summarises the list of parameters for this endpoint

Table 8: **GetShippingAndTaxForAnOrder** Request Fields

| Field Name    | Data Type     | Usage | Description |
| ------------- |:-------------:| ----- |:-----------:|
| __hash__      | String        | Required | Please refer to the description provided in Table 1|
| __userid__    | String        | Required | Please refer to the description provided in Table 1|
| __time__      | Integer       | Required | Please refer to the description provided in Table 1|
| __email__   | String        | Required | This is the email address of the customer you are looking for|
| __ship-firstname__ | String      | Required | This is the first name that appears on the shipping address |
| __ship-lastname__ | String      | Required | This is the last name that appears on the shipping address |
| __ship-fullname__ | String      | Required | This is the full name that appears on the shipping address |
| __ship-company__ | String      | Required | This is the company name that appears on the shipping address |
| __ship-address1__ | String      | Required | This is the street address that appears on the shipping address |
| __ship-address2__ | String      | Required | This is the second line that appears on the shipping address |
| __ship-city__ | String      | Required | This is the city that appears on the shipping address |
| __ship-country__ | String      | Required | This is the country name that appears on the shipping address |
| __ship-telephone__ | String      | Required | This is the phone number that appears on the shipping address |
| __ship-fax__ | String      | Required | This is the fax number that appears on the shipping address |
| __ship-email__ | String      | Required | This is the email address that appears on the shipping address |
| __products__ | String      | Required | This is a comma separated list of products in the basket. It is in the form: product1:qty, product2:qty |


Available URL query parameters (all compulsory)


* email: the email of the customer placing the order
* ship-firstname: the first name of the customer registered at the shipping address of the order
* ship-lastname: the last name of the customer registered at the shipping address of the order
* ship-fullname: the full name of the customer registered at the shipping address of the order
* ship-company: the company name of the customer registered at the shipping address of the order
* ship-address1: the street address of the customer registered at the shipping address of the order
* ship-address2: the street address of the customer registered at the shipping address of the order
* ship-city: the city of the customer registered at the shipping address of the order
* ship-country: the country of the customer registered at the shipping address of the order
* ship-telephone: the phone number of the customer registered at the shipping address of the order
* ship-fax: the fax number of the customer registered at the shipping address of the order
* ship-email: the email of the customer registered at the shipping address of the order
* products: the list of products currently in the basket. This is a comma-separated list in the form productcode1:qty, productcode2:qty, …, productcoden:qty
* time: this is the timestamp of the request
* userid: this is the ID of the user making the request
* hash: this is the hash code associated with the request

##### Request

**POST**

/shippingandtax.json


##### Response

**HTTP /1.1 OK**

```javascript
{
            "tax":10,
            "basket":3,
            "shippingOptions":
                        {
                                    "163":
                                                {
                                                            "shipping_name":"Consolidated Messenger - Three Day",
                                                            "shipping_integrationservicecode":"",
                                                            "shipping_integrationcarriercode":"",
                                                            "shipping_methodid":"2",
                                                            "shipping_countryshippingid":"163",
                                                            "shipping_charge":"0",
                                                            "shipping_interpolate":"0"
                                                            },
                                                            "34":
                                                                        {
                                                                        "shipping_name":"Consolidated Messenger - Five Day",
                                                                        "shipping_integrationservicecode":"",
                                                                        "shipping_integrationcarriercode":"",
                                                                        "shipping_methodid":"3",
                                                                        "shipping_countryshippingid":"34",
                                                                        "shipping_charge":"0",
                                                                        "shipping_interpolate":"0"
                                                                        },
                                                            "181":
                                                                        {
                                                                        "shipping_name":"Consolidated Messenger - Overnight",
                                                                        "shipping_integrationservicecode":"",
                                                                        "shipping_integrationcarriercode":"",
                                                                        "shipping_methodid":"1",
                                                                        "shipping_countryshippingid":"181",
                                                                        "shipping_charge":"15",
                                                                        "shipping_interpolate":"0"
                                                                        }                                                                                    
                                                },
                                    "message":"200 OK",
                                    "parameters":{
                                                "email":"franclin.foping@nitrosell.net",
                                                "ship-firstname":"John",
                                                "ship-lastname":"Jackson",
                                                "ship-fullname":"John Jackson Brooks",
                                                "ship-company":"",
                                                "ship-address1":"66 Bridge St Row",
                                                "ship-address2":"",
                                                "ship-city":"Chester",
                                                "ship-zippostcode":"CH1 1NN",
                                                "ship-state":"Cheshire",
                                                "ship-statecode":"UK",
                                                "ship-country":"United Kingdom",
                                                "ship-countrycode":"UK",
                                                "ship-telephone":"01244400318",
                                                "ship-fax":"01789204015",                                                                                                
                                                "ship-email":"franclin.foping@nitrosell.net",
                                                "products":"JWW003:3,JWW002:1,JWC001:1",
                                                "time":"1360597347",
                                                "userid":"511522521801a",
                                                "hash":"727affc3d4dff6a01b25eccad22ac570"
                                                },
                                                "request":"POST"
}
```

### Retrieve the users' reviews

The following table summarises the list of parameters for this endpoint

Table : **GetReviews** Request Fields

| Field Name    | Data Type     | Usage | Description |
| ------------- |:-------------:| ----- |:-----------:|
| __hash__      | String        | Required | Please refer to the description provided in Table 1|
| __userid__    | String        | Required | Please refer to the description provided in Table 1|
| __time__      | Integer       | Required | Please refer to the description provided in Table 1|
| __productreview_id__ | integer      | Optional | This is the ID of the reviewed to be retrieved, if omitted, all the reviews are returned |




##### Request

**GET**

/review.json?productreview_id=10&hash=2683824d2af315d1022e2ea2dca21ee1&userid=50c2080e091f9&time=1354893907/


##### Response

**HTTP /1.1 OK**

```javascript
{
            "productreview":
            {
              product review           
              },
            "request":"GET"
}
```

### Ping to check your credentials

The following table summarises the list of parameters for this endpoint.
Please note than under no circumstances you should pass your credentials as this is a GET request.

Table : **Ping** Request Fields

| Field Name    | Data Type     | Usage | Description |
| ------------- |:-------------:| ----- |:-----------:|
| __hash__      | String        | Required | Please refer to the description provided in Table 1|
| __userid__    | String        | Required | Please refer to the description provided in Table 1|
| __time__      | Integer       | Required | Please refer to the description provided in Table 1|

##### Request

**GET**

/ping.json?hash=2683824d2af315d1022e2ea2dca21ee1&userid=50c2080e091f9&time=1354893907/


##### Response

**HTTP /1.1 OK**

```javascript
{
            "credentialsok":
            "request":"GET"
}
```


### Retrieve the basket content given its cookie ID (advanced)
All your users are uniquely identified by a session ID which is stored in their browser. This API call gives you the opportunity to leverage that and get the content of their basket. For geeks, this is actually equivalent to performing a JSONP call to our server and you specify a callback that will use the basket content.

The following table summarises the list of parameters for this endpoint

Table : **GetBasketContent** Request Fields

| Field Name    | Data Type     | Usage | Description |
| ------------- |:-------------:| ----- |:-----------:|
| __hash__      | String        | Required | Please refer to the description provided in Table 1|
| __userid__    | String        | Required | Please refer to the description provided in Table 1|
| __time__      | Integer       | Required | Please refer to the description provided in Table 1|
| __sessid__ | hash      | required | This is the session ID of your user |
| __callback__ | string      | required | This is the function that will be called when the call is executed |


##### Request

**GET**

/basket.json?callback='myfunction'&sessid=bu5515adv&hash=2683824d2af315d1022e2ea2dca21ee1&userid=50c2080e091f9&time=1354893907/


##### Response

**HTTP /1.1 OK**

```javascript
myfunction({
            // whatever your function is meant to do
  
})
```

### Insert a Web Order into a WebStore 

The following table summarises the list of parameters for this endpoint

Table 9: __InsertWebOrder__ Request Fields

| Field Name    | Data Type     | Usage | Description |
| ------------- |:-------------:| ----- |:-----------:|
| __hash__      | String        | Required | Please refer to the description provided in Table 1|
| __userid__    | String        | Required | Please refer to the description provided in Table 1|
| __time__      | Integer       | Required | Please refer to the description provided in Table 1|
| __shippingid__ | Integer      | Required | This integer represents the shipping method used in this order|
| __tenderid__   | Integer      | Required | This integer represents the tender type used in this order|
|__cust-username__| String      | Required | This field represents the registered username of the customer placing the order| 
|**cust-firstname**| String| Required | This is the firstname of the customer processing the transaction|
|**cust-lastname**| String| Required | This is the lastname of the customer processing the transaction|
|**cust-address1** |String| Required| This is the street address of the customer processing the transaction|
| **cust-city** | String | Required | This is the name of the city of the  customer processing the transaction|
| **cust-country** |String | Required | This is the country name of the customer processing the transaction|
| **cust-zipppostcode** | String | Required | This is the zip code of the customer processing the transaction |
| __email__   | String        | Required | This is the email address of the customer you are looking for|
| __ship-firstname__ | String      | Required | This is the first name that appears on the shipping address |
| __ship-lastname__ | String      | Required | This is the last name that appears on the shipping address |
| __ship-fullname__ | String      | Required | This is the full name that appears on the shipping address |
| __ship-company__ | String      | Required | This is the company name that appears on the shipping address |
| __ship-address1__ | String      | Required | This is the street address that appears on the shipping address |
| __ship-address2__ | String      | Required | This is the second line that appears on the shipping address |
| __ship-city__ | String      | Required | This is the city that appears on the shipping address |
| __ship-country__ | String      | Required | This is the country name that appears on the shipping address |
| __ship-telephone__ | String      | Required | This is the phone number that appears on the shipping address |
| __ship-fax__ | String      | Required | This is the fax number that appears on the shipping address |
| __ship-email__ | String      | Required | This is the email address that appears on the shipping address |
| __products__ | String      | Required | This is a comma separated list of products in the basket. It is in the form: product1:qty, product2:qty |



##### Request

**POST**

/order.json


##### Response

**HTTP /1.1 OK**

```javascript
{
            "message":"200 OK",
            "parameters":
            {
                        "email":"franclin.foping@nitrosell.net",
                        "cust-username":"tester",
                        "cust-firstname":"Test",
                        "cust-lastname":"Testing",
                        "cust-address1":"10 White Friars",
                        "cust-city":"Manchester",
                        "cust-country":"United Kingdom",
                        "cust-zippostcode":"M1 3EU",
                        "ship-firstname":"John",
                        "ship-lastname":"Brooks",
                        "ship-fullname":"John Brooks",
                        "ship-company":"",
                        "ship-address1":"66 Bridge St Row",
                        "ship-address2":"",
                        "ship-city":"Chester",
                        "ship-zippostcode":"CH1 1NN",
                        "ship-state":"Cheshire",
                        "ship-statecode":"UK",
                        "ship-country":"United Kingdom",
                        "ship-countrycode":"UK",
                        "ship-telephone":"01244400318",
                        "ship-fax":"01789204015",
                        "ship-email":"franclin.foping@nitrosell.net",
                        "products":"SM38MU34C:3,MK25CRS:1,MH86OL32E:1",
                        "time":"1354876123",
                        "shippingid":"175",
                        "tenderid":"8",
                        "hash":"53e05bb3d7f90ff1de3e270f17c71621",
                        "userid":"50c229e511ab0"
            },
            "request":"POST"
}
```

### GetWebOrders
Download all the new orders that you have or a specific order. It should be noted that after downloading an order, its order status will be set to DOWNLOADED. Remember that NSc Sync only downloads NEW orders. 
*Please kindly note that as of October 12, 2017 this endpoint is only available on version alpha and beta.*

Table 10: __GetWebOrders__ Request Fields

| Field Name    | Data Type     | Usage | Description |
| ------------- |:-------------:| ----- |:-----------:|
| __ordernumber__ | String      | Optional | This is the order number to be downloaded|
| __start__ | String      | Optional | This is the time up to which the orders are to be downloaded. By default it's set to the current timestamp |
| __status__ | String      | Optional | This is the status of the orders to be downloaded (NEW, COMPLETE, DOWNLOADED, UNSHIPPED, REFUNDED). By default, it is set to NEW |

##### Request

**GET**

/order.json

##### Response
```json
{
  "count": 1,
  "orders": [
    {
      "Order": {
        "@domain": "mystoredomain.nitrosell.com",
        "@currencysymbol": "$",
        "@currency": "1",
        "@id": "WebOrder #1491920002",
        "Options": {
          "Option": [
            {
              "@name": "edition",
              "$": "RMSSO"
            },
            {
              "@name": "taxmodule",
              "$": "RMSByItem"
            },
            {
              "@name": "shippingbyitem",
              "$": "YES"
            },
            {
              "@name": "shippingbyitemtaxid",
              "$": "2"
            }
          ]
        },
        "NumericTime": "1491920002",
        "OriginatingIP": "10.0.3.93",
        "UserAgent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.12; rv:49.0) Gecko/20100101 Firefox/49.0",
        "AddressInfo": [
          {
            "@type": "ship",
            "@userlocalfield": "AccountNumber",
            "@userremotefield": "Account",
            "Name": {
              "Title": "Mr",
              "First": "John",
              "Last": "Doe",
              "Full": "John Doe"
            },
            "Company": "J&s Outdoors",
            "Address1": "1127 Windiate St",
            "Account": "testuser",
            "CustomText5": "blabla",
            "Address2": [],
            "City": "Manitowoc",
            "State": "WI",
            "Country": "United States",
            "Zip": "54220-2706",
            "Phone": "4083807204",
            "TaxExempt": "0",
            "Fax": [],
            "Email": "myemail@mymailserver.com"
          },
          {
            "@type": "bill",
            "@userlocalfield": "AccountNumber",
            "@userremotefield": "Account",
            "Name": {
              "Title": "Mr",
              "First": "John",
              "Last": "Doe",
              "Full": "John Doe"
            },
            "Company": "J&s Outdoors",
            "Address1": "1127 Windiate St",
            "Account": "nitrotest",
            "CustomText5": "password",
            "Address2": [],
            "City": "Manitowoc",
            "State": "WI",
            "Country": "United States",
            "Zip": "54220-2706",
            "Phone": "4083807204",
            "TaxExempt": "0",
            "Fax": [],
            "Email": "myemail@mymailserver.com"
          }
        ],
        "Shipping": {
          "@id": "15",
          "$": "RM - Special Delivery - Free Delivery"
        },
        "CreditCard": {
          "@amount": "5.32",
          "@tenderid": "15",
          "@cctype": "Visa",
          "@cclastdigits": "1111",
          "@expiration": "ENCRYPTED:+HqaLlblevB/mgOIOb8gQw==",
          "$": "ENCRYPTED:D7LHWyKgDcBCXZCzRIbZsg=="
        },
        "Comments": [
          {
            "@WindowTitle": "WARNING: CARD DETAILS REMOVED FOR PCI-DSS COMPLIANCE"
          },
          {
            "@WindowTitle": "Test Order Notification",
            "$": "NOTICE! This order was placed in test mode. No transfer of funds have taken place!"
          },
          {
            "@WindowTitle": "Shipping Date",
            "$": "SHIP 04/12/2017"
          },
          {
            "@WindowTitle": "Comments from Customer",
            "$": "THIS IS NOT A REAL ORDER! THIS IS A NITROSELL TEST ORDER PLEASE IGNORE! SHIP 04/12/2017"
          }
        ],
        "OrderVersion": "4",
        "Item": {
          "@num": "1",
          "@taxid": "2",
          "Id": "4112",
          "Code": "macoun",
          "Quantity": "1",
          "Unit-Price": "5.32",
          "Unit-Tax": "0.89",
          "Tax-Code": "00000",
          "Description": "King Lucious",
          "Group": [],
          "Taxable": "YES"
        },
        "Total": {
          "Line": [
            {
              "@name": "Subtotal",
              "@type": "Subtotal",
              "$": "5.32"
            },
            {
              "@name": "Shipping",
              "@type": "Shipping",
              "$": "0"
            },
            {
              "@name": "Tax",
              "@type": "Tax",
              "$": "0.89"
            },
            {
              "@name": "Total",
              "@type": "Total",
              "$": "5.32"
            }
          ]
        }
      }
    }
  ]
}

```

### Appendix: Error Codes Returned By the API

The following error codes can be returned by the API to indicate that something went wrong somewhere. In the following tables, we will list and explain what each error means:


Table 10: Error Codes



| Codes    | Message     | Affected Resources | Description and Hints|
| ------------- |:-------------:| ----- |:-----------:|
|400 | Bad request  | All resources | Your request is malformed or missing some vital information. For instance, the supplied email address might not be valid or you did not specify a required parameter to the request.|
|401 | Not Authorized | All resources | This error means that either the request has expired or the hash did not match or the provided User ID is no longer active and simply does not exist. If the hash does not match, the possible cause is that the private key is not valid|
|404| Not Found | All resources | This error means that you are querying a resource that does not exist on our system. A typo might be at the heart of this issue. Double check your syntax and try again.|
|405| Method Not allowed | All resources | This error means that you are attempting an operation that is not supported by that resource. For instance, you may be trying to a POST request on the __tender__ resource| 
