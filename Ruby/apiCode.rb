# Sample code for the API 
# this code has been tested on Ruby version 1.8.7
require 'net/http'
# in a real setting the connection should be establised through a secure channel
# uncomment the next lines to avail of this feature
# require 'net/https'
# 
require 'uri'
require 'time'
require 'openssl'
require 'cgi'

class NScAPIWrapper
  # defining the getter
  attr_reader :userid
	
 
  # This is the constructor of the class.
  # It takes the following parameters
  # webstore: the WebStore URL 
  # userid: the User ID
  # key : the private key of the user  
  def initialize(key, userid, webstoreurl)
    @webstore = webstoreurl
    @userid = userid
    @key = key
    @baseuri = 'YOUR STORE URL'
    # or @baseuri = 'https://api.nitrosell.com'
  end
  
  # This method return the details of the customer identified by email
  # email: the email of the customer whose details are being fetched
  # return the customer data as a JSON-encoded string
  def getCustomerDetails(email)
    parameters = {'email' => email, 'userid' => @userid, 'time' => Time.now.to_i().to_s()}
    parameters['hash'] = OpenSSL::HMAC.hexdigest('md5', @key, parameters.values.join('.'))
    
    uri = @baseuri + @webstore + '/v1/customer.json?' + parameters.map{|k,v| "#{CGI.escape(k)}=#{CGI.escape(v)}"}.join('&')
    #uri = @baseuri + @webstore + '/v1/customer.json?' + URI.encode_www_form(parameters)
    
    uriReq = URI.parse(uri)	
    http = Net::HTTP.new(uriReq.host, uriReq.port)    
    # for an SSL connection uncomment the next lines
    # http.use_ssl = true
    # http.verify_mode = OpenSSL::SSL::VERIFY_NONE
    response = http.request(Net::HTTP::Get.new(uriReq.request_uri, {'User-Agent' => 'Mozilla/5.0'}))
    puts response.body    
  end
  

  # this method returns the list of shipping options as a JSON encoded string
  # parameters is a collection containing the basket details
  def getShippingOptions(parameters)
    parameters['hash'] = OpenSSL::HMAC.hexdigest('md5', @key, parameters.values.join('.'))
    postData = parameters.map{|k,v| "#{CGI.escape(k)}=#{CGI.escape(v)}}"}.join('&')
    headers = {'User-Agent' => 'Mozilla/5.0', 'Content-Type' => 'application/x-www-form-urlencoded', 'Content-Length' => postData.length.to_s}
    uri = URI.parse(@baseuri + @webstore + '/v1/shipping.json')
    http = Net::HTTP.new(uri.host, uri.port)
    request = Net::HTTP::Post.new(uri.request_uri, headers)
    request.set_form_data(parameters)    
    response = http.request(request)
    puts response.body
  end
  

  # this method returns the amount of tax applicable to a basket
  # parameters is a collection containing the basket details
  def getTaxForAnOrder(parameters)
    parameters['hash'] = OpenSSL::HMAC.hexdigest('md5', @key, parameters.values.join('.'))
    postData = parameters.map{|k,v| "#{CGI.escape(k)}=#{CGI.escape(v)}}"}.join('&')
    headers = {'User-Agent' => 'Mozilla/5.0', 'Content-Type' => 'application/x-www-form-urlencoded', 'Content-Length' => postData.length.to_s}
    uri = URI.parse(@baseuri + @webstore + '/v1/tax.json')
    http = Net::HTTP.new(uri.host, uri.port)
    request = Net::HTTP::Post.new(uri.request_uri, headers)
    request.set_form_data(parameters)    
    response = http.request(request)
    puts response.body
  end
  
  # this method returns the amount of tax and shipping options applicable to a basket 
  # as a JSON encoded string
  def getShippingAndTax(parameters)
    parameters['hash'] = OpenSSL::HMAC.hexdigest('md5', @key, parameters.values.join('.'))
    postData = parameters.map{|k,v| "#{CGI.escape(k)}=#{CGI.escape(v)}}"}.join('&')
    headers = {'User-Agent' => 'Mozilla/5.0', 'Content-Type' => 'application/x-www-form-urlencoded', 'Content-Length' => postData.length.to_s}
    uri = URI.parse(@baseuri + @webstore + '/v1/shippingandtax.json')
    http = Net::HTTP.new(uri.host, uri.port)
    request = Net::HTTP::Post.new(uri.request_uri, headers)
    request.set_form_data(parameters)    
    response = http.request(request)
    puts response.body
  end
  

  # this method performs the actual order insertion
  # parameters is a collection containing the basket details
  def insertWebOrders(parameters)
    parameters['hash'] = OpenSSL::HMAC.hexdigest('md5', @key, parameters.values.join('.'))
    postData = parameters.map{|k,v| "#{CGI.escape(k)}=#{CGI.escape(v)}}"}.join('&')
    headers = {'User-Agent' => 'Mozilla/5.0', 'Content-Type' => 'application/x-www-form-urlencoded', 'Content-Length' => postData.length.to_s}
    uri = URI.parse(@baseuri + @webstore + '/v1/order.json')
    http = Net::HTTP.new(uri.host, uri.port)
    request = Net::HTTP::Post.new(uri.request_uri, headers)
    request.set_form_data(parameters)    
    response = http.request(request)
    puts response.body
  end  
end
	

objAPI = NScAPIWrapper.new('YOUR PRIVATE KEY', 'Your USER ID', 'YOUR WEBSTORE URL')
objAPI.getCustomerDetails('YOUR CUSTOMER EMAIL ADDRESS')

products = {'JWW003' => 1, 'JWW002' => 1, 'JWW001' => 1}

parameters = {'email' => 'YOUR EMAIL ADDRESS', 
              'ship-firstname' => 'FIRST NAME',
              'ship-lastname' => 'LAST NAME',
              'ship-fullname' => FULL NAME',
              'ship-company' => '',
              'ship-address1' => '36 Bridge St Row',
              'ship-address2' => '',
              'ship-city' => 'Chester',
              'ship-zippostcode' => 'UK',
              'ship-state' => 'Cheshire',
              'ship-statecode' => 'UK',
              'ship-country' => 'United Kingdom',
              'ship-telephone' => '01244400318',
              'ship-fax' => '01789204015',
              'ship-email' => 'YOUR EMAIL ADDRESS',
              'products' => products.map{|k,v| "#{k}:#{v}"}.join(','),
              'time' => Time.now.to_i().to_s(),
              'userid' => objAPI.userid
             }

#objAPI.getShippingOptions(parameters)
#objAPI.getTaxForAnOrder(parameters)
#objAPI.getShippingAndTax(parameters)

# insert a web order
orderDetails = {}
orderDetails['email'] = 'YOUR CUSTOMER EMAIL ADDRESS'
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
orderDetails['ship-email'] = 'CUSTOMER EMAIL ADDRESS'
orderDetails['products'] = products.map{|k,v| "#{k}:#{v}"}.join(',')
orderDetails['time'] = Time.now.to_i().to_s()
orderDetails['shippingid'] = '163'
orderDetails['tenderid'] = '10'
orderDetails['userid'] = objAPI.userid

#objAPI.insertWebOrders(orderDetails)
