<?php

require_once('enumhelper/CAbstractEnum.php');
require_once('ICOEmbedRequiredResponseKeyConstants.php');

/**
 * Abstract class representinng enumeration of all oEmbed required keys.
 * 
 * Provided because we need to find a way to check all required keys which belong
 * to required ones. Due to inheritance from \link CAbstractEnum \endlink class we have
 * got a simle but yet strong tool for asking if value (actually it is a JSON or XML key/element)
 * is allowed or not.
 * 
 * \code
 * // example of usage:
 * if ( !COEmbedRequiredKeys::isValidValue('COEmbedRequiredKeys',$key) ) {
 *      throw new CNotObligatoryOEmbedKeyException("Key is not among the obligatory ones!");
 * }
 * \endcode
 * 
 * @see COEmbedOptionalKeys
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
abstract class COEmbedRequiredKeys extends CAbstractEnum implements ICOEmbedRequiredResponseKeyConstants {
    
    /**
     * Enumeration item for a type response parameter.
     */    
    const OEMBED_TYPE_KEY = ICOEmbedRequiredResponseKeyConstants::OERC_TYPE;
    
    /**
     * Enumeration item for a version response parameter.
     */    
    const OEMBED_TYPE_VERSION = ICOEmbedRequiredResponseKeyConstants::OERC_VERSION;
}

?>
