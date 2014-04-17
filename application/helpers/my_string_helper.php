<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if ( !function_exists('startsWithABC') ) {

    function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

}
/* End of file email_former_helper.php */
/* Location: ./application/helpers/email_former_helper.php */