<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once( APPPATH . '/models/DataHolders/product_screen_representation.php');

class C_products extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $template_data = array();

        $this->set_title($template_data, 'Our products');
        $this->load_header_templates($template_data);

        // load product definitions
        $products = $this->product_model->get_all_products();

        $basic_pov = $this->point_of_view_model->get_basic_pov();
        //log_message('debug', print_r($products, true));

        $product_screen_representations_array = array();
        
        foreach ($products as $product_instance) {

            //$product_instance_id = $product_instance->getId();
            $basic_raster_model_object = $this->basic_product_raster_model->get_single_basic_product_raster_by_product_id_and_pov_id(
                    $product_instance->getId(),
                    $basic_pov->getId()
                    );

//            $sup_povs = $this->supported_point_of_view_model->get_by_product($product_instance_id);
//
            $urls = array();
            // add basic raster to URLS
            $urls[] = $basic_raster_model_object->getPhotoUrl();
            
            //TODO add components URLS!
//            if ($sup_povs !== NULL) {
//                foreach ($sup_povs as $sup_pov_item) {
//                    $rasters = $this->supported_point_of_view_model->get_rasters_urls_by_pov($sup_pov_item->getId(), 'url');
//                    //log_message('debug', print_r($rasters, true));
//                    foreach ($rasters as $raster_item) {
//                        $urls[] = $raster_item->url;
//                    }
//                }
//            }
            
            $product_screen_representations_array[] =  new Product_screen_representation(
                    $product_instance->getId(),  $product_instance->getName(), $urls);
            
//            log_message('debug', print_r($sup_povs, true));
        }

        log_message('debug', print_r($product_screen_representations_array, true));

        $data['products_representations_list'] = $product_screen_representations_array;

        $this->load->view('templates/header', $template_data);
        $this->load->view('v_products', $data);
    }

}

/* End of file c_products.php */
/* Location: ./application/controllers/c_products.php */