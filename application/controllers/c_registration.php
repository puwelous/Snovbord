<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_registration extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
        $template_data = array();
        
        $this->set_title( $template_data, 'Registration');
        $this->load_header_templates( $template_data );

        $this->load->view('templates/header', $template_data);
        $this->load->view('v_registration');
        $this->load->view('templates/footer');
    }
}

/* End of file c_registration.php */
/* Location: ./application/controllers/c_registration.php */