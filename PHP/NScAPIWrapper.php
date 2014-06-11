<?php
/**
 * This class implements the different endpoints as documented
 * @author Franclin <franclin.foping@nitrosell.net>
 * 
 */
class NScAPIWrapper {

  private $sKey = null; ///< This variable will hold the private key of the user
  private $sUserID = null; ///< This variable will hold the user ID
  private $bDebug = false; ///< This boolean is useful for debugging purpose
  private $responseInfo = array(); ///< This array will be used to store the response from curl   
  private $user_agent = null; ///< This variable will contain the user agent description 
  private $webstoreUrl = null; ///< This variable will contain the URL of the WebStore

  /**
   * This is the constructor of our class. Its job is to set the member variable to their correct values
   * @param type $key  This variable will hold the private key of the user
   * @param type $userid  This variable will hold the user ID
   * @param type $webstoreUrl  This variable will contain the URL of the WebStore
   */
  public function __construct($key, $userid, $webstoreUrl) {
    $this->sKey = $key;
    $this->sUserID = $userid;
    $this->webstoreUrl = $webstoreUrl;

    $this->user_agent = "Testing user agent";
  }

  /**
   * This function sets the private key
   * @param type $key  This is the new private key
   */
  public function setPrivateKey($key) {
    $this->sKey = $key;
  }

  /**
   * This function returns the current user ID 
   * @return the user id 
   */
  public function getUserID() {
    return $this->sUserID;
  }

  /**
   * This helper method is used to process the actual HTTP request.
   * We used Curl here
   * @param type $sUrl this is the URL where the request will be sent
   * @param type $postargs this is the query string to be sent as part of the HTTP request
   * @return the body of the response from the HTTP request
   */
  private function processRequest($sUrl, $postargs = false) {
    $ch = curl_init($sUrl);

    if ($postargs) {
      $arrPostData = array();

      foreach($postargs as $key => $value) {
        $arrPostData[] = $key . '=' . $value;
      }     

      $arrPostData = implode("&", $arrPostData);

      // setting options for curl
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $arrPostData);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));//, 'Content-length: 100'));
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_NOBODY, false);

    if ($this->bDebug) {
      curl_setopt($ch, CURLOPT_HEADER, true);
    } else {
      curl_setopt($ch, CURLOPT_HEADER, false);
    }

    curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);

    $response = curl_exec($ch);

    $this->responseInfo = curl_getinfo($ch);

    curl_close($ch);

    if( $this->bDebug) {
      $debug = preg_split("#\n\s*\n|\r\n\s*\r\n#m", $response);
      echo'<pre>' . $debug[0] . '</pre>';
      exit;
    };
    return $response;
 }

 /**
  * This endpoint returns the customer's data
  * @param type $email this is the email of the customer whose details are being requested
  * @return string the JSON encoded string containing the customer data
  */
 public function getCustomerDetails($email) {
   $requestUrl = "https://api.internal.nitrosell.com/".$this->webstoreUrl."/v1/customer.json";

   $time = time();

   $arrData = array(
       'email' => $email,
       'userid' => $this->sUserID,
       'time' => $time
   );

   $hash = hash_hmac("md5", join(".", $arrData), $this->sKey);

   $queryString = "?email=".$email."&hash=".$hash."&userid=".$this->sUserID."&time=".$time;

   return $this->processRequest($requestUrl . $queryString);
 }

 /**
  * This function returns the set of shipping options for the basket
  * @param type $postargs an associative array containing the basket data
  * @return string a JSON encoded array of the shipping options
  */
 public function getShippingOptions($postargs) {
   $requestUrl = "https://api.internal.nitrosell.com/".$this->webstoreUrl."/v1/shipping.json";
   
   $arrRawPostParameters = array();
   $arrParameters = array();
   parse_str($postargs, $arrRawPostParameters);

   foreach ($arrRawPostParameters as $key => $value) {
     $arrParameters[$key] = urldecode($value);
   }

   $arrParameters['hash'] = hash_hmac("md5", join(".", $arrParameters), $this->sKey);
   
   return $this->processRequest($requestUrl, $arrParameters);
 }
 
 
 /**
  * This endpoint returns the amount of applicable tax for the basket
  * @param type $postargs the basket data
  * @return string the amount of tax in a JSON encoded string
  */
 public function getTaxForAnOrder($postargs) {
   $requestUrl = "https://api.internal.nitrosell.com/".$this->webstoreUrl."/v1/tax.json";
   $arrRawPostParameters = array();
   $arrParameters = array();
   parse_str($postargs, $arrRawPostParameters);

   foreach ($arrRawPostParameters as $key => $value) {
     $arrParameters[$key] = urldecode($value);
   }
   
   $arrParameters['hash'] = hash_hmac("md5", join(".", $arrParameters), $this->sKey);
   
   return $this->processRequest($requestUrl, $arrParameters);
 }


 /**
  * This function performs the actual order insertion into the database
  * @param array $param an associative array containing the basket details
  * @return string 
  */
 public function insertWebOrders($param) {
   $requestUrl = "https://api.internal.nitrosell.com/".$this->webstoreUrl."/v1/order.json";
   $arrRawPostParameters = array();
   $arrParameters = array();
   parse_str($param, $arrRawPostParameters);

   foreach ($arrRawPostParameters as $key => $value) {
     $arrParameters[$key] = urldecode($value);
   }
   
   $arrParameters['hash'] = hash_hmac("md5", join(".", $arrParameters), $this->sKey);
   
   return $this->processRequest($requestUrl, $arrParameters);
 }
 
 /**
  * This endpoint computes and returns the amount of tax and shipping options applicable to a basket
  * @param type $postargs this is an associate array containing the description of the basket
  * @return string a JSON encoded string containing both the amount of tax and shipping options applicable to a basket
  */
 public function getShippingAndTax($postargs) {
   $requestUrl = "https://api.internal.nitrosell.com/".$this->webstoreUrl."/v1/shippingandtax.json";
   $arrRawPostParameters = array();
   $arrParameters = array();
   parse_str($postargs, $arrRawPostParameters);

   foreach ($arrRawPostParameters as $key => $value) {
     $arrParameters[$key] = urldecode($value);
   }
   
   $arrParameters['hash'] = hash_hmac("md5", join(".", $arrParameters), $this->sKey);
   
   return $this->processRequest($requestUrl, $arrParameters);
 }

}
?>
