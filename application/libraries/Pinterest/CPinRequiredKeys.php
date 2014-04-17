<?php

require_once('OEmbed/enumhelper/CAbstractEnum.php');
require_once('ICPinRequiredResponseKeyConstants.php');

/**
 * Abstract class representinng enumeration of all Pinterest required keys.
 * 
 * Provided because we need to find a way to check all required keys which belong
 * to required ones. Due to inheritance from CAbstractEnum class we have
 * got a simle but yet strong tool for asking if value (actually it is a JSON or XML key/element)
 * is allowed or not.
 * 
 * \code
 * // example of usage:
 * if ( !CPinRequiredKeys::isValidValue('CPinRequiredKeys',$key) ) {
 *      throw new CInvalidRequiredPinKeyException("Key is not among the required ones!");
 * }
 * \endcode
 * 
 * @see CPinOptionalKeys
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
abstract class CPinRequiredKeys extends CAbstractEnum implements ICPinRequiredResponseKeyConstants {
    
    /**
     * Enumeration item for an URL response parameter.
     */     
    const PIN_TYPE_URL = ICPinRequiredResponseKeyConstants::PRRKC_URL;
    
    /**
     * Enumeration item for a title response parameter.
     */     
    const PIN_TYPE_TITLE = ICPinRequiredResponseKeyConstants::PRRKC_TITLE;
    
    /**
     * Enumeration item for a price response parameter.
     */     
    const PIN_TYPE_PRICE = ICPinRequiredResponseKeyConstants::PRRKC_PRICE;
    
    /**
     * Enumeration item for a currency code response parameter.
     */     
    const PIN_TYPE_CURRENCY_CODE = ICPinRequiredResponseKeyConstants::PRRKC_CURRENCY_CODE;
}
?>
