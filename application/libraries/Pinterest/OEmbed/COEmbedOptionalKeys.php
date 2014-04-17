<?php

require_once('enumhelper/CAbstractEnum.php');
require_once('ICOEmbedOptionalResponseKeyConstants.php');

/**
 * Abstract class representinng enumeration of all oEmbed optional keys.
 * 
 * Provided because we need to find a way to check all optional keys which belong
 * to optional ones. Due to inheritance from CAbstractEnum class we have
 * got a simle but yet strong tool for asking if value (actually it is a JSON or XML key/element)
 * is allowed or not.
 * 
 * \code
 * // example of usage:
 * if ( !COEmbedOptionalKeys::isValidValue('COEmbedOptionalKeys',$key) ) {
 *      throw new CNotOptionalOEmbedKeyException("Key is not among the optional ones!");
 * }
 * \endcode
 * 
 * @see COEmbedRequiredKeys
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
abstract class COEmbedOptionalKeys extends CAbstractEnum implements ICOEmbedOptionalResponseKeyConstants{

    /**
     * Enumeration item for a title response parameter.
     */
    const OEMBED_TYPE_TITLE = ICOEmbedOptionalResponseKeyConstants::OERC_TITLE;
    
    /**
     * Enumeration item for an author name response parameter.
     */    
    const OEMBED_TYPE_AUTHOR_NAME = ICOEmbedOptionalResponseKeyConstants::OERC_AUTHOR_NAME;
    
    /**
     * Enumeration item for an author url response parameter.
     */    
    const OEMBED_TYPE_AUTHOR_URL = ICOEmbedOptionalResponseKeyConstants::OERC_AUTHOR_URL;
    
    /**
     * Enumeration item for a provider name response parameter.
     */    
    const OEMBED_TYPE_PROVIDER_NAME = ICOEmbedOptionalResponseKeyConstants::OERC_PROVIDER_NAME;
    
    /**
     * Enumeration item for a provider url response parameter.
     */    
    const OEMBED_TYPE_PROVIDER_URL = ICOEmbedOptionalResponseKeyConstants::OERC_PROVIDER_URL;
    
    /**
     * Enumeration item for a cache age response parameter.
     */    
    const OEMBED_TYPE_CACHE_AGE = ICOEmbedOptionalResponseKeyConstants::OERC_CACHE_AGE;
    
    /**
     * Enumeration item for a thumbnail url response parameter.
     */    
    const OEMBED_TYPE_THUMBNAIL_URL = ICOEmbedOptionalResponseKeyConstants::OERC_THUMBNAIL_URL;
    
    /**
     * Enumeration item for a thumbnail width response parameter.
     */    
    const OEMBED_TYPE_THUMBNAIL_WIDTH = ICOEmbedOptionalResponseKeyConstants::OERC_THUMBNAIL_WIDTH;
    
    /**
     * numeration item for a thumbnail height response parameter.
     */
    const OEMBED_TYPE_THUMBNAIL_HEIGHT = ICOEmbedOptionalResponseKeyConstants::OERC_THUMBNAIL_HEIGHT;
}

?>
