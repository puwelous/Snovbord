<?php

/**
 * Interface holding basic PayPal URLs to reach Sanbox or Live site.
 * 
 * @author Pavol Daňo
 * @version 1.0 
 * @file
 */
interface ICPayPalURLConstants {

    /**
     * PayPal Sandbox URL
     * 
     */    
    const PPUC_URL_SANDBOX = 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=';  
    
    /**
     * PayPal Live site URL
     * 
     */ 
    const PPUC_URL_LIVESITE = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';
}

?>
