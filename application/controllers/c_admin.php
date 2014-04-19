<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_admin extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        if (!$this->authentify_admin()) {
            $this->redirectToHomePage();
            return;
        }

        //login or logout in menu
        $template_data = array();
        $this->set_title($template_data, 'Admin interface');
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('v_admin');
        $this->load->view('templates/footer');
    }

    public function categories_admin() {

        if (!$this->authentify_admin()) {
            $this->redirectToHomePage();
            return;
        }

        //login or logout in menu
        $template_data = array();
        $this->set_title($template_data, 'Categories administration');
        $this->load_header_templates($template_data);

        $data['actual_user_nick'] = $this->get_user_nick();

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_categories', $data);
        $this->load->view('templates/footer');
    }

    public function components_admin() {

        if (!$this->authentify_admin()) {
            $this->redirectToHomePage();
            return;
        }

        //login or logout in menu
        $template_data = array();
        $this->set_title($template_data, 'Components administration');
        $this->load_header_templates($template_data);

        $data['actual_user_nick'] = $this->get_user_nick();

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_components', $data);
        $this->load->view('templates/footer');
    }

    public function basic_products_admin() {

        if (!$this->authentify_admin()) {
            $this->redirectToHomePage();
            return;
        }

        //login or logout in menu
        $template_data = array();
        $this->set_title($template_data, 'Basic products administration');
        $this->load_header_templates($template_data);

        $data['actual_user_nick'] = $this->get_user_nick();

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_basic_products', $data);
        $this->load->view('templates/footer');
    }

    public function add_basic_product() {

        if (!$this->authentify_admin()) {
            $this->redirectToHomePage();
            return;
        }

        $template_data = array();

        $this->set_title($template_data, 'Adding basic products');
        $this->load_header_templates($template_data);
        $data['actual_user_nick'] = $this->get_user_nick();

        // field name, error message, validation rules
        $this->form_validation->set_rules('pdf_product_name', 'Product name', 'trim|required|min_length[1]|max_length[32]|xss_clean');
        $this->form_validation->set_rules('pdf_available_sizes', 'Product sizes', 'required');
        $this->form_validation->set_rules('pdf_product_price', 'Product price', 'trim|required|greater_than[0]|max_length[32]|xss_clean|numeric');
        $this->form_validation->set_rules('pdf_product_type', 'Product type', 'trim|required|min_length[1]|max_length[256]');

        if ($this->form_validation->run() == FALSE) {

            $data['error'] = NULL; // no need, prited out by library in a view
            $data['successful'] = NULL;

            // print out validation errors
            $this->load->view('templates/header', $template_data);
            $this->load->view('admin/v_admin_basic_products', $data);
            $this->load->view('templates/footer');
            return;
        }

        // validation ok
        // load basic upload config
        $prod_upl_config = get_basic_products_upload_configuration($this->config);
        // print out DEL later
        log_message('debug', print_r($prod_upl_config, TRUE)); // delete me
        // load user nick
        $actual_user_nick = $this->get_user_nick(); // check if not null later
        // load product's name
        $product_name = $this->input->post('pdf_product_name');

        // load product's type
        $product_type = $this->input->post('pdf_product_type');

        // load product's price
        $product_price = $this->input->post('pdf_product_price');

        $product_sex = $this->input->post('pdf_product_sexes');
        log_message('debug', print_r($product_sex, TRUE)); // delete me later
        // calculate file name using user's nick and product's name
        $generated_product_file_name = generate_product_file_name(
                $actual_user_nick, $product_name
        );

        // add to config
        $prod_upl_config['file_name'] = $generated_product_file_name;


        // init library
        $this->load->library('upload', $prod_upl_config);

        // try to upload and handle if does not work
        if (!$this->upload->do_upload()) {

            $error = array('error' => $this->upload->display_errors());

            log_message('debug', print_r($error['error'], TRUE));

            // add error message
            $data['error'] = $error['error'];
            $data['successful'] = NULL;

            $this->load->view('templates/header', $template_data);
            $this->load->view('admin/v_admin_basic_products', $data);
            $this->load->view('templates/footer');
            return;
        }

        // log some details
        $upload_photo_data = $this->upload->data();
        log_message('debug', 'Upload successfull!');
        log_message('debug', print_r($upload_photo_data, TRUE));

        $data['error'] = NULL;
        $data['successful'] = 'Upload successfull!';

        // database insertions
        $actual_user_id = $this->get_user_id();
        if (is_null($actual_user_id)) {
            $data['error'] = 'Cannot find an user_id of the actual user. How should I assign the creator of a product?';
            $this->load->view('templates/header', $template_data);
            $this->load->view('admin/v_admin_basic_products', $data);
            $this->load->view('templates/footer');
        }

        try {
            // insert product definition instance
            $new_product_definition = new Product_definition_model();
            $new_product_definition->instantiate(
                    //$generated_product_file_name, instead of generated use REAL path after file upload
                    $product_name, get_basic_product_upload_path($this->config) . $upload_photo_data['file_name'], $actual_user_id, $product_type, $product_price, $product_sex
            );

            $inserted_prod_def_id = $new_product_definition->insert_product_definition();

            // insert possible sizes for product instance
            $available_sizes_array = $this->input->post('pdf_available_sizes');
            foreach ($available_sizes_array as $available_size_item) {
                $new_psfp_inst = new Possible_size_for_product_model();
                $new_psfp_inst->instantiate($inserted_prod_def_id, $available_size_item, 1);
                $new_psfp_inst->insert_possible_size_for_product();
            }
        } catch (Exception $e) {
            log_message('debug', print_r($e, TRUE));
        }

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_basic_products', $data);
        $this->load->view('templates/footer');
    }

    public function final_products_admin() {

        if (!$this->authentify_admin()) {
            $this->redirectToHomePage();
            return;
        }

        //login or logout in menu
        $template_data = array();
        $this->set_title($template_data, 'Final products administration');
        $this->load_header_templates($template_data);

        $data['actual_user_nick'] = $this->get_user_nick();

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_final_products', $data);
        $this->load->view('templates/footer');
    }

    public function add_products() {

        if (!$this->authentify_admin()) {
            $this->redirectToHomePage();
            return;
        }

        $template_data = array();

        $this->set_title($template_data, 'Products administration');
        $this->load_header_templates($template_data);
        $data['actual_user_nick'] = $this->get_user_nick();

        // field name, error message, validation rules
        $this->form_validation->set_rules('pdf_product_name', 'Product name', 'trim|required|min_length[1]|max_length[32]|xss_clean');
        $this->form_validation->set_rules('pdf_available_sizes', 'Product sizes', 'required');
        $this->form_validation->set_rules('pdf_product_price', 'Product price', 'trim|required|greater_than[0]|max_length[32]|xss_clean|numeric');
        $this->form_validation->set_rules('pdf_product_type', 'Product type', 'trim|required|min_length[1]|max_length[256]');

        if ($this->form_validation->run() == FALSE) {

            $data['error'] = NULL; // no need, prited out by library in a view
            $data['successful'] = NULL;

            // print out validation errors
            $this->load->view('templates/header', $template_data);
            $this->load->view('admin/v_admin_products', $data);
            $this->load->view('templates/footer');
            return;
        }

        // validation ok
        // load basic upload config
        $prod_upl_config = get_products_upload_configuration($this->config);
        // print out DEL later
        log_message('debug', print_r($prod_upl_config, TRUE)); // delete me
        // load user nick
        $actual_user_nick = $this->get_user_nick(); // check if not null later
        // load product's name
        $product_name = $this->input->post('pdf_product_name');

        // load product's type
        $product_type = $this->input->post('pdf_product_type');

        // load product's price
        $product_price = $this->input->post('pdf_product_price');

        $product_sex = $this->input->post('pdf_product_sexes');
        log_message('debug', print_r($product_sex, TRUE)); // delete me later
        // calculate file name using user's nick and product's name
        $generated_product_file_name = generate_product_file_name(
                $actual_user_nick, $product_name
        );

        // add to config
        $prod_upl_config['file_name'] = $generated_product_file_name;


        // init library
        $this->load->library('upload', $prod_upl_config);

        // try to upload and handle if does not work
        if (!$this->upload->do_upload()) {

            $error = array('error' => $this->upload->display_errors());

            log_message('debug', print_r($error['error'], TRUE));

            // add error message
            $data['error'] = $error['error'];
            $data['successful'] = NULL;

            $this->load->view('templates/header', $template_data);
            $this->load->view('admin/v_admin_products', $data);
            $this->load->view('templates/footer');
            return;
        }

        // log some details
        $upload_photo_data = $this->upload->data();
        log_message('debug', 'Upload successfull!');
        log_message('debug', print_r($upload_photo_data, TRUE));

        $data['error'] = NULL;
        $data['successful'] = 'Upload successfull!';

        // database insertions
        $actual_user_id = $this->get_user_id();
        if (is_null($actual_user_id)) {
            $data['error'] = 'Cannot find an user_id of the actual user. How should I assign the creator of a product?';
            $this->load->view('templates/header', $template_data);
            $this->load->view('admin/v_admin_products', $data);
            $this->load->view('templates/footer');
        }

        try {
            // insert product definition instance
            $new_product_definition = new Product_definition_model();
            $new_product_definition->instantiate(
                    //$generated_product_file_name, instead of generated use REAL path after file upload
                    $product_name, get_product_upload_path($this->config) . $upload_photo_data['file_name'], $actual_user_id, $product_type, $product_price, $product_sex
            );

            $inserted_prod_def_id = $new_product_definition->insert_product_definition();

            // insert possible sizes for product instance
            $available_sizes_array = $this->input->post('pdf_available_sizes');
            foreach ($available_sizes_array as $available_size_item) {
                $new_psfp_inst = new Possible_size_for_product_model();
                $new_psfp_inst->instantiate($inserted_prod_def_id, $available_size_item, 1);
                $new_psfp_inst->insert_possible_size_for_product();
            }
        } catch (Exception $e) {
            log_message('debug', print_r($e, TRUE));
        }

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_products', $data);
        $this->load->view('templates/footer');
    }

    public function about_us() {

        if (!$this->authentify_admin()) {
            $this->redirectToHomePage();
            return;
        }

        //login or logout in menu
        $template_data = array();
        $this->set_title($template_data, 'About us administration');
        $this->load_header_templates($template_data);

        $data['isFirstTimeRendered'] = TRUE;
        $data['company'] = $this->company_model->get_company();

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_about_us', $data);
        $this->load->view('templates/footer');

        $data['isFirstTimeRendered'] = FALSE;
    }

    public function update_about_us() {

        // field name, error message, validation rules
        $this->form_validation->set_rules('cmpnf_provider_firstname', 'User Name', 'trim|required|min_length[1]|max_length[32]|xss_clean|callback_nick_check');
        $this->form_validation->set_rules('cmpnf_provider_lastname', 'First name', 'trim|required|min_length[1]|max_length[32]|xss_clean');
        $this->form_validation->set_rules('cmpnf_provider_street', 'Last name', 'trim|required|min_length[1]|max_length[32]|xss_clean');
        $this->form_validation->set_rules('cmpnf_provider_email', 'Email', 'trim|required|valid_email|min_length[1]|max_length[64]');
        $this->form_validation->set_rules('cmpnf_provider_street_number', 'Street number', 'trim|required|min_length[1]|max_length[8]');
        $this->form_validation->set_rules('cmpnf_provider_phone_number', 'Phone number', 'trim|required|min_length[10]|max_length[32]');
        $this->form_validation->set_rules('cmpnf_provider_city', 'City', 'trim|required|min_length[2]|max_length[64]|xss_clean');
        $this->form_validation->set_rules('cmpnf_provider_country', 'Country', 'trim|required|min_length[2]|max_length[64]|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            // print out validation errors
            $template_data = array();

            $this->set_title($template_data, 'About us administration');
            $this->load_header_templates($template_data);

            $this->load->view('templates/header', $template_data);
            $this->load->view('admin/v_admin_about_us');
            $this->load->view('templates/footer');
            return;
        } else {

            $p_first_name = $this->input->post('cmpnf_provider_firstname');
            $p_last_name = $this->input->post('cmpnf_provider_lastname');
            $p_street = $this->input->post('cmpnf_provider_street');
            $p_email = $this->input->post('cmpnf_provider_email');
            $p_street_number = $this->input->post('cmpnf_provider_street_number');
            $p_phone_number = $this->input->post('cmpnf_provider_phone_number');
            $p_city = $this->input->post('cmpnf_provider_city');
            $p_country = $this->input->post('cmpnf_provider_country');

            // no need to update rules, copy from previous version of company stored in DB now
            $currentCompany = $this->company_model->get_company_as_object();

            $company_instance = new Company_model();
            $company_instance->instantiate($p_first_name, $p_last_name, $p_street, $p_street_number, $p_city, $p_country, $p_email, $p_phone_number, $currentCompany->provider_rules);
            $company_instance->primary_key = $currentCompany->primary_key;

            log_message('debug', 'Updating company:' . print_r($currentCompany, TRUE));
            log_message('debug', 'To company:' . print_r($company_instance, TRUE));

            $currentCompany->update_company_by_company($company_instance);

            redirect('/c_admin/about_us', 'refresh');
        }
    }

    public function rules() {

        if (!$this->authentify_admin()) {
            $this->redirectToHomePage();
            return;
        }

        //login or logout in menu
        $template_data = array();
        $this->set_title($template_data, 'Rules administration');
        $this->load_header_templates($template_data);

        //loading company info
        $data['company_rules'] = $this->company_model->get_company()->cmpn_rules;

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_rules', $data);
        $this->load->view('templates/footer');
    }

    public function update_rules() {
        if ($this->input->post('ajax') == '4') {

            log_message('debug', 'Attempt to change rules in the admin interface.');

            $new_rules = $this->input->post('new_rules');

            $company = $this->company_model->get_company();

            if (!isset($company)) {
                log_message('debug', 'Company not found in DB.');

                echo '-1';
                return;
            }

            if ($company->cmpn_rules == $new_rules) {
                log_message('debug', 'Rules to be changed are the same as current rules. No need to change anything.');

                echo '0';
                return;
            }

            $this->company_model->update(
                    $company->cmpn_id, array('cmpn_rules' => $new_rules));

            log_message('debug', 'Company rules successfully updated.');

            // send successful
            echo '1';
            return;
        }
    }

    public function users_admin() {

        if (!$this->authentify_provider()) {
            $this->redirectToHomePage();
            return;
        }

        $this->load->library('table');
        $tmpl = array('table_open' => '<table border="1" class="users_table">');

        $this->table->set_template($tmpl);
        $this->table->set_heading('Nick', 'Firstname', 'Lastname', 'Email', 'Phone', 'Privilege', 'Change privilege');

        $all_users = $this->user_model->get_all_users();

        foreach ($all_users as $single_user_model_instance) {

            $user_type_name = $this->user_type_model->get($single_user_model_instance->getUserType())->usrtp_name;

            $form_instance = form_open('c_admin/change_privilege/' . $single_user_model_instance->getUserId());

            $options = array(
                'customer' => 'Customer',
                'provider' => 'Provider',
                'graphic' => 'Graphic',
                'producer' => 'Producer',
            );

            $select = form_dropdown('uf_privileges', $options, $user_type_name);

            $submit_button = form_submit('mysubmit', 'Change');

            $this->table->add_row(
                    $single_user_model_instance->getNick(), $single_user_model_instance->getFirstName(), $single_user_model_instance->getLastName(), $single_user_model_instance->getEmailAddress(), $single_user_model_instance->getPhoneNumber(), $user_type_name, $form_instance . $select . $submit_button . form_close()
            );
        }

        $template_data = array();
        $this->set_title($template_data, 'Users administration');
        $this->load_header_templates($template_data);

        $data['table_data'] = $this->table->generate();

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_users', $data);
        $this->load->view('templates/footer');
    }

    
    public function change_privilege( $user_id ){
        
        if (!isset($user_id) || is_null($user_id) || !is_numeric($user_id)) {
            log_message('debug', 'Param for c_admin/change_privilege not initialized, redirecting to admin page!');
            redirect('/c_admin/index', 'refresh');
            return;
        }
       
        //$selected_user = $this->user_model->get_by_id($user_id);
        
        $selected_user_type_value = $this->input->post('uf_privileges');
        
       $new_user_type = $this->user_type_model->get_by_user_type_name( $selected_user_type_value );
       
       $update_result = $this->user_model->update_user_type( $user_id, $new_user_type->getUserTypeId() );
        
        if ( $update_result <= 0 ) {
            log_message('error', 'Changing user privilege not successful!');
            redirect('/c_admin/users_admin', 'refresh');
            return;
        }
        
        log_message('info', 'Changing user privileges successful!');
        redirect('/c_admin/users_admin', 'refresh');
    }
}

/* End of file c_admin.php */
    /* Location: ./application/controllers/c_admin.php */