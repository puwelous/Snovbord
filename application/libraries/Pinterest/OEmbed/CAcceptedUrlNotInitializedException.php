<?php

require_once('COEmbedException.php');

/**
 * Exception thrown in a case that object extending ICOembed 
 * is not initialized properly before usage of certain methods 
 * where accepted URL is necessary to be defined.
 * 
 * @see COEmbedImpl::check_url_from_get
 * @see COEmbedImpl::remove_expected_part
 * 
 * @author Pavol Daňo
 * @file
 */
class CAcceptedUrlNotInitializedException extends COEmbedException {
    
}

?>
