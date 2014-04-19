<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_ucreate extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($prodId = NULL) {

        if ( !$this->authentify() ) {
            log_message('debug', 'Attempt to edit product for unlogged user. Redirect!');
            redirect('/c_registration/index', 'refresh');
        }           
        
        $template_data = array();

        $this->set_title($template_data, 'Product preview');
        $this->load_header_templates($template_data);

        //$prodId = 30;
        
        if (!isset($prodId) || is_null($prodId) || !is_numeric($prodId)) {
            log_message('debug', 'Param for c_preview/show not initialized, redirecting to welcome page!');
            redirect('/c_finalproducts', 'refresh');
            return;
        }


        $previewed_product = $this->product_definition_model->get($prodId);

        $data['previewed_product'] = $previewed_product;
        $data['previewed_product_creator'] = $this->user_model->get( $this->get_user_id() );
        $possible_sizes_array = $this->possible_size_for_product_model->get_all_possible_sizes_for_product_by('pd_id', $previewed_product->pd_id, FALSE);

        $output_sizes = array();

        for ($i = 0; $i < count($possible_sizes_array); $i++) {
            $output_sizes[$possible_sizes_array[$i]['psfp_id']] = $possible_sizes_array[$i]['psfp_name'];
        }

        //log_message('debug', print_r($data['previewed_product_size_options'], TRUE));
        $data['previewed_product_size_options'] = $output_sizes;
        

        $this->load->view('templates/header', $template_data);
        $this->load->view('v_ucreate', $data);
        $this->load->view('templates/footer');
    }
}

/* End of file c_ucreate.php */
/* Location: ./application/controllers/c_ucreate.php */