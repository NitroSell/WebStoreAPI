WebStoreAPI DOCUMENTATION
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

** HTTP /1.1 OK **

...javascript
{
"customer_id":"2",
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
...
