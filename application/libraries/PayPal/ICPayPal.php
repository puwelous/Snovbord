<?php

/**
 * Interface declaring all call methods assigned semantically to PayPal API calls.
 * In this case the focus is aimed only on "sale" type of the call.
 * Following calls are covered:
 * - SetExpressCheckout,
 * - GetExpressCheckoutDetails,,
 * - DoDirectPayment,
 * - DoExpressCheckoutPayment.
 * 
 * 
 * @author Pavol Daňo, PayPal API
 * @version 1.0
 * @file
 */
interface ICPayPal {

    /**
     * Prepares the parameters for the SetExpressCheckout API Call.
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
     *  API call results in a form of an array
     */
    function CallShortcutExpressCheckout($paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL);

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
    function CallMarkExpressCheckout($paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL, $shipToName, $shipToStreet, $shipToCity, $shipToState, $shipToCountryCode, $shipToZip, $shipToStreet2, $phoneNum);

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
    function GetShippingDetails($token);

    /**
     * Prepares the parameters for the GetExpressCheckoutDetails API Call.
     * 
     * @param string $FinalPaymentAmt
     *  Final payment amount.
     * @retval array
     *  API call results in a form of an array. The NVP Collection object of the GetExpressCheckoutDetails Call Response
     */
    function ConfirmPayment($FinalPaymentAmt);

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
    function DirectPayment($paymentType, $paymentAmount, $creditCardType, $creditCardNumber, $expDate, $cvv2, $firstName, $lastName, $street, $city, $state, $zip, $countryCode, $currencyCode);

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
    function hash_call($methodName, $nvpStr);
}

?>
