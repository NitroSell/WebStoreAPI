/*
* This file contains the implementation of the main class for the API
* The class contains five public methods to access each endpoint of the API and a handful of private methods
* Each method is documented below
* @author franclin
* @date 04/02/2013
*/

using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Security.Cryptography;
using System.Security;
using System.Collections.Specialized;
using System.Net;
using System.IO;
using System.Web;
namespace SampleCode
{
    class NScAPIWrapper
    {
        private string _key;
        private string _userid;
        private string _webstoreUrl;
        static readonly Encoding encoding = Encoding.UTF8;
        private HMACMD5 _hmacmd5 = new HMACMD5();

        static string ByteToString(byte[] buff)
        {
            string binary = "";

            for (int i = 0; i < buff.Length; i++)
            {
                binary += buff[i].ToString("X2");
            }

            return binary.ToLower();
        }

        /**
        *
        * This is the constructor of the class
        * Three parameters are needed to build an object of this type
        *
        * @param key this represents your private. You should contact the NitroSell Support Team to request one.
        * @param userid this represents your User ID as assigned by the retailer to which you would like to query his data
        * @param webstoreUrl this is the URL of the WebStore that you would like to query
        */
        public NScAPIWrapper(string key, string userid, string webstoreUrl)
        {
            this._key = key;
            this._userid = userid;
            this._webstoreUrl = webstoreUrl;

            var keyByte = encoding.GetBytes(key);
            this._hmacmd5 = new HMACMD5(keyByte);          
        }

        /**
         * This method builds an HTTP request and executes it.
         * @param parameters this is a collection holding the key value pair of the data to be sent as part of the request
         * @param action this is the resource (or the endpoint) we are looking for. It could be customer, shipping or order
         */ 
        private void buildRequestAndExecute(NameValueCollection parameters, string action) 
        {
            string toencode = "";

            foreach(String entry in parameters.AllKeys) 
            {
                toencode += parameters[entry] + ".";
            }

            toencode = toencode.Substring(0, toencode.Length - 1);
            var messageBytes = encoding.GetBytes(toencode);

            this._hmacmd5.ComputeHash(messageBytes);

            parameters.Add("hash",ByteToString(this._hmacmd5.Hash));

            HttpWebRequest request = (HttpWebRequest) WebRequest.Create("http://api.internal.nitrosell.com/" + this._webstoreUrl + "/v1/"+action+".json");
            request.Method = "POST";
            
            string postData = parameters.ToString();
            byte[] byteArray = encoding.GetBytes(postData);

            request.ContentType = "application/x-www-form-urlencoded";
            request.ContentLength = byteArray.Length;
            request.UserAgent = "Mozilla/5.0";

            Stream dataStream = request.GetRequestStream();
            dataStream.Write(byteArray, 0, byteArray.Length);

            dataStream.Close();

            try 
            {
                WebResponse response = request.GetResponse();
                // Display the status.
                Console.WriteLine(((HttpWebResponse)response).StatusDescription);

                // Get the stream containing content returned by the server.
                dataStream = response.GetResponseStream();
                // Open the stream using a StreamReader for easy access.
                StreamReader reader = new StreamReader(dataStream);
                // Read the content.
                string responseFromServer = reader.ReadToEnd();
                // Display the content.
                Console.WriteLine(responseFromServer);
                // Clean up the streams.
                reader.Close();
                dataStream.Close();
                response.Close();
            } catch(WebException e) 
            {
                Console.WriteLine("Error in the request Code : {0}", e.Message);
            }
        }


        /**
         * This function return the user ID
         */ 
        public string getUserID() 
        {
            return this._userid;              
        }


        /**
         * This function returns the customer's details
         * @param email this is the email address of the customer we are looking for
         */ 
        public void getCustomerDetails(string email) 
        {
            TimeSpan time = (DateTime.UtcNow - new DateTime(1970, 1, 1, 0, 0, 0));

            string message = email + "." + this._userid + "." + (long)time.TotalSeconds;
            var messageBytes = encoding.GetBytes(message);

            this._hmacmd5.ComputeHash(messageBytes);
            HttpWebRequest request = (HttpWebRequest) WebRequest.Create("http://api.internal.nitrosell.com/" + this._webstoreUrl + "/v1/customer.json?email=" + email + "&hash=" + ByteToString(this._hmacmd5.Hash) + "&userid=" + this._userid + "&time=" + (long)time.TotalSeconds);

            request.UserAgent = "Mozilla/5.0";
            request.Method = "GET";
           
            try
            {
               
                HttpWebResponse response = (HttpWebResponse)request.GetResponse();
                Stream objStream = request.GetResponse().GetResponseStream();
                StreamReader objReader = new StreamReader(objStream);
                Console.WriteLine(objReader.ReadToEnd());
 
            }                
                 
            catch (WebException e) 
            {
                Console.WriteLine("Error in the request Code : {0}", e.Message);
            }
           
        }

        /**
         * This method returns a JSON encoded string of the set of possible shipping options
         * @param parameters this is a collection of the key value pairs that will be sent with the HTTP request
         */ 
        public void getShippingOptions(NameValueCollection parameters) 
        {
            this.buildRequestAndExecute(parameters, "shipping");
        }


        /**
         * This method returns a JSON encoded string of the set of possible options and any applicable tax of a given basket
         * @param parameters this is a collection of the key value pairs that will be sent with the HTTP request
         */ 
        public void getShippingAndTax(NameValueCollection parameters) 
        {
            this.buildRequestAndExecute(parameters, "shippingandtax");
        }


        /**
         * This method returns a JSON encoded string of the amount of applicable tax to a given basket
         * @param parameters this a key value pairs of the details that will be sent with the HTTP request
         */ 
        public void getTaxForAnOrder(NameValueCollection parameters) 
        {
            this.buildRequestAndExecute(parameters, "tax");
        }

        /**
         * This methods returns a JSON encoded string indicating whether or not the order insertion was successful
         * @param parameters is a collection containing the order details
         */ 
        public void insertWebOrders(NameValueCollection parameters) 
        {
            this.buildRequestAndExecute(parameters, "order");
        }
    }
}
