<?php

/**
 * Interface holding basic optional Pinterest constants with values 
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
interface ICPinOptionalResponseKeyConstants {

    /**
     * provider name (optional)
     * String, store name (for example "Snoubórd").
     * 
     */    
    const PORKC_PROVIDER_NAME = 'provider_name';  
    
    /**
     * description (optional)
     * String, product description.
     * May be truncated, all line breaks and HTML tags will be removed.
     */
    const PORKC_DESCRIPTION = 'description';  
    
    /**
     * brand (optional)
     * String, brand name (for example "Lucky Brand").
     */
    const PORKC_BRAND = 'brand';     
    
    /**
     * product id (optional)
     * String, ID that uniquely identifies the product within your site.
     */    
    const PORKC_PRODUCT_ID = 'product_id';   
    
    /**
     * availability (optional)
     * Case-insensitive string, possible values: 
     * - "in stock",
     * - "preorder",
     * - "backorder" (will be back in stock soon),
     * - "out of stock" (may be back in stock some time),
     * - "discontinued".
     * Discontinued items won’t be part of a daily scrape
     * and marking them will decrease the load on your servers.
     */
    const PORKC_AVAILABILITY = 'availability'; 
    
    /**
     * quantity (optional)
     * Number (int). Positive value is interpreted as "in stock", 
     * "out of stock" otherwise.
     */
    const PORKC_QUANTITY = 'quantity';
    
    /**
     * standard_price (optional)
     * Number (float). Product's original price if it's on sale 
     * (without currency sign, for example "10.00").
     */
    const PORKC_STANDARD_PRICE = 'standard_price';
    
    /**
     * sale start date (optional)
     * If the product is on sale, the start date in ISO 8601 date format.
     */
    const PORKC_SALES_START_DATE = 'sale_start_date';   
    
    /**
     * sale end date (optional)
     * If the product is on sale, the end date in ISO 8601 date format.
     */
    const PORKC_SALES_END_DATE = 'sale_end_date';     
    
    /**
     * product expiration (optional)
     * Product expiration date in ISO 8601 date format.
     */
    const PORKC_PRODUCT_EXPIRATION = 'product_expiration';  
    
    /**
     * gender (optional)
     * Gender property of this product can only be:
     * - "male", 
     * - "female" or
     * - unisex".
     */
    const PORKC_GENDER = 'gender';     
     
    /**
     * geographic availability (optional)
     * The list of product available 
     * geographic areas in ISO 3166 Country Code format.
     * Can be 'All' if applies for all countries.
     */
    const PORKC_GEO_AVAILABILITY = 'geographic_availability';  
    
    /**
     * color (optional)
     * List of color specs (in JSON format) if the product has different colors.
     * You can specify the color's name, detail image 
     * and standard color map name:
     * - "beige",
     * - "black",
     * - "blue",
     * - "bronze",
     * - "brown",
     * - "gold",
     * - "green",
     * - "gray",
     * - "metallic",
     * - "multicolored",
     * - "off-white",
     * - "orange",
     * - "pink",
     * - "purple",
     * - "red",
     * - "silver",
     * - "transparent",
     * - "turquoise",
     * - "white" or
     * - "yellow".
     */
    const PORKC_COLOR = 'color';    
    
    /**
     * images (optional)
     * List of URLs of high resolution images for this product. 
     * Up to 6 images can be provided.
     */
    const PORKC_IMAGES = 'images';     
    
    /**
     * related items (optional)
     * List of URLs (must be the same domain) representing the related products.
     */
    const PORKC_RELATED_ITEMS = 'related_items';
    
    /**
     * referenced items (optional)
     * List of URLs representing the referenced other products.
     */
    const PORKC_REFERENCED_ITEMS = 'referenced_items';
    
    /**
     * rating (optional)
     * Number (float), rating of this product (for example, 4.6).
     */
    const PORKC_RATING = 'rating';    
    
    /**
     * rating scale (optional)
     * Number (int), maximum value of the ratings scale. 
     * Required if rating provided (e.g. 5).
     */
    const PORKC_RATING_SCALE = 'rating_scale';      

    /**
     * rating count (optional)
     * Number (int), rating count of which the product is rated(e.g. 113).
     */
    const PORKC_RATING_COUNT= 'rating_count';      
}

?>
