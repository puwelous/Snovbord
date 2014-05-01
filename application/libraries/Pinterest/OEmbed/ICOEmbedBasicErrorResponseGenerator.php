<?php

/**
 * Interface declaring 4 basic methods generating oEmbed response 
 * for any error query input.
 * 
 * @author Pavol Daňo
 * @version 1.0 
 * @file
 */
interface ICOEmbedBasicErrorResponseGenerator {

    /**
     * Generated oEmbed response for 404 status code.
     * @param string $url
     *  URL address to be added to the response.
     * @return object
     *  object representing the response for the error
     */
    public function generate_404_not_found($url);

    /**
     * Generated oEmbed response for 501 status code.
     * @param string $url
     *  URL address to be added to the response.
     * @return object
     *  object representing the response for the error
     */
    public function generate_501_not_implemented($url);

    /**
     * Generated oEmbed response for 401 status code.
     * @param string $url
     *  URL address to be added to the response.
     * @return object
     *  object representing the response for the error
     */
    public function generate_401_unauthorized($url);

    /**
     * Generated oEmbed response for 500 status code.
     * @param string $url
     *  URL address to be added to the response.
     * @return object
     *  object representing the response for the error
     */
    public function generate_500_server_issues($url);
}

?>
