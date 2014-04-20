<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_finalproducts extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $template_data = array();

        $this->set_title($template_data, 'Final products');
        $this->load_header_templates($template_data);

        // load product definitions
        $product_definitions = $this->product_definition_model->get_all_product_definitions();
        
        $data['products_list'] = $product_definitions;
                
        $this->load->view('templates/header', $template_data);
        $this->load->view('v_finalproducts', $data);
    }

}

/* End of file c_finalproducts.php */
/* Location: ./application/controllers/c_finalproducts.php */