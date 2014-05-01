<?php

require_once('ICOEmbed.php');

require_once('ICOEmbedErrorKeyConstants.php');
require_once('ICOEmbedErrorValueConstants.php');

require_once('ICOEmbedVersionValueConstants.php');
require_once('ICOEmbedTypeValueConstants.php');

//require_once('COEmbedBasicErrorResponseGeneratorImpl.php');

require_once('COEmbedRequiredKeys.php');
require_once('COEmbedOptionalKeys.php');

require_once('CInvalidRequiredOEmbedKeyException.php');
require_once('CInvalidOptionalOEmbedKeyException.php');

/**
 * Class imeplementing ICOEmbed interface. It is responsible for:
 * - URL check
 * - URL prefix filtration
 * - embedding oEmbed required key/value pair into response,
 * - embedding oEmbed optional key/value pair into response,
 * - embedding any key/value pair into response.
 * 
 * Example of usage:
 * \code
 * // let's say accepted URL schema is set to http://my.domain.com/products/
 * $input_url = 'http://my.domain.com/products/id/30';
 * 
 * $url_without_expected_part = $this->cpinterestimpl->remove_expected_part($input_url);
 * 
 * echo $url_without_expected_part; // should print 'id/30'
 * \endcode
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class COEmbedImpl implements ICOEmbed {

    
    /**
     * Constant inditacting the key name used in constructor call getting the parameters of calling environment (class, controller).
     */
    const ACCEPTED_URL_SCHEME_KEY_VALUE = 'accepted_url_scheme';
    
    /**
     * @var string $_acceptedUrlScheme
     *  URL scheme to be accepted. All oEmbed request calls meeting this schema should be accepted.
     */
    private $_acceptedUrlScheme = '';

    /**
     * Constructor of COEmbedImpl class.
     * 
     * @param string $params
     *  URL schema which is supposed to be accepted by the oEmbed service and this helper.
     *  
     */
    public function __construct($params) {
        //parent::__construct();
        $this->_acceptedUrlScheme = $params[self::ACCEPTED_URL_SCHEME_KEY_VALUE];
    }

    /**
     * Setter for accepted URL schema.
     * 
     * @param string $acceptedURLScheme
     *  URL as a string to bound with oEmbed service controller.
     */
    public function set_accepted_url_scheme($acceptedURLScheme) {
        $this->_acceptedUrlScheme = $acceptedURLScheme;
    }

    /**
     * Getter for accepted URL schema.
     * 
     * @retval string
     *  URL schema which is accepted by oEmbed service controller by.
     */    
    public function get_accepted_url_scheme() {
        return $this->_acceptedUrlScheme;
    }

    /**
     * Checks URL passed as input parameter if obeys or does not obey defined rules.
     * Rules is actually the accepted URL schema which class implementing 
     * this interface is bound to.
     * 
     * @param string $urlToBeChecked
     *  URL schema from request to be checked.
     * @retval boolean
     *  true or false value according to the comparison of accepted URL schema and passed argument.
     */    
    public function check_url_from_get($urlToBeChecked) {
        if (!isset($this->_acceptedUrlScheme)) {
            throw new CAcceptedUrlNotInitializedException("Accepted URL has been not defined yet! Usage before appropriate initialization.", -1, NULL);
        }

        if (substr($urlToBeChecked, 0, strlen($this->_acceptedUrlScheme)) === $this->_acceptedUrlScheme) {

            return TRUE;
        }

        // someone tries to reach not allowed URL
        return FALSE;
    }
    
    /**
     * Removes URL prefix according to the accepted URL schema which class implementing 
     * this interface is bound to.
     * 
     * @param string $full_url
     *  URL address from a request including accepted URL schema (prefix).
     * @retval string
     *  URL address without the prefix.
     */    
    public function remove_expected_part($full_url) {
        if (!isset($this->_acceptedUrlScheme)) {
            throw new CAcceptedUrlNotInitializedException("Accepted URL has been not defined yet! Usage before appropriate initialization.", -1, NULL);
        }

        $url_without_prefix = str_replace($this->_acceptedUrlScheme, "", $full_url);

        return $url_without_prefix;
    }

    /**
     * Adds required keys and assigned values to the response including checking whether the keys
     * are required at all.
     * 
     * @param object $responseObject
     *  Actual response object to put keys and values into.
     * @param array $key_value_items_array
     *  Array including required keys and values to be added into the response message.
     * @retval object
     *  Updated response object with a new keys and valuse in a form of an array.
     */    
    public function add_oembed_required_response_items($responseObject, $key_value_items_array) {

        // check keys
        foreach ($key_value_items_array as $key => $value) {
            if ( !COEmbedRequiredKeys::isValidValue('COEmbedRequiredKeys',$key) ) {
                throw new CInvalidRequiredOEmbedKeyException('Key "' . $key . '" does not belong to required keys.');
            }
        }

        // set new data
        $responseObject->addData( $key_value_items_array );
        
        return $responseObject;
    }
    
    /**
     * Adds optional keys and assigned values to the response including checking whether the keys
     * are optional at all.
     * 
     * @param object $responseObject
     *  Actual response object to put keys and values into.
     * @param array $key_value_items_array
     *  Array including optional keys and values to be added into the response message.
     * @retval object
     *  Updated response object with a new optional keys and values in a form of an array.
     */ 
    public function add_oembed_optional_response_items($responseObject, $key_value_items_array) {

        foreach ($key_value_items_array as $key => $value) {
            if ( !COEmbedOptionalKeys::isValidValue( 'COEmbedOptionalKeys',$key) ) {
                throw new CInvalidOptionalOEmbedKeyException('Key "' . $key . '" does not belong to obligatory keys.');
            }
        }
          // set new data
        $responseObject->addData( $key_value_items_array );
        
        return $responseObject;
    } 

    /**
     * Adds any keys and assigned values to the response.
     * 
     * @param object $responseObject
     *  Actual response object to put keys and values into.
     * @param array $key_value_items_array
     *  Array including any keys and values to be added into the response message.
     * @retval object
     *  Updated response object with a new (any) keys and values in a form of an array.
     */       
    public function add_oembed_any_response_items($responseObject, $key_value_items_array) {

          // set new data
        $responseObject->addData( $key_value_items_array );
        
        return $responseObject;
    }    
}
?>
