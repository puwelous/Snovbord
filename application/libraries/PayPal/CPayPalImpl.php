<?php

/**
 * Basic interface that this class implements.
 */
require_once('ICPayPal.php');

/**
 * Constants interfaces.
 */
require_once('ICPayPalEndpointConstants.php');
require_once('ICPayPalURLConstants.php');
require_once('ICPayPalProxySettingsConstants.php');

/**
 * Settings interface and holder.
 */
require_once('ICPayPalUserSettingsHolder.php');
require_once('CPayPalUserSettingsHolderImpl.php');

/**
 * Class imeplementing ICPinterest interface. It is responsible for:
 * - embedding Pinterest required key/value pair into response,
 * - embedding Pinterest optional key/value pair into response,
 * - embedding any key/value pair into response.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class CPayPalImpl implements ICPayPal, ICPayPalEndpointConstants, ICPayPalURLConstants, ICPayPalProxySettingsConstants {

    /**
     * PayPal API endpoint
     * 
     * @var string $API_Endpoint
     */

    protected $API_Endpoint;

    /**
     * PayPal URL endpoint
     * @var string $PAYPAL_URL
     */
    protected $PAYPAL_URL;

    /**
     * Proxy flag
     * @var string $USE_PROXY
     */
    private $USE_PROXY;

    /**
     * Proxy host. Used if proxy flag is set to true
     * @var string $PROXY_HOST
     */
    private $PROXY_HOST;

    /**
     * Proxy port. Used if proxy flag is set to true
     * @var string $PROXY_PORT
     */
    private $PROXY_PORT;

    /**
     * Interface holding getter methods for gathering PayPal user settings to call API at all.
     * @var object $iPayPalUserSettingsHolder
     */
    private $iPayPalUserSettingsHolder;

    /**
     * PayPal button source.
     */

    const PAYPAL_BUTTON_SOURCE = "PP-ECWizard";

    /**
     *
     * @var string $version
     *  PayPal calls version number. 
     */
    private $version;

    /**
     * Constructor with basic params which are necessary.
     * 
     * @param array $params
     *  Array of params passed from caller. It has to contain sandbox flag and API settings.
     */
    public function __construct($params) {

        if (!array_key_exists('sandboxFlag', $params) || !array_key_exists('api_username', $params) || !array_key_exists('api_password', $params) || !array_key_exists('api_signature', $params)) {
            throw new CInvalidPayPalArgumentException('Parameters not defined properly!');
        }

        if ($params['sandboxFlag'] == true) {
            $this->API_Endpoint = self::PPEC_API_ENDPOINT_SANDBOX;
            $this->PAYPAL_URL = self::PPUC_URL_SANDBOX;
        } else {
            $this->API_Endpoint = self::PPEC_API_ENDPOINT_LIVESITE;
            $this->PAYPAL_URL = self::PPUC_URL_LIVESITE;
        }

        $this->iPayPalUserSettingsHolder = new CPayPalUserSettingsHolderImpl($params['api_username'], $params['api_password'], $params['api_signature']);

        $this->USE_PROXY = self::PPPSC_USE_PROXY_FLAG;

        $this->PROXY_HOST = self::PPPSC_PROXY_HOST;
        $this->PROXY_PORT = self::PPSC_PROXY_PORT;

        $this->version = "93";

        if (session_id() == "")
            session_start();
    }

    /**
     * An express checkout transaction starts with a token, that identifies to PayPal your transaction.
     * This method prepares the parameters for the SetExpressCheckout API Call.
     * 
     * @param string $paymentAmount
     *  Payment amount. Total value of the required payment (shopping cart)
     * @param string $currencyCodeType
     *  Currency code type. EUR or USA or as PayPal API defines
     * @param string $paymentType
     *  The type of the paymet. In this library only "SALE" paymnents are supported
     * @param string $returnURL
     *  URL for PayPal to redirect user after successfull procedure
     * @param string $cancelURL
     *  URL for PayPal to redirect user after non-successfull procedure
     * @retval array
     *  API call results in a form of an array.
     */
    function CallShortcutExpressCheckout($paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL) {
        $nvpstr = "&PAYMENTREQUEST_0_AMT=" . $paymentAmount;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_PAYMENTACTION=" . $paymentType;
        $nvpstr = $nvpstr . "&RETURNURL=" . $returnURL;
        $nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_CURRENCYCODE=" . $currencyCodeType;

        $_SESSION["currencyCodeType"] = $currencyCodeType;
        $_SESSION["PaymentType"] = $paymentType;


        // Make the API call to PayPal
        // If the API call succeded, then redirect the buyer to PayPal to begin to authorize payment.  
        // If an error occured, show the resulting errors

        $resArray = $this->hash_call("SetExpressCheckout", $nvpstr);
        $ack = strtoupper($resArray["ACK"]);
        if ($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {
            $token = urldecode($resArray["TOKEN"]);
            $_SESSION['TOKEN'] = $token;
        }

        return $resArray;
    }

    /**
     * Prepares the parameters for the SetExpressCheckout API Call.
     * 
     * @param string $paymentAmount
     *  Total value of the shopping cart.
     * @param string $currencyCodeType
     *  Currency code value the PayPal API.
     * @param string $paymentType
     *  paymentType has to be one of the following values: Sale or Order or Authorization
     * @param string $returnURL
     *  The page where buyers return to after they are done with the payment review on PayPal
     * @param string $cancelURL
     *  The page where buyers return to when they cancel the payment review on PayPal
     * @param string $shipToName
     *  The Ship to name entered on the merchant's site
     * @param string $shipToStreet
     *  The Ship to Street entered on the merchant's site
     * @param string $shipToCity
     *  The Ship to City entered on the merchant's site
     * @param string $shipToState
     *  The Ship to State entered on the merchant's site
     * @param string $shipToCountryCode
     *  The Code for Ship to Country entered on the merchant's site
     * @param string $shipToZip
     *  The Ship to ZipCode entered on the merchant's site
     * @param string $shipToStreet2
     *  The Ship to Street2 entered on the merchant's site
     * @param string $phoneNum
     *  The phoneNum  entered on the merchant's site
     * @retval array
     *  API call results in a form of an array.
     */
    function CallMarkExpressCheckout($paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL, $shipToName, $shipToStreet, $shipToCity, $shipToState, $shipToCountryCode, $shipToZip, $shipToStreet2, $phoneNum
    ) {
        $nvpstr = "&PAYMENTREQUEST_0_AMT=" . $paymentAmount;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_PAYMENTACTION=" . $paymentType;
        $nvpstr = $nvpstr . "&RETURNURL=" . $returnURL;
        $nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_CURRENCYCODE=" . $currencyCodeType;
        $nvpstr = $nvpstr . "&ADDROVERRIDE=1";
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTONAME=" . $shipToName;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOSTREET=" . $shipToStreet;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOSTREET2=" . $shipToStreet2;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOCITY=" . $shipToCity;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOSTATE=" . $shipToState;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE=" . $shipToCountryCode;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOZIP=" . $shipToZip;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOPHONENUM=" . $phoneNum;

        $_SESSION["currencyCodeType"] = $currencyCodeType;
        $_SESSION["PaymentType"] = $paymentType;


        // Make the API call to PayPal
        // If the API call succeded, then redirect the buyer to PayPal to begin to authorize payment.  
        // If an error occured, show the resulting errors

        $resArray = $this->hash_call("SetExpressCheckout", $nvpstr);
        $ack = strtoupper($resArray["ACK"]);
        if ($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {
            $token = urldecode($resArray["TOKEN"]);
            $_SESSION['TOKEN'] = $token;
        }

        return $resArray;
    }

    /**
     * Prepares the parameters for the GetExpressCheckoutDetails API Call.
     * 
     * At this point, the buyer has completed authorizing the payment
     * at PayPal.  The function will call PayPal to obtain the details
     * of the authorization, incuding any shipping information of the
     * buyer.  Remember, the authorization is not a completed transaction
     * at this state - the buyer still needs an additional step to finalize
     * the transaction.
     * 
     * @param string $token
     *  Token received from previous call.
     * @retval array
     *  API call results in a form of an array. The NVP Collection object of the GetExpressCheckoutDetails Call Response
     */
    function GetShippingDetails($token) {

        // Build a second API request to PayPal, using the token as the
        //  ID to get the details on the payment authorization

        $nvpstr = "&TOKEN=" . $token;

        // Make the API call and store the results in an array.  
        // If the call was a success, show the authorization details, and provide
        // an action to complete the payment.  
        // If failed, show the error

        $resArray = $this->hash_call("GetExpressCheckoutDetails", $nvpstr);
        $ack = strtoupper($resArray["ACK"]);
        if ($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {
            $_SESSION['payer_id'] = $resArray['PAYERID'];
        }
        return $resArray;
    }


    /**
     * Prepares the parameters for the GetExpressCheckoutDetails API Call.
     * 
     * @param string $FinalPaymentAmt
     *  Final payment amount.
     * @retval array
     *  API call results in a form of an array. The NVP Collection object of the GetExpressCheckoutDetails Call Response
     */
    function ConfirmPayment($FinalPaymentAmt) {
        /* Gather the information to make the final call to
          finalize the PayPal payment.  The variable nvpstr
          holds the name value pairs
         */

        //Format the other parameters that were stored in the session from the previous calls	
        $token = urlencode($_SESSION['TOKEN']);
        $paymentType = urlencode($_SESSION['PaymentType']);
        $currencyCodeType = urlencode($_SESSION['currencyCodeType']);
        $payerID = urlencode($_SESSION['payer_id']);

        $serverName = urlencode($_SERVER['SERVER_NAME']);

        $nvpstr = '&TOKEN=' . $token . '&PAYERID=' . $payerID . '&PAYMENTREQUEST_0_PAYMENTACTION=' . $paymentType . '&PAYMENTREQUEST_0_AMT=' . $FinalPaymentAmt;
        $nvpstr .= '&PAYMENTREQUEST_0_CURRENCYCODE=' . $currencyCodeType . '&IPADDRESS=' . $serverName;

        /* Make the call to PayPal to finalize payment
          If an error occured, show the resulting errors
         */
        $resArray = $this->hash_call("DoExpressCheckoutPayment", $nvpstr);

        /* Display the API response back to the browser.
          If the response from PayPal was a success, display the response parameters'
          If the response was an error, display the errors received using APIError.php.
         */
        $ack = strtoupper($resArray["ACK"]);

        return $resArray;
    }

    /**
     * This function makes a DoDirectPayment API call.
     * 
     * @param string $paymentType
     *  paymentType has to be one of the following values: Sale or Order or Authorization
     * @param string $paymentAmount
     *  Total value of the shopping cart.
     * @param string $creditCardType
     *  Buyer's credit card type (i.e. Visa, MasterCard ... )
     * @param string $creditCardNumber
     *  Buyers credit card number without any spaces, dashes or any other characters
     * @param string $expDate
     *  credit card expiration date
     * @param string $cvv2
     *  Card Verification Value
     * @param string $firstName
     *  First name as it appears on credit card
     * @param string $lastName
     *  Last name as it appears on credit card
     * @param string $street
     *  Buyer's street address line as it appears on credit card
     * @param string $city
     *  Buyer's city
     * @param string $state
     *  Buyer's state
     * @param string $zip
     *  Buyer's zip
     * @param string $countryCode
     *  Buyer's country code
     * @param string $currencyCode
     *  Currency code value the PayPal API
     * @retval array
     *  API call results in a form of an array. The NVP Collection object of the GetExpressCheckoutDetails Call Response
     */
    function DirectPayment($paymentType, $paymentAmount, $creditCardType, $creditCardNumber, $expDate, $cvv2, $firstName, $lastName, $street, $city, $state, $zip, $countryCode, $currencyCode) {
        //Construct the parameter string that describes DoDirectPayment
        $nvpstr = "&AMT=" . $paymentAmount;
        $nvpstr = $nvpstr . "&CURRENCYCODE=" . $currencyCode;
        $nvpstr = $nvpstr . "&PAYMENTACTION=" . $paymentType;
        $nvpstr = $nvpstr . "&CREDITCARDTYPE=" . $creditCardType;
        $nvpstr = $nvpstr . "&ACCT=" . $creditCardNumber;
        $nvpstr = $nvpstr . "&EXPDATE=" . $expDate;
        $nvpstr = $nvpstr . "&CVV2=" . $cvv2;
        $nvpstr = $nvpstr . "&FIRSTNAME=" . $firstName;
        $nvpstr = $nvpstr . "&LASTNAME=" . $lastName;
        $nvpstr = $nvpstr . "&STREET=" . $street;
        $nvpstr = $nvpstr . "&CITY=" . $city;
        $nvpstr = $nvpstr . "&STATE=" . $state;
        $nvpstr = $nvpstr . "&COUNTRYCODE=" . $countryCode;
        $nvpstr = $nvpstr . "&IPADDRESS=" . $_SERVER['REMOTE_ADDR'];

        $resArray = $this->hash_call("DoDirectPayment", $nvpstr);

        return $resArray;
    }
    
    /**
     * Function to perform the API call to PayPal using API signature.
     * 
     * @param string $methodName
     *  name of API  method.
     * @param string $nvpStr
     *  nvp string.
     * @retval array
     *  API call results in a form of an array. Associtive array containing the response from the server
     */
    function hash_call($methodName, $nvpStr) {
        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        //turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
        //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
        if ($this->USE_PROXY)
            curl_setopt($ch, CURLOPT_PROXY, $this->PROXY_HOST . ":" . $this->PROXY_PORT);

        //NVPRequest for submitting to server
        $nvpreq = "METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode($this->version) . "&PWD=" . urlencode($this->iPayPalUserSettingsHolder->get_paypal_user_password()) . "&USER=" . urlencode($this->iPayPalUserSettingsHolder->get_paypal_user_name()) . "&SIGNATURE=" . urlencode($this->iPayPalUserSettingsHolder->get_paypal_user_signature()) . $nvpStr . "&BUTTONSOURCE=" . urlencode(self::PAYPAL_BUTTON_SOURCE);

        //setting the nvpreq as POST FIELD to curl
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

        //getting response from server
        $response = curl_exec($ch);

        //convrting NVPResponse to an Associative Array
        $nvpResArray = $this->deformatNVP($response);
        $nvpReqArray = $this->deformatNVP($nvpreq);
        $_SESSION['nvpReqArray'] = $nvpReqArray;

        if (curl_errno($ch)) {
            // moving to display page to display curl errors
            $_SESSION['curl_error_no'] = curl_errno($ch);
            $_SESSION['curl_error_msg'] = curl_error($ch);

            //Execute the Error handling module to display errors. 
        } else {
            //closing the curl
            curl_close($ch);
        }

        return $nvpResArray;
    }

    /**
     * Purpose: Redirects to PayPal site.
     * 
     * @param string $token
     *  NVP string.
     */
    function RedirectToPayPal($token) {

        $payPalURL = $this->PAYPAL_URL . $token;
        header("Location: " . $payPalURL);
        exit;
    }

    /**
     * This function will take NVPString and convert it to an Associative Array and it will decode the response.
     * It is usefull to search for a particular key and displaying arrays.
     * 
     * @param string $nvpstr
     *  NVPString.
     * @retval array
     *  Associative Array.
     */
    function deformatNVP($nvpstr) {
        $intial = 0;
        $nvpArray = array();

        while (strlen($nvpstr)) {
            //postion of Key
            $keypos = strpos($nvpstr, '=');
            //position of value
            $valuepos = strpos($nvpstr, '&') ? strpos($nvpstr, '&') : strlen($nvpstr);

            /* getting the Key and Value values and storing in a Associative Array */
            $keyval = substr($nvpstr, $intial, $keypos);
            $valval = substr($nvpstr, $keypos + 1, $valuepos - $keypos - 1);
            //decoding the respose
            $nvpArray[urldecode($keyval)] = urldecode($valval);
            $nvpstr = substr($nvpstr, $valuepos + 1, strlen($nvpstr));
        }
        return $nvpArray;
    }
}

?>
