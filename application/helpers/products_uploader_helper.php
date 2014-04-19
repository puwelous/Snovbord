<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('get_products_upload_configuration')) {

    $prod_upl_config = array();

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

    function get_product_upload_path( $config_helper ) {
        return $config_helper->item('product_upload_path');
    }
}

if (!function_exists('generate_product_file_name')) {

    function generate_product_file_name( $user_nick, $product_name) {
        $prefix = $user_nick . '_' . $product_name . '_';
        // 13 chars long
        return uniqid($prefix);
    }
}

if (!function_exists('get_basic_products_upload_configuration')) {

    $basic_prod_upl_config = array();

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

    function get_basic_product_upload_path( $config_helper ) {
        return $config_helper->item('basic_product_upload_path');
    }
}


/* End of file products_uploader_helper.php */
/* Location: ./application/helpers/products_uploader_helper.php */