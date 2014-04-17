<?php

/**
 * Abstract class defining basic necessary methods for derived classes.
 * Represents OEmbed response message.
 * 
 * @author Pavol Daňo
 * @version 1.0 
 * @file
 */
abstract class OEmbedAbstractResponseMessage {
    
    /**
     * Adds data to existing data.
     */
    protected abstract function addData( $keys_and_values_array );   
    
    /**
     * Retrieves existing data.
     */
    protected abstract function getData();
    
    /**
     * Sets completely new data.
     */
    protected abstract function setData( $keys_and_values_array );
}

?>
