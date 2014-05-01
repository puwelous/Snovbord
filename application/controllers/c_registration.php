<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Controller class rendering registration screen.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class C_registration extends MY_Controller {

    /**
     * Basic constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Renders registration screen.
     */
    public function index() {
        
        $template_data = array();
        
        $this->set_title( $template_data, 'Registration');
        $this->load_header_templates( $template_data );

        $this->load->view('templates/header', $template_data);
        $this->load->view('v_registration');
    }
}

/* End of file c_registration.php */
/* Location: ./application/controllers/c_registration.php */