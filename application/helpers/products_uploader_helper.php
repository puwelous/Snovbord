<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('get_products_upload_configuration')) {

    /**
     * Array holding configuration information for products upload.
     * 
     * @var array $prod_upl_config products upload configuration array
     */
    $prod_upl_config = array();

    /**
     * Returns product configuration array.
     * 
     * @param object $config_helper
     *  Configuration helper CI object
     * @retval array
     *  Products configuration array
     */
    function get_products_upload_configuration($config_helper) {

        if (empty($prod_upl_config)) {
            // load once
            $prod_upl_config['upload_path'] = $config_helper->item('product_upload_path');
            $prod_upl_config['allowed_types'] = $config_helper->item('product_allowed_types');
            $prod_upl_config['max_size'] = $config_helper->item('product_max_size');
            $prod_upl_config['max_width'] = $config_helper->item('product_max_width');
            $prod_upl_config['max_height'] = $config_helper->item('product_max_height');
        }
        return $prod_upl_config;
    }
}

if (!function_exists('get_product_upload_path')) {

    /**
     * Returns product configuration upload path
     * 
     * @param object $config_helper
     *  Configuration helper CI object
     * @retval string
     *  Products configuration upload path
     */    
    function get_product_upload_path( $config_helper ) {
        return $config_helper->item('product_upload_path');
    }
}

if (!function_exists('generate_product_file_name')) {

    /**
     * Generates product file name according to user nick and product name.
     * Replaces "." with "_" and " " with "_", too.
     * Adds sufix for making any file unique.
     * 
     * @param string $user_nick
     *  Nick of user uploading file
     * @param string $product_name
     *  Name of product to upload file for
     * @retval string
     *  String as a path to the added file.
     */
    function generate_product_file_name( $user_nick, $product_name) {
        // get rid of dots if necessary
        $product_name_without_dots = str_replace(".", "_", $product_name);
        $product_name_without_dots_and_spaces = str_replace(" ", "_", $product_name_without_dots);
        
        $prefix = $user_nick . '_' . $product_name_without_dots_and_spaces . '_';
        // 13 chars long
        return uniqid($prefix);
    }
}

if (!function_exists('get_basic_products_upload_configuration')) {

    /**
     * Array holding configuration information for basic products upload.
     * 
     * @var array $basic_prod_upl_config basic products upload configuration array
     */    
    $basic_prod_upl_config = array();

    /**
     * Returns basic product configuration array.
     * 
     * @param object $config_helper
     *  Configuration helper CI object
     * @retval array
     *  Basic products configuration array
     */
    function get_basic_products_upload_configuration($config_helper) {

        if (empty($basic_prod_upl_config)) {
            // load once
            $basic_prod_upl_config['upload_path'] = $config_helper->item('basic_product_upload_path');
            $basic_prod_upl_config['allowed_types'] = $config_helper->item('basic_product_allowed_types');
            $basic_prod_upl_config['max_size'] = $config_helper->item('basic_product_max_size');
            $basic_prod_upl_config['max_width'] = $config_helper->item('basic_product_max_width');
            $basic_prod_upl_config['max_height'] = $config_helper->item('basic_product_max_height');
        }
        return $basic_prod_upl_config;
    }
}

if (!function_exists('get_basic_product_upload_path')) {

    /**
     * Returns basic product configuration upload path
     * 
     * @param object $config_helper
     *  Configuration helper CI object
     * @retval string
     *  Basic products configuration upload path
     */     
    function get_basic_product_upload_path( $config_helper ) {
        return $config_helper->item('basic_product_upload_path');
    }
}

if (!function_exists('get_components_upload_configuration')) {

    /**
     * Array holding configuration information for components upload.
     * 
     * @var array $basic_prod_upl_config components upload configuration array
     */     
    $components_upl_config = array();

    /**
     * Returns component upload configuration array.
     * 
     * @param object $config_helper
     *  Configuration helper CI object
     * @retval array
     *  Component upload configuration array
     */    
    function get_components_upload_configuration($config_helper) {

        if (empty($components_upl_config)) {
            // load once
            $components_upl_config['upload_path'] = $config_helper->item('component_upload_path');
            $components_upl_config['allowed_types'] = $config_helper->item('component_allowed_types');
            $components_upl_config['max_size'] = $config_helper->item('component_max_size');
            $components_upl_config['max_width'] = $config_helper->item('component_max_width');
            $components_upl_config['max_height'] = $config_helper->item('component_max_height');
        }
        return $components_upl_config;
    }
}

if (!function_exists('get_components_upload_path')) {

    /**
     * Returns component configuration upload path
     * 
     * @param object $config_helper
     *  Configuration helper CI object
     * @retval string
     *  Components configuration upload path
     */      
    function get_components_upload_path( $config_helper ) {
        return $config_helper->item('component_upload_path');
    }
}
/* End of file products_uploader_helper.php */
/* Location: ./application/helpers/products_uploader_helper.php */