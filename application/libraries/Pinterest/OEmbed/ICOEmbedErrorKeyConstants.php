<?php

/**
 * Interface holding basic oEmbed error constants with values 
 * defining JSON error keys or XML error elements' names.
 * For most of keys also values are already pre-defined.
 * 
 * See also:
 * <a href="http://oembed.com/#section2">oEmbed documentation</a>  
 * 
 * Exampleee of usage:
 * \code
 *  $error_response = array(  ICOEmbedErrorKeyConstants::OEEKC_URL => $url,  ICOEmbedErrorKeyConstants::OEEKC_ERROR_CODE => ICOEmbedErrorValueConstants::OEEVC_ERROR_CODE_500,  ICOEmbedErrorKeyConstants::OEEKC_ERROR_MESSAGE => ICOEmbedErrorValueConstants::OEEVC_ERROR_MESSAGE_500,  ICOEmbedErrorKeyConstants::OEEKC_ERROR_TYPE => ICOEmbedErrorValueConstants::OEEC_ERROR_TYPE_VALUE
 *  );
 * \endcode
 * 
 * @see ICOEmbedErrorValueConstants
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
interface ICOEmbedErrorKeyConstants {
    
    /**
     * Error URL key value.
     */
    const OEEKC_URL = 'url';
    
    /**
     * Error code key value.
     */
    const OEEKC_ERROR_CODE = 'error_code';
    
    /**
     * Error message key value.
     */
    const OEEKC_ERROR_MESSAGE = 'error_message';
    
    /**
     * Type of the message.
     */
    const OEEKC_ERROR_TYPE = 'type';

}

?>
