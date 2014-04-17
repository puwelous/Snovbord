<?php

/**
 * Interface holding basic required Pinterest constants with values 
 * defining JSON keys or XML elements' names.
 * 
 * See also:
 * <a href="https://developers.pinterest.com/rich_pins/">Pinterest Rich Pins documentation</a>  
 *
 * 
 * @author Pavol Daňo
 * @version 1.0 
 * @file
 */
interface ICPinRequiredResponseKeyConstants {
    
    /**
     * url (required)
     * String, canonical URL for the page.
     * Example:
     * http://www.etsy.com/listing/83934917/chocolate-raspberry-drizzle-body-lotion
     * 
     */
    const PRRKC_URL = 'url';

    /**
     * title (required)
     * String, product name. 
     * May be truncated, all formatting and HTML tags will be removed.
     */
    const PRRKC_TITLE = 'title';
    
    /**
     * price (required)
     * Number (float),product price 
     * Without currency sign, for example 6.50.
     */
    const PRRKC_PRICE = 'price';    

    /**
     * currency code (required)
     * String, currency code as defined in http://www.xe.com/iso4217.php (for example "USD").
     */
    const PRRKC_CURRENCY_CODE = 'currency_code';
}

?>
