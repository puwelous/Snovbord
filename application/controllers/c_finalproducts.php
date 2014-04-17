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
        
//        $data['products_list'] = array(
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner1'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner2'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner3'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner4'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner5'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner6'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner7'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner8'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner9'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner10'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner11'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner12'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner13'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner14'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner15'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner16'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner17'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner18'),
//            array('img_path' => 'assets/images/jacket.jpg', 'prod_name' => 'sky runner19')
//        );
        
        $this->load->view('templates/header', $template_data);
        $this->load->view('v_finalproducts', $data);
        $this->load->view('templates/footer');
    }

}

/* End of file c_finalproducts.php */
/* Location: ./application/controllers/c_finalproducts.php */