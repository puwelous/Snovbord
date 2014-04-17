<?php

/**
 * Interface holding 1 constant value for a response version.
 * Allowed value so far is "1.0".
 * 
 * See also:
 * <a href="http://oembed.com/#section2">oEmbed documentation</a>  
 *
 * 
 * @see ICOEmbedRequiredResponseKeyConstants
 * @author Pavol Daňo
 * @version 1.0 
 * @file
 */
interface ICOEmbedVersionValueConstants {

    /**
     * The oEmbed version number. 
     * This must be 1.0
     */
    const COEVVC_VERSION = '1.0';
}

?>
