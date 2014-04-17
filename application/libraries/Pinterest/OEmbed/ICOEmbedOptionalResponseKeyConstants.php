<?php

/**
 * Interface holding basic optional oEmbed constants with values 
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
interface ICOEmbedOptionalResponseKeyConstants {

    /**
     * title (optional)
     * A text title, describing the resource.
     */
    const OERC_TITLE = 'title';

    /**
     * author_name (optional)
     * The name of the author/owner of the resource.
     */
    const OERC_AUTHOR_NAME = 'author_name';


    /**
     * author_url (optional)
     * A URL for the author/owner of the resource.
     */
    const OERC_AUTHOR_URL = 'author_url';

    /**
     * provider_name (optional)
     * The name of the resource provider.
     */
    const OERC_PROVIDER_NAME = 'provider_name';

    /**
     * provider_url (optional)
     * The url of the resource provider.
     */
    const OERC_PROVIDER_URL = 'provider_url';

    /**
     * cache_age (optional)
     * The suggested cache lifetime for this resource, in seconds. 
     * Consumers may choose to use this value or not.
     */
    const OERC_CACHE_AGE = 'cache_age';

    /**
     * thumbnail_url (optional)
     * An URL to a thumbnail image representing the resource. 
     * The thumbnail must respect any maxwidth and maxheight parameters. 
     * If this parameter is present, thumbnail_width 
     * and thumbnail_height must also be present (see below).
     */
    const OERC_THUMBNAIL_URL = 'thumbnail_url';

    /**
     * thumbnail_width (optional)
     * The width of the optional thumbnail.
     * If this parameter is present, thumbnail_url 
     * and thumbnail_height must also be present (see above and below).
     */
    const OERC_THUMBNAIL_WIDTH = 'thumbnail_width';

    /**
     * thumbnail_height (optional)
     * The height of the optional thumbnail.
     * If this parameter is present, thumbnail_url 
     * and thumbnail_width must also be present (see above).
     */
    const OERC_THUMBNAIL_HEIGHT = 'thumbnail_height';
}

?>
