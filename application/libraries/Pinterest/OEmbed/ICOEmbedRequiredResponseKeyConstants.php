<?php

/**
 * Interface holding basic required oEmbed constants with values 
 * defining JSON keys or XML elements' names.
 * 
 * See also:
 * <a href="http://oembed.com/#section2">oEmbed documentation</a>  
 *
 * 
 * @see ICOEmbedErrorKeyConstants
 * @author Pavol Daňo
 * @version 1.0 
 * @file
 */
interface ICOEmbedRequiredResponseKeyConstants {
    

    /**
     * type (required)
     * The resource type. Valid values, along with value-specific parameters, are described below.
     */
    const OERC_TYPE = 'type';

    /**
     * version (required)
     * The oEmbed version number. This must be 1.0.
     */
    const OERC_VERSION = 'version';

}

?>
