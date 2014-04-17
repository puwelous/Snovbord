<?php

/**
 * Interface holding 3 constants as alternative values 
 * for a response gender of a product owner type.
 * Might be:
 * - male
 * - female
 * - unisex
 * 
 * See also:
 * <a href="https://developers.pinterest.com/rich_pins/">Pinterest Rich Pins documentation</a>  
 *
 * 
 * @author Pavol Daňo
 * @version 1.0 
 * @file
 */
interface ICPinGenderValueConstants {

    /**
     * Male.
     * 
     * Marks product as a product for a person of the male gender.
     */    
    const PGVC_MALE = 'male';  
    
    /**
     * Female.
     * 
     * Marks product as a product for a person of the female gender.
     */  
    const PGVC_FEMALE = 'female';  
    
    /**
     * Unisex.
     * 
     * Marks product as a product for a person of any gender.
     */  
    const PGVC_UNISEX = 'unisex';
}

?>
