#!/usr/bin/python

"""@package docstring
Documentation for this module
This file contains the implementation of the main class for the API
The class contains five public methods to access each endpoint of the API and a handful of private methods
Each method is documented below
"""

import time
from hashlib import md5
import hmac
import urllib
import httplib


class NScAPIWrapper:
    ##
    # This is the constructor of the class
    # Three parameters are needed to build an object of this type
    # Attributes:
	
    #  key this represents your private. You should contact the NitroSell Support Team to request one.
    #  userid this represents your User ID as assigned by the retailer to which you would like to query his data
    #  webstoreUrl this is the URL of the WebStore that you would like to query    
    def __init__(self, key = '', userid = '', webstoreurl = ''):
        self.key = key # this attribute will hold the private key of the user
        self.webstoreurl = webstoreurl # this will contain the URL of the WebStore 
        self.userid = userid # this will store the user ID 

        # Note: for testing purpose I have initiated an unsecure connection
        # Of course, in a real setting, this connection will have to be established in a secure socket
        # which means that you should compile you should compile the socket module with the SSL support
        # having done that, all you need to do to avail of the secure connection is to uncomment the next line and comment the line after the next
        #self.connection = httplib.HTTPSConnection('api.internal.nitrosell.com')
        self.connection = httplib.HTTPConnection('YOUR STORE URL')

    ##
    # This method returns the user ID
    def getUserID(self):
        return self.userid

    ##
    # This method fetches the details of the customer whose email is emailAddress
    # @param self the object pointer
    # @param emailAddress the email address of the customer we are after
    # @return the details of the customer as a JSON-encoded string
    def getCustomerDetails(self, emailAddress):
        parameters = {}
        parameters.update(email = emailAddress)
        parameters.update(userid = self.userid)
        parameters.update(time = int(time.time()))

        stringtoencode = parameters['userid'] + '.' + parameters['email'] + '.' + str(parameters['time'])
        parameters.update(hash = hmac.new(self.key, stringtoencode, md5).hexdigest())
        self.connection.request("GET","/"+self.webstoreurl+"/v1/customer.json?"+urllib.urlencode(parameters),"",{"User-Agent": "Mozilla/5.0"})

        response = self.connection.getresponse()
        print response.read()

    ##
    # This method returns the list of shipping options for a given basket
    # @param self the object pointer
    # @param parameters a dictionary describing the basket
    # @return the set of shipping options in a JSON-encoded string
    def getShippingOptions(self, parameters):
        stringtoencoded = ''
        for s in parameters.values():
            stringtoencoded += str(s) + '.'

        stringtoencoded = stringtoencoded[:-1]
        parameters.update(hash = hmac.new(self.key, stringtoencoded, md5).hexdigest())

        postdata = urllib.urlencode(parameters)

        headers = {"User-Agent": "Mozilla/5.0","Content-Type": 'application/x-www-form-urlencoded', "Content-Length": str(len(postdata))}
        self.connection.request("POST", "/"+self.webstoreurl+'/v1/shipping.json', postdata, headers)
        response = self.connection.getresponse()

        print 'Response Code is : '+ str(response.status)
        print response.read()

    ##
    # This method the amount of tax applicable to a given basket
    # @param self the object pointer
    # @param parameters a dictionary containing the basket items
    # @return the amount of tax applicable to the basket in a JSON-encoded string
    def getTaxForAnOrder(self, parameters):
        stringtoencoded = ''
        for s in parameters.values():
            stringtoencoded += str(s) + '.'

        stringtoencoded = stringtoencoded[:-1]
        parameters.update(hash = hmac.new(self.key, stringtoencoded, md5).hexdigest())

        postdata = urllib.urlencode(parameters)

        headers = {"User-Agent": "Mozilla/5.0","Content-Type": 'application/x-www-form-urlencoded', "Content-Length": str(len(postdata))}
        self.connection.request("POST", "/"+self.webstoreurl+'/v1/tax.json', postdata, headers)
        response = self.connection.getresponse()

        print 'Response Code is : '+ str(response.status)
        print response.read()

    ##
    # This method returns both the amount of tax and the shipping options applicable to a given basket
    # @param self the object pointer
    # @param parameters a dictionary containing the basket items
    # @return the set of shipping options and the amount of tax in a JSON-encoded array
    def getShippingAndTax(self, parameters):
        stringtoencoded = ''
        for s in parameters.values():
            stringtoencoded += str(s) + '.'

        stringtoencoded = stringtoencoded[:-1]
        parameters.update(hash = hmac.new(self.key, stringtoencoded, md5).hexdigest())

        postdata = urllib.urlencode(parameters)

        headers = {"User-Agent": "Mozilla/5.0","Content-Type": 'application/x-www-form-urlencoded', "Content-Length": str(len(postdata))}
        self.connection.request("POST", "/"+self.webstoreurl+'/v1/shippingandtax.json', postdata, headers)
        response = self.connection.getresponse()

        print 'Response Code is : '+ str(response.status)
        print response.read()
    ##
    # This function performs the actual order insertion into the WebStore
    # @param self the object pointer
    # @param parameters a dictionary describing the basket items
    def insertWebOrders(self, parameters):
        stringtoencoded = ''
        for s in parameters.values():
            stringtoencoded += str(s) + '.'

        stringtoencoded = stringtoencoded[:-1]
        parameters.update(hash = hmac.new(self.key, stringtoencoded, md5).hexdigest())

        postdata = urllib.urlencode(parameters)

        headers = {"User-Agent": "Mozilla/5.0","Content-Type": 'application/x-www-form-urlencoded', "Content-Length": str(len(postdata))}
        self.connection.request("POST", "/"+self.webstoreurl+'/v1/order.json', postdata, headers)
        response = self.connection.getresponse()

        print 'Response Code is : '+ str(response.status)
        print response.read()

# to build an NScAPIWrapper object, we need the following parameters in order:
# 1 - the private key of the customer initiating the request. Note that this key is never sent across the network, it is only used to compute the hash required to authenticate the user
# 2 - the user ID
# 3 - the URI of the webstore on which you are making the request

APICalls = NScAPIWrapper('df5e8c6c2cb42e2447de4b4f6e4c39ef', '511522521801a', 'YOUR STORE URL')


# Here you define the items that are in your basket
# We use a dictionary to represent the basket
# the key represents the product code and the value the quantity of the items
# for instance JWW003:2 means that two items with the code JWW0003 are in the basket
products = dict(JWW003 = 1, JWW002 = 1, JWC001 = 1)

# or
#products = {'JWW003' : 1, 'JWW002' : 1, 'JWC001' : 1}


productString = ''

# building the string of products and their number as described in the basket
for key, value in products.iteritems():
    productString += str(key) + ':' + str(value) + ','


# parameters is a dictionary describing the basket
parameters = {}
parameters['email'] = 'YOUR EMAIL ADDRESS'
parameters['ship-firstname'] = 'FIRST NAME'
parameters['ship-lastname'] = 'LAST NAME'
parameters['ship-fullname'] = 'FULL NAME'
parameters['ship-company'] = ''
parameters['ship-address1'] = '36 Bridge St Row'
parameters['ship-address2'] = ''
parameters['ship-city'] = 'Chester'
parameters['ship-zippostcode'] = 'UK'
parameters['ship-state'] = 'Cheshire'
parameters['ship-statecode'] = 'UK'
parameters['ship-country'] = 'United Kingdom'
parameters['ship-telephone'] = '01244400318'
parameters['ship-fax'] = '01789204015'
parameters['ship-email'] = 'CUSTOMER EMAIL ADDRESS'
parameters['products'] = productString[:-1] # the slicing is used to strip the trailing comma
parameters['time'] = int(time.time())
parameters['userid'] = APICalls.getUserID()

# You can perform six actions with the API
# 1 - get a customer's details given his or her email address
# 2 - get the shipping options given a basket
# 3 - get the amount of tax applicable to a basket
# 4 - fetch an item given a set of criteria
# 5 - get the amount of tax and shipping options for a given basket
# 6 - insert an order into the database

email = 'myself@domain.com'
#if not re.match(r"^[a-z0-9\.\'\_\-]+@[a-z0-9\.\-]+\.[a-z]{2,4}$", email):
#    print "The email address is not valid, check it again please"
#else:
    # good to go from here. The server will check that the email is valid and if not you will end up with a 400 response
APICalls.getCustomerDetails(email)

# this function get the shipping options for a basket, the parameters are contained in the parameter array defined previously
#APICalls.getShippingOptions(parameters)

# this function get the amount of tax applicable to a basket, the parameters are contained in the parameter array defined previously
#APICalls.getTaxForAnOrder(parameters)

# this function call get the details of a customer given his or her email address
#APICalls.getCustomerDetails(email)

# this function get the shipping options and the amount of tax for a basket, the parameters are contained in the parameter array defined previously
#APICalls.getShippingAndTax(parameters)


# the orderDetails dictionary holds the necessary details to insert an order into the WebStore
orderDetails = {}
orderDetails['email'] = 'EMAIL ADDRESS'
orderDetails['cust-username'] = 'tester'
orderDetails['cust-lastname'] = 'Testing'
orderDetails['cust-address1'] = '10 White Friars'
orderDetails['cust-city'] = 'Manchester'
orderDetails['cust-country'] = 'United Kingdom'
orderDetails['cust-zippostcode'] = 'M1 3EU'
orderDetails['ship-firstname'] = 'FIRST NAME'
orderDetails['ship-lastname'] = 'LAST NAME'
orderDetails['ship-fullname'] = 'FULL NAME'
orderDetails['ship-company'] = ''
orderDetails['ship-address1'] = '36 Bridge St Row'
orderDetails['ship-address2'] = ''
orderDetails['ship-city'] = 'Chester'
orderDetails['ship-zippostcode'] = 'CH1 1NN'
orderDetails['ship-state'] = 'Cheshire'
orderDetails['ship-statecode'] = 'UK'
orderDetails['ship-country'] = 'United Kingdom'
orderDetails['ship-countrycode'] = 'UK'
orderDetails['ship-telephone'] = '01244400318'
orderDetails['ship-fax'] = '01789204015'
orderDetails['ship-email'] = 'EMAIL ADDRESS'
orderDetails['products'] = productString[:-1] # the slicing is used to strip the trailing comma
orderDetails['time'] = int(time.time())
orderDetails['shippingid'] = '163'
orderDetails['tenderid'] = '10'
orderDetails['userid'] = APICalls.getUserID()

# This function inserts an order into the DB
#APICalls.insertWebOrders(orderDetails)
