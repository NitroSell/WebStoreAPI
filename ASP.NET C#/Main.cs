using System;
using System.Collections.Specialized;
using System.Linq;
using System.Text;
using System.IO;
using System.Net;
using System.Web;

namespace SampleCode {
    class APIEntry
    {  
        public static void Main(string[] args)
        {  

            NScAPIWrapper objAPI = new NScAPIWrapper("Your PRIVATE KEY HERE", "YOUR USER ID", "YOUR WEBSTORE URL");

            
            NameValueCollection products = new NameValueCollection();
            
            products.Add("JWW003", "2");
            products.Add("JWW002", "2");
            products.Add("JWW001", "2");

            string productString = "";
            foreach (String entry in products.AllKeys) 
            {
                productString += entry + ":" + products[entry] + ",";
            }

            TimeSpan time = (DateTime.UtcNow - new DateTime(1970, 1, 1, 0, 0, 0));
            long timeStamp = (long)time.TotalSeconds;

            
            NameValueCollection parameters = System.Web.HttpUtility.ParseQueryString(string.Empty);
            parameters.Add("email", "YOUR EMAIL ADDRESS");
            parameters.Add("ship-firstname", "Jane");
            parameters.Add("ship-lastname", "Jane");
            parameters.Add("ship-fullname", "Jane Brook");
            parameters.Add("ship-company", "");
            parameters.Add("ship-address1", "36 Bridge St Row");
            parameters.Add("ship-address2", "");
            parameters.Add("ship-city", "Chester");
            parameters.Add("ship-zippostcode", "UK");
            parameters.Add("ship-state", "Cheshire");
            parameters.Add("ship-statecode", "UK");
            parameters.Add("ship-country", "United Kingdom");
            parameters.Add("ship-telephone", "01244400318");
            parameters.Add("ship-fax", "01789204015");
            parameters.Add("ship-email", "YOUR EMAIL ADDRESS");
            parameters.Add("products", productString.Substring(0, productString.Length - 1));
            parameters.Add("time", timeStamp.ToString());
            parameters.Add("userid", objAPI.getUserID());

            // You can uncomment one of these methods to test the appropriate endpoint
            //objAPI.getShippingOptions(parameters);
            //objAPI.getShippingAndTax(parameters);
            //objAPI.getTaxForAnOrder(parameters);
            objAPI.getCustomerDetails("YOUR CUSTOMER EMAIL");
           
            /**
             * Order details parameters 
             */
            NameValueCollection orderDetails = System.Web.HttpUtility.ParseQueryString(string.Empty);
            orderDetails.Add("email", "YOUR EMAIL");
            orderDetails.Add("cust-username", "tester");
            orderDetails.Add("cust-lastname", "Testing");
            orderDetails.Add("cust-address1", "ADDRESS LINE 1");
            orderDetails.Add("cust-city", "Manchester");
            orderDetails.Add("cust-country", "United Kingdom");
            orderDetails.Add("cust-zipppostcode", "M1 3EU");
            orderDetails.Add("ship-firstname", "FIRSTNAME");
            orderDetails.Add("ship-lastname", "LAST NAME");
            orderDetails.Add("ship-fullname", "FIRST AND LAST NAMES");
            orderDetails.Add("ship-company", "");
            orderDetails.Add("ship-address1", "36 Bridge St Row");
            orderDetails.Add("ship-address2", "");
            orderDetails.Add("ship-city", "Chester");
            orderDetails.Add("ship-zippostcode", "UK");
            orderDetails.Add("ship-state", "Cheshire");
            orderDetails.Add("ship-statecode", "UK");
            orderDetails.Add("ship-country", "United Kingdom");
            orderDetails.Add("ship-telephone", "PHONE NUMBER");
            orderDetails.Add("ship-fax", "FAX NUMBER");
            orderDetails.Add("ship-email", "YOUR EMAIL ADDRESS");
            orderDetails.Add("products", productString.Substring(0, productString.Length - 1));
            orderDetails.Add("time", timeStamp.ToString());
            orderDetails.Add("shippingid", "163");
            orderDetails.Add("tenderid", "10");
            orderDetails.Add("userid", objAPI.getUserID());

            //objAPI.insertWebOrders(orderDetails);
            Console.ReadLine();
        }
    }
}
