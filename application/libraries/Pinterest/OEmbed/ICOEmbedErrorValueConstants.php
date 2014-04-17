<?php

/**
 * Interface holding basic oEmbed error value constants assigned to 
 * error keys. All of values are already pre-defined.
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
 * @see ICOEmbedErrorKeyConstants
 * @author Pavol Daňo
 * @version 1.0 
 * @file
 */
interface ICOEmbedErrorValueConstants {
    /**
     * Error code 401.
     */

    const OEEVC_ERROR_CODE_401 = '401';

    /**
     * Error code 404.
     */
    const OEEVC_ERROR_CODE_404 = '404';

    /**
     * Error code 500.
     */
    const OEEVC_ERROR_CODE_500 = '500';

    /**
     * Error code 501.
     */
    const OEEVC_ERROR_CODE_501 = '501';

    /**
     * Predefined error message for code 401.
     */
    const OEEVC_ERROR_MESSAGE_401 = 'HTTP 401: Unauthorized';

    /**
     * Predefined error message for code 404.
     */
    const OEEVC_ERROR_MESSAGE_404 = 'HTTP 404: Not Found';

    /**
     * Predefined error message for code 500.
     */
    const OEEVC_ERROR_MESSAGE_500 = 'HTTP 500: Server issues';

    /**
     * Predefined error message for code 501.
     */
    const OEEVC_ERROR_MESSAGE_501 = 'HTTP 501: Not Implemented';

    /**
     * Predefined error message type value.
     */
    const OEEC_ERROR_TYPE_VALUE = 'error';

}

?>
