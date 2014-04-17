<?php

/**
 * Interface holding 4 constants as alternative values 
 * for a response resource type.
 * Might be:
 * - photo
 * - video
 * - rich
 * - link
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
interface ICOEmbedTypeValueConstants {
    /**
     * Photo type.
     * 
     * Specifies response type to be of a photo type.
     * This type is used for representing static photos.
     */
    const COETVC_PHOTO = 'photo';

    /**
     * Video type.
     * 
     * Specifies response type to be of a video type.
     * This type is used for representing playable videos.
     */
    const COETVC_VIDEO = 'video';

    /**
     * Link type.
     * 
     * Specifies response type to be of a link type.
     * Responses of this type allow a provider to return 
     * any generic embed data (such as title and author_name), 
     * without providing either the url or html parameters.
     */
    const COETVC_LINK = 'link';

    /**
     * Rich type.
     * 
     * Specifies response type to be of a rich type.
     * This type is used for rich HTML content that 
     * does not fall under one of the other categories. 
     */
    const COETVC_RICH = 'rich';

}

?>
