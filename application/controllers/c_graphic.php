<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once( APPPATH . '/models/DataHolders/product_screen_representation.php');

class C_graphic extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        if (!$this->authentify_graphic()) {
            $this->redirectToHomePage();
            return;
        }

        //login or logout in menu
        $template_data = array();
        $this->set_title($template_data, 'Graphic interface');
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('v_graphic');
    }

    public function components_graphic() {

        if (!$this->authentify_graphic()) {
            $this->redirectToHomePage();
            return;
        }

        $proposed_components = $this->component_model->get_proposed_components();
        if (!$proposed_components) {
            $template_data = array();
            $this->set_title($template_data, 'No proposed components');
            $this->load_header_templates($template_data);
            $this->load->view('templates/header', $template_data);
            $this->load->view('graphic/v_graphic_no_proposed_components');
            return;
        }

        $this->load->library('table');
        $tmpl = array('table_open' => '<table border="1" class="admin_table">');

        $this->table->set_template($tmpl);
        $this->table->set_heading('ID', 'Name', 'Price' . ' &euro;', 'Status', 'Is stable?', 'Creator', 'Category', 'Photo', 'Edit picture');


        foreach ($proposed_components as $single_proposed_component_instance) {

            $form_instance = form_open_multipart('c_graphic/change_component_picture/' . $single_proposed_component_instance->getId());

            $atts = array(
                'width' => '800',
                'height' => '600',
                'scrollbars' => 'yes',
                'status' => 'yes',
                'resizable' => 'yes',
                'screenx' => '0',
                'screeny' => '0'
            );

            $anchorPhoto = anchor_popup('c_graphic/component_photo_index/' . $single_proposed_component_instance->getId(), 'Photo', $atts);
            $anchorUser = anchor_popup('c_admin/users_admin/' . $single_proposed_component_instance->getCreator(), $this->user_model->get_user_by_id($single_proposed_component_instance->getCreator())->getNick(), $atts);

            $categoryName = $this->category_model->get_category_by_id($single_proposed_component_instance->getCategory())->getName();

            $this->table->add_row(
                    $single_proposed_component_instance->getId(), $single_proposed_component_instance->getName(), $single_proposed_component_instance->getPrice(), $single_proposed_component_instance->getAcceptanceStatus(), ($single_proposed_component_instance->getIsStable() == '1' ? 'Yes' : 'No'), $anchorUser, $categoryName, $anchorPhoto, $form_instance . '<input type="file" name="userfile" size="20" />' . '<br />' . '<input type="submit" value="Save new picture" />' . '</form>'
            );
        }

        $data['table_data'] = $this->table->generate();
        $data['error'] = '';

        $template_data = array();
        $this->set_title($template_data, 'Prodposed components administration');
        $this->load_header_templates($template_data);
        $this->load->view('templates/header', $template_data);
        $this->load->view('graphic/v_graphic_components', $data);
    }

    public function change_component_picture($componentId) {
        if (!$this->authentify_graphic()) {
            $this->redirectToHomePage();
            return;
        }


        if (is_null($componentId) || !isset($componentId) || !is_numeric($componentId)) {
            $this->redirectToHomePage();
            return;
        }
        
        $components_upl_config = get_components_upload_configuration($this->config);
        $raster_model = $this->component_raster_model->get_component_single_raster_by_component_and_point_of_view( $componentId, $this->point_of_view_model->get_basic_pov()->getId());
        $components_upl_config['file_name'] = basename( $raster_model->getPhotoUrl());

        $this->load->library('upload', $components_upl_config);
        
        if (!$this->upload->do_upload()) {
            $data['error'] = array('error' => $this->upload->display_errors());
            $template_data = array();
            $this->set_title($template_data, 'Prodposed components administration');
            $this->load_header_templates($template_data);
            $this->load->view('templates/header', $template_data);
            $this->load->view('graphic/v_graphic_components', $data);
        } else {
            $upload_photo_data = $this->upload->data();
            $raster_model->setPhotoUrl( get_components_upload_path($this->config) . $upload_photo_data['file_name'] );
            $raster_model->update_raster_model();

            $data['error'] = '';
            $template_data = array();
            $this->set_title($template_data, 'Prodposed components administration');
            $this->load_header_templates($template_data);
            $this->load->view('templates/header', $template_data);
            $this->load->view('graphic/v_graphic_component_pic_changed_suc', $data);
        }
    }

    /**
     * Do not generalize! Download button!
     * @param type $componentId
     * @return type
     */
    public function component_photo_index($componentId) {
        if (!$this->authentify_graphic()) {
            $this->redirectToHomePage();
            return;
        }

        if (is_null($componentId) || !isset($componentId) || !is_numeric($componentId)) {
            $this->redirectToHomePage();
            return;
        }


        $basic_pov = $this->point_of_view_model->get_basic_pov();
        $singleComponentRasterModel = $this->component_raster_model->get_component_single_raster_by_component_and_point_of_view($componentId, $basic_pov->getId());

        $data['component_raster_model'] = $singleComponentRasterModel;

        $template_data = array();
        $this->set_title($template_data, 'Component photo detail');
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('graphic/v_graphic_component_detail', $data);
    }

    public function profile() {
        if (!$this->authentify_graphic()) {
            $this->redirectToHomePage();
            return;
        }

        $actual_user_id = $this->get_user_id();

        //loading user info
        $user = $this->user_model->get_user_by_id($actual_user_id);
        $data['user'] = $user;
        $data['address'] = $this->address_model->get_address_by_id($user->getAddress());

        $template_data = array();
        $this->set_title($template_data, 'Edit profile');
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('graphic/v_graphic_profile', $data);
    }

    public function edit_profile() {

        if (!$this->authentify_graphic()) {
            $this->redirectToHomePage();
            return;
        }

        // field name, error message, validation rules
        $this->form_validation->set_rules('tf_nick', 'User Name', 'trim|required|min_length[4]|max_length[32]|xss_clean|callback_nick_check');
        $this->form_validation->set_rules('tf_first_name', 'First name', 'trim|required|min_length[4]|max_length[32]|xss_clean');
        $this->form_validation->set_rules('tf_last_name', 'Last name', 'trim|required|min_length[4]|max_length[32]|xss_clean');
        $this->form_validation->set_rules('tf_email_address', 'Your Email', 'trim|required|valid_email|max_length[64]|callback_email_check');
        $this->form_validation->set_rules('tf_password_base', 'Password', 'trim|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('tf_password_confirm', 'Password Confirmation', 'trim|matches[tf_password_base]|max_length[32]');
        $this->form_validation->set_rules('tf_phone_number', 'Phone number', 'trim|max_length[32]|xss_clean');
        $this->form_validation->set_rules('tf_street', 'Street', 'trim|required|max_length[128]|xss_clean');
        $this->form_validation->set_rules('tf_city', 'City', 'trim|required|max_length[64]|xss_clean');
        $this->form_validation->set_rules('tf_zip', 'ZIP', 'trim|required|max_length[16]|xss_clean');
        $this->form_validation->set_rules('tf_country', 'Country', 'trim|required|max_length[64]|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            // print out validation errors
            $template_data = array();

            $this->set_title($template_data, 'Edit profile - fail');
            $this->load_header_templates($template_data);

            $this->load->view('templates/header', $template_data);
            $this->load->view('graphic/v_graphic_profile');
            return;
        }

        $user_id = $this->get_user_id();
        $user = $this->user_model->get_user_by_id($user_id);
        $address = $this->address_model->get_address_by_id($user->getAddress());


        log_message('debug', 'User before: ' . print_r($user, true));
        log_message('debug', 'Address before: ' . print_r($address, true));

        $newNick = $this->input->post('tf_nick');
        if ($user->getNick() !== $newNick) {
            $user->setNick($newNick);
        }

        $newEmailAddress = $this->input->post('tf_email_address');
        if ($user->getEmailAddress() !== $newEmailAddress) {
            $user->setEmailAddress($newEmailAddress);
        }

        $newFirstname = $this->input->post('tf_first_name');
        if ($user->getFirstName() !== $newFirstname) {
            $user->setFirstName($newFirstname);
        }

        $newLastname = $this->input->post('tf_last_name');
        if ($user->getLastName() !== $newLastname) {
            $user->setLastName($newLastname);
        }

        $newPhoneNumber = $this->input->post('tf_phone_number');
        if ($user->getPhoneNumber() !== $newPhoneNumber) {
            $user->setPhoneNumber($newPhoneNumber);
        }

        $newGender = ( $this->input->post('tf_gender') == 'male' ? 0 : 1 );
        if ($user->getGender() !== $newGender) {
            $user->setGender($newGender);
        }

        $password = $this->input->post('tf_password_base');
        if (strlen($password) > 0 && $user->getPassword() !== md5($password)) {
            $user->setPassword($password);
        }

        $newStreet = $this->input->post('tf_street');
        if ($address->getStreet() !== $newStreet) {
            $address->setStreet($newStreet);
        }
        $newCity = $this->input->post('tf_city');
        if ($address->getCity() !== $newCity) {
            $address->setCity($newCity);
        }

        $newZip = $this->input->post('tf_zip');
        if ($address->getZip() !== $newZip) {
            $address->setZip($newZip);
        }

        $newCountry = $this->input->post('tf_country');
        if ($address->getCountry() !== $newCountry) {
            $address->setCountry($newCountry);
        }

        log_message('debug', 'User after: ' . print_r($user, true));
        log_message('debug', 'Address after: ' . print_r($address, true));

        $user->update_user();
        $address->update_address();

        redirect('c_graphic/profile', 'refresh');
    }

}

/* End of file c_admin.php */
    /* Location: ./application/controllers/c_admin.php */