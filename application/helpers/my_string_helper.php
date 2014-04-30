<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if ( !function_exists('startsWithABC') ) {

    /**
     * Helper function for retrieveing info if string $haystack starts with $needle part.
     * 
     * @param string $haystack
     *  String to search in.
     * @param string $needle
     *  String to be searched.
     * @retval boolean
     *  Boolean value as a result of comparison.
     */
    function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

}
/* End of file my_string_helper.php */
/* Location: ./application/helpers/my_string_helper.php */