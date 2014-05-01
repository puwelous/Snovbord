<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once( APPPATH . '/models/DataHolders/product_screen_representation.php');

/**
 * Controller class for handling final products on common screen
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class C_products extends MY_Controller {

    /**
     * Basic constructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Index page.
     * Selects accepted products and renders list of products.
     * For each product representation takes the view which is the basic one.
     */
    public function index() {

        $template_data = array();

        $this->set_title($template_data, 'Our products');
        $this->load_header_templates($template_data);

        // load product definitions
        $products = $this->product_model->get_accepted_products();
        //$products = $this->product_model->get_all_products(); //TODO: change to the line of code above!
        
        $basic_pov = $this->point_of_view_model->get_basic_pov();
        //log_message('debug', print_r($products, true));

        $product_screen_representations_array = array();
        
        foreach ($products as $product_instance) {
            
//            $sup_povs = $this->supported_point_of_view_model->get_by_product($product_instance_id);
            
            $urls = array();
            // add basic raster to URLS
            //$urls[] = $basic_raster_model_object->getPhotoUrl();
            $urls[] = $product_instance->getPhotoUrl();
//            $compositions = $this->composition_model->get_compositions_by_product_id( $product_instance->getId() );
//            
//            foreach ( $compositions as $singleComposition ){
//                $component_raster_instance = $this->component_raster_model->get_component_single_raster_by_component_and_point_of_view(
//                        $singleComposition->getComponent(),
//                        $basic_pov->getId());
//                $urls[] = $component_raster_instance->getPhotoUrl();
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