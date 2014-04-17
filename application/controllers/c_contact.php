<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_contact extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
        //login or logout in menu
        $template_data = array();
        $this->set_title( $template_data, 'Contact');
        $this->load_header_templates( $template_data );

        
        //loading company info
        $data['company'] = $this->company_model->get_company();
        
        // if no data loaded, explicitly set some
        if( is_null($data['company'])){  
            $data['company'] = (object) array(
                'cmpn_id'                       => $this->config->item('cmpn_id'),
                'cmpn_provider_firstname'       => $this->config->item('cmpn_provider_firstname'),
                'cmpn_provider_lastname'        => $this->config->item('cmpn_provider_lastname'),
                'cmpn_provider_street'          => $this->config->item('cmpn_provider_street'),
                'cmpn_provider_street_number'   => $this->config->item('cmpn_provider_street_number'),
                'cmpn_provider_city'            => $this->config->item('cmpn_provider_city'),
                'cmpn_provider_country'         => $this->config->item('cmpn_provider_country'),
                'cmpn_provider_email'           => $this->config->item('cmpn_provider_email'),
                'cmpn_provider_phone_number'    => $this->config->item('cmpn_provider_phone_number'),
                'cmpn_rules'                    => $this->config->item('cmpn_rules')
            );
        }

        $this->load->view('templates/header', $template_data);
        $this->load->view('v_contact', $data);
        $this->load->view('templates/footer');
    }
}

/* End of file c_contact.php */
/* Location: ./application/controllers/c_contact.php */