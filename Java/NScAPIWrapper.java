/*
 * This file contains the implementation of the main class for the API
 * The class contains five public methods to access each endpoint of the API and a handful of private methods
 * Each method is documented below
 * @author franclin
 * @date 04/02/2013
 */
package apisamplecode;

import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.math.BigInteger;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import java.util.Set;
import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;

/**
 *
 * @author franclin
 * @date 04/02/2013
 */
public class NScAPIWrapper {

  private String _key;
  private String _userid;
  private String _webstoreUrl;
  private URL _url = null;
  private HttpURLConnection _connection = null;
  private SecretKeySpec _keySpec = null;
  private Mac _mac = null;

  /**
   * 
   * This is the constructor of the class
   * Three parameters are needed to build an object of this type
   * 
   * @param key this represents your private. You should contact the NitroSell Support Team to request one. 
   * @param userid this represents your User ID as assigned by the retailer to which you would like to query his data
   * @param webstoreUrl this is the URL of the WebStore that you would like to query
   */
  public NScAPIWrapper(String key, String userid, String webstoreUrl) {
    this._key = key;
    this._userid = userid;
    this._webstoreUrl = webstoreUrl;

    _keySpec = new SecretKeySpec(this._key.getBytes(), "HmacMD5");
    try {
      _mac = Mac.getInstance("HmacMD5");
      _mac.init(_keySpec);
    } catch (Exception e) {
      System.out.println(e.getMessage());
    }
    //this._connection = new HttpURLConnection();
  }
  
  /**
   * This helper method builds an HTTP POST request given the parameters and the action to be taken
   * 
   * - The parameters is a hash table containing all the details required to make the request
   * - The action is the resource being requested. It could be either shipping, shippingandtax, tax or order
   * @param parameters
   * @param action 
   */
  private void buildRequestAndExecute(HashMap parameters, String action) {
    String toencode = "";
    Set set = parameters.entrySet();
    Iterator iter = set.iterator();

    while (iter.hasNext()) {
      Map.Entry e = (Map.Entry) iter.next();
      toencode += e.getValue() + ".";
    }

    toencode = toencode.substring(0, toencode.length() - 1);
    byte[] rawHmac = _mac.doFinal(toencode.getBytes());

    BigInteger hash = new BigInteger(1, rawHmac);
    String hmac = hash.toString(16);

    if (hmac.length() % 2 != 0) {
      hmac = "0" + hmac;
    }
    
    parameters.put("hash", hmac);

    try {
      this._url = new URL("http://api.nitrosell.com/" + this._webstoreUrl + "/v1/"+ action +".json");
      _connection = (HttpURLConnection) _url.openConnection();
      _connection.setRequestMethod("POST");
      _connection.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");
      _connection.setRequestProperty("Content-Length", Integer.toString(this.urlEncodeUTF8(parameters).length()));
      _connection.setDoOutput(true);
      _connection.setUseCaches(false);
      _connection.setDoInput(true);
      _connection.setAllowUserInteraction(false);

      //Send request
      DataOutputStream wr = new DataOutputStream(_connection.getOutputStream());
      wr.writeBytes(this.urlEncodeUTF8(parameters));
      wr.flush();
      wr.close();

      // Get response
      InputStream is = _connection.getInputStream();
      BufferedReader br = new BufferedReader(new InputStreamReader(is));
      String line;

      StringBuilder response = new StringBuilder();

      while ((line = br.readLine()) != null) {
        response.append(line);
        response.append("\r");
      }

      br.close();

      System.out.println("Response " + response.toString());
    } catch (Exception e) {
      System.out.println(e.getMessage());
    }
  }
  
  
  /**
   * This helper function returns the URL Encoded UTF-8 version of the input string
   * @param s the string to be encoded
   * @return 
   */
  private String urlEncodeUTF8(String s) {
    try {
      return URLEncoder.encode(s, "UTF-8");
    } catch (Exception e) {
      throw new UnsupportedOperationException(e);
    }
  }
  
  /**
   * This helper method returns the query string used that will be passed in the body of the POST request   * 
   * @param map
   * @return string 
   */
  private String urlEncodeUTF8(Map<?, ?> map) {
    StringBuilder sb = new StringBuilder();
    
    for (Map.Entry<?, ?> entry : map.entrySet()) {
      if (sb.length() > 0) {
        sb.append("&");
      }
      
      sb.append(String.format("%s=%s", urlEncodeUTF8(entry.getKey().toString()), urlEncodeUTF8(entry.getValue().toString())));      
    }
    
    return sb.toString();
  }
  
  
  /**
   * This method returns the user ID 
   * 
   * @return 
   */
  public String getUserID() {
    return this._userid;
  }

  /**
   * This method is the endpoint to get the customer details
   * @param email 
   */
  public void getCustomerDetails(String email) {
    Date time = new Date();
    long timestamp = time.getTime() / 1000;

    try {

      String message = email + "." + this._userid + "." + timestamp;

      byte[] rawHmac = _mac.doFinal(message.getBytes());

      BigInteger hash = new BigInteger(1, rawHmac);
      String hmac = hash.toString(16);

      if (hmac.length() % 2 != 0) {
        hmac = "0" + hmac;
      }

      this._url = new URL("http://api.nitrosell.com/" + this._webstoreUrl + "/v1/customer.json" + "?email=" + email + "&hash=" + hmac + "&userid=" + this._userid + "&time=" + timestamp);
      this._connection = (HttpURLConnection) _url.openConnection();

      InputStream is = this._connection.getInputStream();
      BufferedReader br = new BufferedReader(new InputStreamReader(is));

      String line;
      StringBuilder response = new StringBuilder();

      while ((line = br.readLine()) != null) {
        response.append(line);
        response.append("\r");
      }

      br.close();
      System.out.print(response.toString());

    } catch (Exception e) {
      System.out.println("Error : " + e.getMessage());
      _connection.disconnect();
    } finally {
      if (_connection != null) {
        _connection.disconnect();
      }
    }
  }
  
  /**
   * This method is the endpoint to get the list of shipping options
   * The parameters of the request are passed in parameters tables
   * @param parameters 
   */

  public void getShippingOptions(HashMap parameters) {
    this.buildRequestAndExecute(parameters, "shipping");   
  }
  
  /**
   * This method is the endpoint to get the list of shipping options and any applicable tax 
   * The parameters of the request are passed in parameters tables
   * @param parameters 
   */
  
  public void getShippingAndTax(HashMap parameters) {
    
    this.buildRequestAndExecute(parameters, "shippingandtax");   
  }
  
  /**
   * This method is the endpoint to get the tax for a given order
   * The parameters of the request are passed in parameters tables
   * @param parameters 
   */
  
  public void getTaxForAnOrder(HashMap parameters) {
    
    this.buildRequestAndExecute(parameters, "tax");
   
  }
  
  
  /**
   * This method is the endpoint to insert an order into the WebStore
   * The parameters of the request are passed in parameters tables
   * @param parameters 
   */
  
  public void insertWebOrders(HashMap parameters) {
    this.buildRequestAndExecute(parameters, "order");
  }
}
