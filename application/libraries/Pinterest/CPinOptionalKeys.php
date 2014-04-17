<?php

require_once('OEmbed/enumhelper/CAbstractEnum.php');
require_once('ICPinOptionalResponseKeyConstants.php');

/**
 * Abstract class representinng enumeration of all Pinterest optional keys.
 * 
 * Provided because we need to find a way to check all optional keys which belong
 * to optional ones. Due to inheritance from CAbstractEnum class we have
 * got a simle but yet strong tool for asking if value (actually it is a JSON or XML key/element)
 * is allowed or not.
 * 
 * \code
 * // example of usage:
 * if ( !CPinOptionalKeys::isValidValue('CPinOptionalKeys',$key) ) {
 *      throw new CInvalidOptionalPinKeyException("Key is not among the optional ones!");
 * }
 * \endcode
 * 
 * @see CPinRequiredKeys
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
abstract class CPinOptionalKeys extends CAbstractEnum implements ICPinOptionalResponseKeyConstants {

    /**
     * Enumeration item for a provider name response parameter.
     */    
    const PIN_TYPE_PROVIDER_NAME = ICPinOptionalResponseKeyConstants::PORKC_PROVIDER_NAME;
    
    /**
     * Enumeration item for a description response parameter.
     */    
    const PIN_TYPE_DESCRIPTION = ICPinOptionalResponseKeyConstants::PORKC_DESCRIPTION;
    
    /**
     * Enumeration item for a brand response parameter.
     */    
    const PIN_TYPE_BRAND = ICPinOptionalResponseKeyConstants::PORKC_BRAND;
    
    /**
     * Enumeration item for a product id response parameter.
     */       
    const PIN_TYPE_PRODUCT_ID = ICPinOptionalResponseKeyConstants::PORKC_PRODUCT_ID;
    
    /**
     * Enumeration item for an availability response parameter.
     */       
    const PIN_TYPE_AVAILABILITY = ICPinOptionalResponseKeyConstants::PORKC_AVAILABILITY;
    
    /**
     * Enumeration item for a quantity response parameter.
     */       
    const PIN_TYPE_QUANTITY = ICPinOptionalResponseKeyConstants::PORKC_QUANTITY;
    
    /**
     * Enumeration item for a standard price response parameter.
     */       
    const PIN_TYPE_STANDARD_PRICE = ICPinOptionalResponseKeyConstants::PORKC_STANDARD_PRICE;
    
    /**
     * Enumeration item for a start date of sale response parameter.
     */       
    const PIN_TYPE_SALES_START_DATE = ICPinOptionalResponseKeyConstants::PORKC_SALES_START_DATE;
    
    /**
     * Enumeration item for an end date of sale response parameter.
     */       
    const PIN_TYPE_SALES_END_DATE = ICPinOptionalResponseKeyConstants::PORKC_SALES_END_DATE;
    
    /**
     * Enumeration item for a product expiration response parameter.
     */       
    const PIN_TYPE_PRODUCT_EXPIRATION = ICPinOptionalResponseKeyConstants::PORKC_PRODUCT_EXPIRATION;
    
    /**
     * Enumeration item for a gender response parameter.
     */       
    const PIN_TYPE_GENDER = ICPinOptionalResponseKeyConstants::PORKC_GENDER;
    
    /**
     * Enumeration item for a geographic availability response parameter.
     */       
    const PIN_TYPE_GEO_AVAILABILITY = ICPinOptionalResponseKeyConstants::PORKC_GEO_AVAILABILITY;
    
    /**
     * Enumeration item for a color response parameter.
     */       
    const PIN_TYPE_COLOR = ICPinOptionalResponseKeyConstants::PORKC_COLOR;
    
    /**
     * Enumeration item for images response parameter.
     */       
    const PIN_TYPE_IMAGES = ICPinOptionalResponseKeyConstants::PORKC_IMAGES;
    
    /**
     * Enumeration item for a related items response parameter.
     */       
    const PIN_TYPE_RELATED_ITEMS = ICPinOptionalResponseKeyConstants::PORKC_RELATED_ITEMS;
    
    /**
     * Enumeration item for referenced items response parameter.
     */       
    const PIN_TYPE_REFERENCED_ITEMS = ICPinOptionalResponseKeyConstants::PORKC_REFERENCED_ITEMS;
    
    /**
     * Enumeration item for a rating response parameter.
     */       
    const PIN_TYPE_RATING = ICPinOptionalResponseKeyConstants::PORKC_RATING;
    
    /**
     * Enumeration item for a rating scale response parameter.
     */       
    const PIN_TYPE_RATING_SCALE = ICPinOptionalResponseKeyConstants::PORKC_RATING_SCALE;
    
    /**
     * Enumeration item for rating count response parameter.
     */       
    const PIN_TYPE_RATING_COUNT = ICPinOptionalResponseKeyConstants::PORKC_RATING_COUNT;
}

?>
