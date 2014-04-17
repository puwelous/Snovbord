<?php

/**
 * Interface holding PayPal Sandbox or Live site endpoints URLs. 
 * 
 * @author Pavol Daňo
 * @version 1.0 
 * @file
 */
interface ICPayPalEndpointConstants {

    /**
     * PayPal API Sandbox endpoint
     * 
     */    
    const PPEC_API_ENDPOINT_SANDBOX = 'https://api-3t.sandbox.paypal.com/nvp';  
    
    /**
     * PayPal API Live site endpoint
     * 
     */ 
    const PPEC_API_ENDPOINT_LIVESITE = 'https://api-3t.paypal.com/nvp';
}

?>
