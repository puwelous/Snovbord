<?php

require_once('OEmbedAbstractResponseMessage.php');

/**
 * Concrete class defining basic necessary methods for message creation and manipulation.
 * Represents concrete non-error OEmbed response message.
 * 
 * @see OEmbedAbstractResponseMessage
 * 
 * @author Pavol Daňo
 * @version 1.0 
 * @file
 */
class OEmbedResponseMessage extends OEmbedAbstractResponseMessage{
    
    /**
     * Held data.
     * @var array
     *  Representation of data as an array.
     */
    private $response_message_data = array();
    
    /**
     * Basic constructor. Init data are allowed to be passed as param.
     * @param array $keys_and_values_array
     *  Array of keys and values to be set at the moment of initialization.
     */
    public function __construct( $keys_and_values_array ) {
        if (is_array($keys_and_values_array)  ){
            $this->response_message_data = $keys_and_values_array;
        }
    }

    /**
     * Adding new data.
     * 
     * @param array $keys_and_values_array
     *  New data to be added.
     */
    public function addData($keys_and_values_array) {
        if ( is_array($keys_and_values_array )  ){
            foreach ($keys_and_values_array as $key => $value) {
                $this->response_message_data[$key] = $value;
            }
        }
    }

    /**
     * Getting actual data.
     * 
     * @retval array
     *  Getting actual instance data.
     */
    public function getData() {
        return $this->response_message_data;
    }

    /**
     * Setting new data.
     * @param array $keys_and_values_array
     *  New data array to be set.
     */
    public function setData($keys_and_values_array) {
        if (is_array($keys_and_values_array)  ){
            $this->response_message_data = $keys_and_values_array;
        }
    }
}

?>
