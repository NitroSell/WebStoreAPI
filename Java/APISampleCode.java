package apisamplecode;

import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import java.util.Set;
/**
 *
 * @author franclin
 */
public class APISampleCode {

  /**
   * @param args the command line arguments
   */
  public static void main(String[] args) {   

    NScAPIWrapper objAPI = new NScAPIWrapper("cdb78aa013683839d85f14adab52022f", "50c229e511ab0", "franclin20.internal.nitrosell.com");
    HashMap products = new HashMap();

    products.put("JWW003", 1);
    products.put("JWW002", 2);
    products.put("JWW001", 1);

    //objAPI.getCustomerDetails("annabel@saintbelle.fr");

    String productStr = "";

    Set set = products.entrySet();
    Iterator iter = set.iterator();

    while (iter.hasNext()) {
      Map.Entry e = (Map.Entry) iter.next();
      productStr += e.getKey() + ":" + e.getValue() + ",";
    }


    HashMap parameters = new HashMap();
    parameters.put("email", "franclin.foping@nitrosell.net");
    parameters.put("ship-firstname", "Jane");
    parameters.put("ship-lastname", "Jane");
    parameters.put("ship-fullname", "Jane Brook");
    parameters.put("ship-company", "");
    parameters.put("ship-address1", "36 Bridge St Row");
    parameters.put("ship-address2", "");
    parameters.put("ship-city", "Chester");
    parameters.put("ship-zippostcode", "UK");
    parameters.put("ship-state", "Cheshire");
    parameters.put("ship-statecode", "UK");
    parameters.put("ship-country", "United Kingdom");
    parameters.put("ship-telephone", "01244400318");
    parameters.put("ship-fax", "01789204015");
    parameters.put("ship-email","franclin.foping@nitrosell.net");
    parameters.put("products", productStr.substring(0, productStr.length() - 1));
    parameters.put("time", new Date().getTime() / 1000);
    parameters.put("userid", objAPI.getUserID());
    
    /*
     * uncomment one these lines to test different situations
    objAPI.getShippingOptions(parameters);    
    objAPI.getShippingAndTax(parameters);
    objAPI.getTaxForAnOrder(parameters);
    */ 
    
    HashMap orderDetails = new HashMap();
    orderDetails.put("email", "franclin.foping@nitrosell.net");
    orderDetails.put("cust-username", "tester");
    orderDetails.put("cust-lastname", "Testing");
    orderDetails.put("cust-address1", "10 White Friars");
    orderDetails.put("cust-city", "Manchester");
    orderDetails.put("cust-country", "United Kingdom");
    orderDetails.put("cust-zipppostcode", "M1 3EU");
    orderDetails.put("ship-firstname", "Jane");
    orderDetails.put("ship-lastname", "Jane");
    orderDetails.put("ship-fullname", "Jane Brook");
    orderDetails.put("ship-company", "");
    orderDetails.put("ship-address1", "36 Bridge St Row");
    orderDetails.put("ship-address2", "");
    orderDetails.put("ship-city", "Chester");
    orderDetails.put("ship-zippostcode", "UK");
    orderDetails.put("ship-state", "Cheshire");
    orderDetails.put("ship-statecode", "UK");
    orderDetails.put("ship-country", "United Kingdom");
    orderDetails.put("ship-telephone", "01244400318");
    orderDetails.put("ship-fax", "01789204015");
    orderDetails.put("ship-email","franclin.foping@nitrosell.net");
    orderDetails.put("products", productStr.substring(0, productStr.length() - 1));
    orderDetails.put("time", new Date().getTime() / 1000);    
    orderDetails.put("shippingid", "163");
    orderDetails.put("tenderid", "10");
    orderDetails.put("userid", objAPI.getUserID());
    
    objAPI.insertWebOrders(orderDetails);
  }
}
