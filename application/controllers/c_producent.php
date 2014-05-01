<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once( APPPATH . '/models/DataHolders/product_screen_representation.php');

/**
 * Class implementing producent actions.
 *  
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class C_producent extends MY_Controller {

    /**
     * Basic constructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Renders index page.
     * Shows producent menu.
     */
    public function index() {

        if (!$this->authentify_producent()) {
            $this->redirectToHomePage();
            return;
        }

        //login or logout in menu
        $template_data = array();
        $this->set_title($template_data, 'Producent interface');
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('v_producent');
    }

    /**
     * Renders producent products menu.
     */
    public function products_producent() {

        if (!$this->authentify_producent()) {
            $this->redirectToHomePage();
            return;
        }

        //login or logout in menu
        $template_data = array();
        $this->set_title($template_data, 'Producent products interface');
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('producent/v_producent_products');
    }

    /**
     * Renders page with proposed products ready for qualifying process.
     */
    public function qualify_product_producent() {

        if (!$this->authentify_producent()) {
            $this->redirectToHomePage();
            return;
        }

        $proposed_products = $this->product_model->get_proposed_products();
        if (is_null($proposed_products)) {
            $template_data = array();
            $this->set_title($template_data, 'No proposed products');
            $this->load_header_templates($template_data);
            $this->load->view('templates/header', $template_data);
            $this->load->view('producent/v_producent_no_proposed_products');
            return;
        }

        $this->load->library('table');
        $tmpl = array('table_open' => '<table border="1" class="admin_table">');

        $this->table->set_template($tmpl);
        $this->table->set_heading('ID', 'Name', 'Price' . ' &euro;', 'Description', 'Sizes', 'Sex', 'Status', 'Photo');


        foreach ($proposed_products as $single_proposed_product_instance) {

            $form_instance = form_open('c_producent/change_product_status/' . $single_proposed_product_instance->getId());

            $sizesOptions = array(
                'small' => 'Small',
                'medium' => 'Medium',
                'large' => 'Large',
                'xlarge' => 'Xlarge'
            );
            $sizesSelected = array('small', 'medium', 'large', 'xlarge');
            $sizesSelect = form_multiselect('uf_available_sizes[]', $sizesOptions, $sizesSelected, 'id="pdf_available_sizes"');


            $sexSelectOption = array(
                'male' => 'Male',
                'female' => 'Female',
                'unisex' => 'Unisex'
            );

            $sexSelect = form_dropdown('uf_sex', $sexSelectOption, 'unisex');

            $statusSelectOptions = array(
                'declined_unseen' => 'Decline',
                'accepted' => 'Accept'
            );

            $statusSelect = form_dropdown('uf_status', $statusSelectOptions, 'accepted');

            $submit_button = form_submit('mysubmit', 'Change');

            $atts = array(
                'width' => '800',
                'height' => '600',
                'scrollbars' => 'yes',
                'status' => 'yes',
                'resizable' => 'yes',
                'screenx' => '0',
                'screeny' => '0'
            );

            $anchorPhoto = anchor_popup('c_producent/product_photo_index/' . $single_proposed_product_instance->getId(), 'Photo', $atts);

            $this->table->add_row(
                    $single_proposed_product_instance->getId(), $single_proposed_product_instance->getName(), $single_proposed_product_instance->getPrice(), $single_proposed_product_instance->getDescription(), $form_instance . $sizesSelect, $sexSelect, $statusSelect . $submit_button . form_close(), $anchorPhoto
            );
        }

        $data['table_data'] = $this->table->generate();

        $template_data = array();
        $this->set_title($template_data, 'Prodposed products administration');
        $this->load_header_templates($template_data);
        $this->load->view('templates/header', $template_data);
        $this->load->view('producent/v_producent_proposed_products', $data);
    }

    /**
     * Allows producent to change product status
     * @param int $productId
     *  ID of the product whose status is about to be changed
     */
    public function change_product_status($productId) {

        if (!isset($productId) || is_null($productId) || !is_numeric($productId)) {
            log_message('debug', 'Param for c_admin/change_status not initialized, redirecting to admin page!');
            redirect('/c_producent/index', 'refresh');
            return;
        }

        $selected_sex_value = $this->input->post('uf_sex');
        $selected_status_value = $this->input->post('uf_status');

        $product_to_qualify = $this->product_model->get_product($productId);
        if (is_null($product_to_qualify)) {
            log_message('debug', 'Could not load required product to be qualified!');
            redirect('/c_producent/index', 'refresh');
            return;
        }

        // if accepted also set sex value
        if (strtoupper($selected_status_value) === Product_model::PRODUCT_STATUS_ACCEPTED) {
            $product_to_qualify->setSex($selected_sex_value);
        }

        // set status
        $product_to_qualify->setAcceptanceStatus(strtoupper($selected_status_value));

        $this->db->trans_begin();
        {
            if (!$product_to_qualify->update_product()) {
                log_message('debug', 'Could not update qualified product!');
                $this->db->trans_rollback();
                redirect('/c_producent/index', 'refresh');
                return;
            }

            // insert possible sizes for product instance
            $available_sizes_array = $this->input->post('uf_available_sizes');
            foreach ($available_sizes_array as $available_size_item) {
                $new_psfp_inst = new Possible_size_for_product_model();
                $new_psfp_inst->instantiate(
                        $available_size_item, 1, $product_to_qualify->getId());
                $new_psfp_inst->save();
            }
        }
        if ($this->db->trans_status() === FALSE) {
            log_message('debug', 'Transaction status is FALSE! Rolling the transaction back!');
            $this->db->trans_rollback();
            redirect('/c_producent/index', 'refresh');
            return;
        } else {
            log_message('debug', '... commiting transaction ...!');
            $this->db->trans_commit();
            log_message('info', 'Changing user privileges successful!');
            redirect('/c_producent/qualify_product_producent', 'refresh');
        }
    }

    /**
     * Shows visual representation of specified product.
     * @param int $productId
     *  ID of the product to show
     */
    public function product_photo_index($productId) {
        if (!$this->authentify_producent()) {
            $this->redirectToHomePage();
            return;
        }

        if (is_null($productId) || !isset($productId) || !is_numeric($productId)) {
            $this->redirectToHomePage();
            return;
        }

        $singleProduct = $this->product_model->get_product($productId);

        $data['product'] = $singleProduct;

        $template_data = array();
        $this->set_title($template_data, 'Product photo detail');
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('producent/v_producent_product_detail', $data);
    }

    /**
     * Renders profile page for producent.
     */
    public function profile() {
        if (!$this->authentify_producent()) {
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
        $this->load->view('producent/v_producent_profile', $data);
    }

    /**
     * Allowrs producent to edit his/her profile.
     */
    public function edit_profile() {

        if (!$this->authentify_producent()) {
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
            $this->load->view('producent/v_producent_profile');
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

        redirect('c_producent/profile', 'refresh');
    }
}

/* End of file c_producent.php */
    /* Location: ./application/controllers/c_producent.php */