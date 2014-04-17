<?php

/**
 * Interface declaring mostly helper methods for:
 * - URL check
 * - URL prefix filtration
 * - embedding oEmbed required key/value pair into response,
 * - embedding oEmbed optional key/value pair into response,
 * - embedding any key/value pair into response.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
interface ICOEmbed {

    /**
     * Setter for accepted URL schema.
     * 
     * @param string $acceptedURLScheme
     *  URL as a string to bound with oEmbed service controller.
     */
    public function set_accepted_url_scheme($acceptedURLScheme);

    /**
     * Getter for accepted URL schema.
     * 
     * @retval string
     *  URL schema which is accepted by oEmbed service controller by.
     */
    public function get_accepted_url_scheme();

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
    public function check_url_from_get($urlToBeChecked);

    /**
     * Removes URL prefix according to the accepted URL schema which class implementing 
     * this interface is bound to.
     * 
     * @param string $url
     *  URL address from a request including accepted URL schema (prefix).
     * @retval string
     *  URL address without the prefix.
     */
    public function remove_expected_part($url);

    /**
     * Adds required oEmbed keys and assigned values to the response including checking whether the keys
     * are required at all.
     * 
     * @param object $responseObject
     *  Actual response array to put keys and values into.
     * @param array $key_value_items_array
     *  Array including required key sand values to be added into the response message.
     * @retval object
     *  Updated response object with a new keys and values in a form of an array.
     */
    public function add_oembed_required_response_items($responseObject, $key_value_items_array);

    
    /**
     * Adds optional oEmbed keys and assigned values to the response including checking whether the keys
     * are optional at all.
     * 
     * @param array $responseObject
     *  Actual response object to put keys and values into.
     * @param array $key_value_items_array
     *  Array including optional keys and values to be added into the response message.
     * @retval object
     *  Updated response object with a new optional keys and values in a form of an array.
     */    
    public function add_oembed_optional_response_items($responseObject, $key_value_items_array);

    /**
     * Adds any keys and assigned values to the response object.
     * 
     * @param array $responseObject
     *  Actual response object to put keys and values into.
     * @param array $key_value_items_array
     *  Array including any keys and values to be added into the response message.
     * @retval object
     *  Updated response object with a new (any) keys and values in a form of an array.
     */       
    public function add_oembed_any_response_items($responseObject, $key_value_items_array);
}

?>
