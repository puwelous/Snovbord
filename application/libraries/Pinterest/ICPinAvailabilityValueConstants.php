<?php

/*
 * C, possible values: "in stock", "preorder", "backorder" 
 * (will be back in stock soon), 
 * “out of stock” (may be back in stock some time), 
 * “discontinued.” 
 * Discontinued items won’t be part of a daily scrape 
 * and marking them will decrease the load on your servers.
 */

/**
 * Interface holding 5 constants as alternative values 
 * for a response product availability.
 * Might be any of these case-insensitive strings:
 * - "in stock",
 * - "preorder",
 * - "backorder",
 * - "out of stock",
 * - "discontinued".
 * 
 * See also:
 * <a href="https://developers.pinterest.com/rich_pins/">Pinterest Rich Pins documentation</a>  
 *
 * 
 * @author Pavol Daňo
 * @version 1.0 
 * @file
 */
interface ICPinAvailabilityValueConstants {

    /**
     * In stock.
     * 
     * Marks product as a product in stock.
     */       
    const PAVC_IN_STOCK = 'in stock';  
    
    /**
     * Preorder.
     * 
     * Marks product as a product for which preorder is necessary.
     */  
    const PAVC_PREORDER = 'preorder';  
    
    /**
     * Backorder.
     * 
     * Marks product as a product providers need to order because it is out of stock.
     */  
    const PAVC_BACKORDER = 'backorder';     
    
    /**
     * Out of stock.
     * 
     * Marks product as a product which is out of stock.
     */ 
    const PAVC_OUT_OF_STOCK = 'out of stock';   
    
    /**
     * Discontinued.
     * 
     * Marks product as a product which is not going to be sold again.
     */ 
    const PAVC_DISCONTINUED = 'discontinued'; 
}

?>
