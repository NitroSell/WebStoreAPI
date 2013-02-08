WebStoreAPI Documentation
=========================

The API enables Web developers to integrate with retailers' Webstores to allow order import and customer insert from third-party platforms. It can also be used to perform a number of operations that are documented below. The API consists of eight endpoints which are detailed below:


*     __GetCustomerDetails__: this endpoint allows you to retrieve the details of a customer given its email address;
*     __GetAllTenders__: this endpoint allows you to get the list of available tenders for a given WebStore;
*     __GetTendersByKeywords__: this endpoint allows to search for a particular tender given its keyword;
*     __GetItem__: this endpoint allows you to retrieve a set of items given their characteristics. This could be their keywords, their department ID and so forth;
*     __GetShippingOptions__: this endpoint allows you to query the list of possible shipping options of a given basket;
*     __GetTaxForAnOrder__: this endpoint allows to get the applicable amount of tax for a given basket;
*     __GetShippingAndTaxForAnOrder__: this endpoint is an aggregation of the previous two endpoints; it returns the applicable tax and the shipping options for a given basket;
*     __InsertWebOrders__: this endpoint allows you to insert a given order into the WebStore.


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

### Retrieve the list of all supported tenders

The following table summarises the list of parameters for this endpoint

Table 2: **GetAllTenders** Request Fields

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

Table 3: **GetTendersByKeywords** Request Fields

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

Table 4: **GetItem** Request Fields

| Field Name    | Data Type     | Usage | Description |
| ------------- |:-------------:| ----- |:-----------:|
| __hash__      | String        | Required | Please refer to the description provided in Table 1|
| __userid__    | String        | Required | Please refer to the description provided in Table 1|
| __time__      | Integer       | Required | Please refer to the description provided in Table 1|
|__keywords__   | String        | Optional | This indicates the keyword of the tender. If you don’t provide one, all the tenders will be returned.|
| departmentid  | Integer      | Optional | This indicates the department ID of the items to be fetched |
| categoryid   | Integer      | Optional | This indicates the category ID of the items to be fetched |
| price   | Integer      | Optional | This indicates the price of the items to be fetched |

Available URL query parameters (all compulsory)


* hash: the hash of the request. This is computed with your private key
* userid: your user ID
* time: the timestamp of the request
* keywords: the keywords of the tender to be fetched

N.B: *the departmentid, categoryid and price can be appended with the following operators: gte standing for greater than, le standing for less than. A typical usage would be to specify for instance the list of items whose departmentid is less than 50. In that case, one could specify this as a parameter to the request: product_departmentid_lt = 50 (Note the use of an underscore between the operator and departmentid).* 

##### Request

**GET**

/item.json?keyword=tie&hash=2683824d2af315d1022e2ea2dca21ee1&userid=50c2080e091f9&time=1354893907/


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

Table 5: **GetShippingOptions** Request Fields

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
