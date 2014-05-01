<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Controller class for handling welcome screen outprint.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class C_welcome extends MY_Controller {

    /**
     * Renders welcome page.
     */
    public function index() {

        $template_data = array();
        
        $this->set_title( $template_data, 'Welcome');
        $this->load_header_templates( $template_data );

        $this->load->view('templates/header', $template_data);
        $this->load->view('v_welcome');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_welcome.php */