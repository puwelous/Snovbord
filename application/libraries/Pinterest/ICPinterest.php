<?php

/**
 * Interface declaring methods for:
 * - embedding Pinterest required key/value pair into response,
 * - embedding Pinterest optional key/value pair into response,
 * - embedding any key/value pair into response.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
interface ICPinterest {

    /**
     * Adds required Pinterest keys and assigned values to the response including checking whether the keys
     * are required at all.
     * 
     * @param object $responseObject
     *  Actual response object to put keys and values into.
     * @param array $key_value_items_array
     *  Array including required key sand values to be added into the response message.
     * @retval object
     *  Updated response object with a new keys and values in a form of an array.
     */
    public function add_pinterest_required_response_items($responseObject, $key_value_items_array);

    /**
     * Adds optional Pinterest keys and assigned values to the response including checking whether the keys
     * are optional at all.
     * 
     * @param object $responseObject
     *  Actual response object to put keys and values into.
     * @param array $key_value_items_array
     *  Array including optional keys and values to be added into the response message.
     * @retval object
     *  Updated response object with a new optional keys and values in a form of an array.
     */
    public function add_pinterest_optional_response_items($responseObject, $key_value_items_array);

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
    public function add_pinterest_any_response_items($responseObject, $key_value_items_array);
}

?>
