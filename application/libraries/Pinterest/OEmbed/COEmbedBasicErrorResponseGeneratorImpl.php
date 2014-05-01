<?php

require_once('ICOEmbedBasicErrorResponseGenerator.php');

require_once('OEmbedResponseErrorMessage.php');

/**
 * Class implementing 4 basic reactions for oEmbed response messages
 * which are of not appropriate format or ask for unappropriate resources.
 *
 * @see ICOEmbedBasicErrorResponseGenerator
 *
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class COEmbedBasicErrorResponseGeneratorImpl implements ICOEmbedBasicErrorResponseGenerator {

    /**
     * Basic class constructor.
     */
    public function __construct() {
        
    }

    /**
     * \brief Generated oEmbed response for 401 status code.
     *
     * According to the oEmbed API specification:
     * The specified URL contains a private (non-public) resource.
     * The consumer should provide a link directly to the resource
     * instead of any embedding any extra information,
     * and rely on the provider to provide access control.
     *
     * JSON example:
     * \code
     *  {
     *      "url": "http://flickr.com/embedly",
     *      "error_code": 401,
     *      "error_message": "HTTP 401: Unauthorized",
     *      "type": "error"
     *  }
     * \endcode
     *
     * @param string $url
     *  URL address to be added to the response.
     * @return object
     *  object representing the response for the error
     */
    public function generate_401_unauthorized($url) {

        $responseMessageObj = new OEmbedResponseErrorMessage(
                        array(
                            ICOEmbedErrorKeyConstants::OEEKC_URL => $url,
                            ICOEmbedErrorKeyConstants::OEEKC_ERROR_CODE => ICOEmbedErrorValueConstants::OEEVC_ERROR_CODE_401,
                            ICOEmbedErrorKeyConstants::OEEKC_ERROR_MESSAGE => ICOEmbedErrorValueConstants::OEEVC_ERROR_MESSAGE_404,
                            ICOEmbedErrorKeyConstants::OEEKC_ERROR_TYPE => ICOEmbedErrorValueConstants::OEEC_ERROR_TYPE_VALUE
                        )
        );

        return $responseMessageObj;
    }

    /**
     * \brief Generated oEmbed response for 404 status code.
     *
     * According to the oEmbed API specification:
     * The provider has no response for the requested url parameter.
     * This allows providers to be broad in their URL scheme
     * and then determine at call time if they have a representation to return.
     *
     * JSON example:
     * \code
     *  {
     *      "url": "http://flickr.com/embedly",
     *      "error_code": 404,
     *      "error_message": "HTTP 404: Not Found",
     *      "type": "error"
     *  }
     * \endcode
     *
     * @param string $url
     *  URL address to be added to the response.
     * @return object
     *  object representing the response for the error
     */
    public function generate_404_not_found($url) {

        $responseMessageObj = new OEmbedResponseErrorMessage(
                        array(
            ICOEmbedErrorKeyConstants::OEEKC_URL => $url,
            ICOEmbedErrorKeyConstants::OEEKC_ERROR_CODE => ICOEmbedErrorValueConstants::OEEVC_ERROR_CODE_404,
            ICOEmbedErrorKeyConstants::OEEKC_ERROR_MESSAGE => ICOEmbedErrorValueConstants::OEEVC_ERROR_MESSAGE_404,
            ICOEmbedErrorKeyConstants::OEEKC_ERROR_TYPE => ICOEmbedErrorValueConstants::OEEC_ERROR_TYPE_VALUE
                )
        );

        return $responseMessageObj;
    }

    /**
     * \brief Generated oEmbed response for 500 status code.
     *
     * According to the oEmbed API specification:
     * If a server error occurs.
     *
     * JSON example:
     * \code
     *  {
     *      "url": "http://flickr.com/embedly",
     *      "error_code": 500,
     *      "error_message": "HTTP 500: Server Issues",
     *      "type": "error"
     *  }
     * \endcode
     *
     * @param string $url
     *  URL address to be added to the response.
     * @return object
     *  object representing the response for the error
     */
    public function generate_500_server_issues($url) {

        $responseMessageObj = new OEmbedResponseErrorMessage(
                        array(
            ICOEmbedErrorKeyConstants::OEEKC_URL => $url,
            ICOEmbedErrorKeyConstants::OEEKC_ERROR_CODE => ICOEmbedErrorValueConstants::OEEVC_ERROR_CODE_500,
            ICOEmbedErrorKeyConstants::OEEKC_ERROR_MESSAGE => ICOEmbedErrorValueConstants::OEEVC_ERROR_MESSAGE_500,
            ICOEmbedErrorKeyConstants::OEEKC_ERROR_TYPE => ICOEmbedErrorValueConstants::OEEC_ERROR_TYPE_VALUE
                )
        );

        return $responseMessageObj;        
    }

    /**
     * \brief Generated oEmbed response for 501 status code.
     *
     * According to the oEmbed API specification:
     * The provider cannot return a response in the requested format.
     * This should be sent when (for example) the request includes
     * format=xml and the provider doesn't support XML responses.
     * However, providers are encouraged to support both JSON and XML.
     *
     * JSON example:
     * \code
     *  {
     *      "url": "http://flickr.com/embedly",
     *      "error_code": 501,
     *      "error_message": "HTTP 501: Not Implemented",
     *      "type": "error"
     *  }
     * \endcode
     *
     * @param string $url
     *  URL address to be added to the response.
     * @return object
     *  object representing the response for the error
     */
    public function generate_501_not_implemented($url) {

        $responseMessageObj = new OEmbedResponseErrorMessage(
                        array(
            ICOEmbedErrorKeyConstants::OEEKC_URL => $url,
            ICOEmbedErrorKeyConstants::OEEKC_ERROR_CODE => ICOEmbedErrorValueConstants::OEEVC_ERROR_CODE_501,
            ICOEmbedErrorKeyConstants::OEEKC_ERROR_MESSAGE => ICOEmbedErrorValueConstants::OEEVC_ERROR_MESSAGE_501,
            ICOEmbedErrorKeyConstants::OEEKC_ERROR_TYPE => ICOEmbedErrorValueConstants::OEEC_ERROR_TYPE_VALUE
                )
        );

        return $responseMessageObj;         
    }

}

?>
