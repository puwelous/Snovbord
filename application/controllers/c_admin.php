<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once( APPPATH . '/models/DataHolders/product_screen_representation.php');

class C_admin extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        if (!$this->authentify_provider()) {
            $this->redirectToHomePage();
            return;
        }

        //login or logout in menu
        $template_data = array();
        $this->set_title($template_data, 'Admin interface');
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('v_admin');
    }

    public function products_admin() {

        if (!$this->authentify_provider()) {
            $this->redirectToHomePage();
            return;
        }

        //login or logout in menu
        $template_data = array();
        $this->set_title($template_data, 'Admin interface');
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_products');
    }

    public function new_product_admin_index() {

        if (!$this->authentify_provider()) {
            $this->redirectToHomePage();
            return;
        }

        $data['actual_user_nick'] = $this->get_user_nick();

        $presentPointsOfView = $this->supported_point_of_view_model->get_pov_names_distinct();
        log_message('debug', print_r($presentPointsOfView, TRUE));

        $with_value_included_array = array();

        foreach ($presentPointsOfView as $value) {
            $with_value_included_array[$value] = $value;
        }


        $data['with_value_included_array'] = $with_value_included_array;

        //login or logout in menu
        $template_data = array();
        $this->set_title($template_data, 'Admin interface');
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_new_product_index', $data);
    }

    public function new_product_admin_add() {

        if (!$this->authentify_provider()) {
            $this->redirectToHomePage();
            return;
        }

        $template_data = array();
        $this->set_title($template_data, 'Admin interface');
        $this->load_header_templates($template_data);

        $data['actual_user_nick'] = $this->get_user_nick();


        $this->db->trans_begin(); {        // field name, error message, validation rules
            $this->form_validation->set_rules('npf_product_name', 'Product name', 'trim|required|min_length[1]|max_length[32]|xss_clean');
            $this->form_validation->set_rules('npf_available_sizes', 'Product sizes', 'required');
            $this->form_validation->set_rules('npf_product_price', 'Product price', 'trim|required|greater_than[0]|max_length[32]|xss_clean|numeric');
            $this->form_validation->set_rules('npf_product_desc', 'Product description', 'trim|required|min_length[1]|max_length[256]');
            $this->form_validation->set_rules('npf_point_of_view_name', 'Point of view', 'trim|required|min_length[1]|max_length[64]');

            if ($this->form_validation->run() == FALSE) {

                $data['error'] = NULL; // no need, printed out by library in a view
                $data['successful'] = NULL;

                // print out validation errors
                $this->load->view('templates/header', $template_data);
                $this->load->view('admin/v_admin_new_product_index', $data);
                return;
            }

            // validation ok
            // load basic upload config
            $prod_upl_config = get_basic_products_upload_configuration($this->config);
            // load user nick
            $actual_user_nick = $this->get_user_nick(); // check if not null later
            // load product's name
            $product_name = $this->input->post('npf_product_name');

            // load product's type
            $product_desc = $this->input->post('npf_product_desc');

            // load product's price
            $product_price = $this->input->post('npf_product_price');

            // load product's sex
            $product_sex = $this->input->post('npf_product_sexes');

//            // load product's pov
//            $product_pov = $this->input->post('npf_point_of_view_name');
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
                log_message('debug', print_r($prod_upl_config, TRUE));

                // add error message
                $data['error'] = $error['error'];
                $data['successful'] = NULL;

                $this->load->view('templates/header', $template_data);
                $this->load->view('admin/v_admin_new_product_index', $data);
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
                $this->load->view('admin/v_admin_new_product_index', $data);
            }

            try {
                // create sb_product
                $new_product = new Product_model();
                $new_product->instantiate($product_name, $product_price, $product_desc, $product_sex, $actual_user_id);
                $new_product_id = $new_product->save();

                if ($new_product_id <= 0) {
                    // add error message
                    $data['error'] = 'Product creation in database failed!';
                    $data['successful'] = NULL;
                    log_message('error', 'Product creation in database failed! Rolling the transaction back!');
                    $this->db->trans_rollback();

                    $this->load->view('templates/header', $template_data);
                    $this->load->view('admin/v_admin_new_product_index', $data);
                    return;
                } else {
                    log_message('debug', 'Product creation successful');
                }

                // insert possible sizes for product instance
                $available_sizes_array = $this->input->post('npf_available_sizes');
                foreach ($available_sizes_array as $available_size_item) {
                    $new_psfp_inst = new Possible_size_for_product_model();
                    $new_psfp_inst->instantiate($available_size_item, 1, $new_product_id);
                    if ($new_psfp_inst->save() <= 0) {
                        log_message('error', 'Possible size creation in database failed! Ignoring now!');
                    } else {
                        log_message('debug', 'Possible size creation successful');
                    }
                }

                // create sb_basic_product
                $new_basic_product = new Basic_product_model();
                $new_basic_product->instantiate($new_product_id, $actual_user_id);
                $new_basic_product_id = $new_basic_product->save();

                if ($new_basic_product_id <= 0) {
                    // add error message
                    $data['error'] = 'Basic product creation in database failed!';
                    $data['successful'] = NULL;
                    log_message('error', 'Basic product creation in database failed! Rolling the transaction back!');
                    $this->db->trans_rollback();

                    $this->load->view('templates/header', $template_data);
                    $this->load->view('admin/v_admin_new_product_index', $data);
                    return;
                } else {
                    log_message('debug', 'Basic product creation successful');
                }

                // point of view
                $pov_presence = $this->input->post('npf_is_point_of_view_present');
                if ($pov_presence === 'new_pov') {
                    // load product's pov
                    $product_pov = $this->input->post('npf_point_of_view_name');
                } else if ($pov_presence === 'old_pov') {
                    $product_pov = $this->input->post('npf_present_povs');
                    ;
                } else {
                    log_message('error', 'Input type radio value neither new_pov neither old_pov! How come!!!');
                    log_message('error', 'POV creation in database failed! Rolling the transaction back!');
                    $this->db->trans_rollback();
                    $this->load->view('templates/header', $template_data);
                    $this->load->view('admin/v_admin_new_product_index', $data);
                    return;
                }

                $new_supported_point_of_view = new Supported_point_of_view_model();
                $new_supported_point_of_view->instantiate($product_pov, $new_product_id);
                $new_supported_point_of_view_id = $new_supported_point_of_view->save();
                if ($new_supported_point_of_view_id <= 0) {
                    // add error message
                    $str = $this->db->last_query();
                    log_message('error', 'Last query: ' . $str);

                    $data['error'] = 'POV creation in database failed!';
                    $data['successful'] = NULL;
                    log_message('error', 'Point of view creation in database failed! Rolling the transaction back!');
                    $this->db->trans_rollback();

                    $this->load->view('templates/header', $template_data);
                    $this->load->view('admin/v_admin_new_product_index', $data);
                    return;
                } else {
                    log_message('debug', 'Point of view creation successful');
                }

                // create sb_basic_product_raster_representation
                $new_basic_product_raster = new Basic_product_raster_model();
                $photoUrl = get_basic_product_upload_path($this->config) . $upload_photo_data['file_name'];
                $new_basic_product_raster->instantiate($photoUrl, $new_basic_product_id, $new_supported_point_of_view_id);
                $new_basic_product_raster_id = $new_basic_product_raster->save();

                if ($new_basic_product_raster_id <= 0) {
                    // add error message
                    $data['error'] = 'Raster representation creation in database failed!';
                    $data['successful'] = NULL;
                    log_message('error', 'Raster representation creation in database failed! Rolling the transaction back!');
                    $this->db->trans_rollback();

                    $this->load->view('templates/header', $template_data);
                    $this->load->view('admin/v_admin_new_product_index', $data);
                    return;
                } else {
                    log_message('debug', 'Raster representation creation successful');
                }
            } catch (Exception $e) {
                log_message('error', print_r($e, TRUE));
            }

            // create set of sb_basic_product_vector_representation
        }
        if ($this->db->trans_status() === FALSE) {
            log_message('debug', 'Transaction status is FALSE! Rolling the transaction back!');
            $this->db->trans_rollback();
            redirect('/c_admin/new_product_admin_index', 'refresh');
            return;
        } else {
            log_message('debug', '... commiting transaction ...!');
            $this->db->trans_commit();

            $data['error'] = NULL;
            $data['successful'] = 'New basic product created succesfully!';

            redirect('/c_admin/new_product_admin_index', 'refresh');
        }

        //login or logout in menu
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_new_product_index', $data);
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
                $new_psfp_inst->save();
            }
        } catch (Exception $e) {
            log_message('debug', print_r($e, TRUE));
        }

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_basic_products', $data);
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
        $tmpl = array('table_open' => '<table border="1" class="admin_table">');

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
    }

    public function change_privilege($user_id) {

        if (!isset($user_id) || is_null($user_id) || !is_numeric($user_id)) {
            log_message('debug', 'Param for c_admin/change_privilege not initialized, redirecting to admin page!');
            redirect('/c_admin/index', 'refresh');
            return;
        }

        //$selected_user = $this->user_model->get_by_id($user_id);

        $selected_user_type_value = $this->input->post('uf_privileges');

        $new_user_type = $this->user_type_model->get_by_user_type_name($selected_user_type_value);

        $update_result = $this->user_model->update_user_type($user_id, $new_user_type->getUserTypeId());

        if ($update_result <= 0) {
            log_message('error', 'Changing user privilege not successful!');
            redirect('/c_admin/users_admin', 'refresh');
            return;
        }

        log_message('info', 'Changing user privileges successful!');
        redirect('/c_admin/users_admin', 'refresh');
    }

    public function orders_admin() {
        if (!$this->authentify_admin()) {
            $this->redirectToHomePage();
            return;
        }

        //login or logout in menu
        $template_data = array();
        $this->set_title($template_data, 'Orders administration');
        $this->load_header_templates($template_data);

        $data['actual_user_nick'] = $this->get_user_nick();

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_orders', $data);
    }

    public function order_admin( $order_type ) {
        if (!$this->authentify_provider()) {
            $this->redirectToHomePage();
            return;
        }

        $this->load->library('table');
        $tmpl = array('table_open' => '<table border="1" class="admin_table">');

        $this->table->set_template($tmpl);
        $this->table->set_heading('ID', 'Total', 'Cart', 'Shipping method', 'Payment method', 'Registration address?', 'Detail');

        if ( strtoupper( $order_type ) == 'OPEN' ){
            $all_specific_orders = $this->order_model->get_all_open_orders();
        }else if (  strtoupper( $order_type ) == 'PAID' ){
            $all_specific_orders = $this->order_model->get_all_paid_orders();
        }else if ( strtoupper( $order_type ) == 'SHIPPING' ){
            $all_specific_orders = $this->order_model->get_all_shipping_orders();
        }else{
            $all_specific_orders = NULL;
            log_message('error', 'Parameter order type for order_admin incorrect.' . $order_type);
            redirect('c_admin/orders_admin');
            return;
        }

        foreach ($all_specific_orders as $single_specific_order_model_instance) {

            $atts = array(
                'width' => '800',
                'height' => '600',
                'scrollbars' => 'yes',
                'status' => 'yes',
                'resizable' => 'yes',
                'screenx' => '0',
                'screeny' => '0'
            );

            $anchor = anchor_popup('c_admin/any_order_detail_index/' . $single_specific_order_model_instance->getId(), 'Details', $atts);

            $this->table->add_row(
                    $single_specific_order_model_instance->getId(), $single_specific_order_model_instance->getFinalSum() . ' &euro;', $single_specific_order_model_instance->getCart(), $single_specific_order_model_instance->getShippingMethod(), $single_specific_order_model_instance->getPaymentMethod(), ($single_specific_order_model_instance->getIsShippingAddressRegistrationAddress() == 0 ? 'No' : 'Yes'), $anchor
            );
        }

        $template_data = array();
        $this->set_title($template_data, 'Shipping orders administration');
        $this->load_header_templates($template_data);

        $data['table_data'] = $this->table->generate();

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/orders/v_admin_shipping_orders', $data);
    }

    public function any_order_detail_index($orderId) {

        if (!$this->authentify_provider()) {
            $this->redirectToHomePage();
            return;
        }

        if (is_null($orderId) || !isset($orderId) || !is_numeric($orderId)) {
            $this->redirectToHomePage();
            return;
        }

        $single_paid_order_model_instance = $this->order_model->get_order_by_id($orderId);
        $shipping_method = $this->shipping_method_model->get_shipping_method_by_id($single_paid_order_model_instance->getShippingMethod());
        $payment_method = $this->payment_method_model->get_payment_method_by_id($single_paid_order_model_instance->getPaymentMethod());
        $assigned_cart = $this->cart_model->get_cart_by_id($single_paid_order_model_instance->getCart());

        $ordering_person = $this->user_model->get_user_by_id($assigned_cart->getOrderingPerson());

        $ordered_products_full_info = $this->ordered_product_model->get_ordered_product_full_info_by_cart_id($assigned_cart->getId());

        $this->load->library('table');
        $tmpl = array('table_open' => '<table border="1" class="admin_table">');
        $this->table->set_template($tmpl);


        // order table
        $this->table->set_heading('ID', 'Total', 'Cart', 'Shipping method', 'Payment method', 'Registration address?', 'Status');
        $this->table->add_row(
                $single_paid_order_model_instance->getId(), $single_paid_order_model_instance->getFinalSum() . ' &euro;', $single_paid_order_model_instance->getCart(), $shipping_method->getName(), $payment_method->getName(), ($single_paid_order_model_instance->getIsShippingAddressRegistrationAddress() == 0 ? 'No' : 'Yes'), $single_paid_order_model_instance->getStatus()
        );
        $data['table_data_order'] = $this->table->generate();

        $this->table->clear();

        // cart table
        $this->table->set_heading('ID', 'Sum', 'Status');
        $this->table->add_row(
                $assigned_cart->getId(), $assigned_cart->getSum() . ' &euro;', $assigned_cart->getStatus()
        );
        $data['table_data_cart'] = $this->table->generate();

        $this->table->clear();

        // user table
        $this->table->set_heading('ID', 'Nick', 'Email address', 'Firstname', 'Lastname', 'Gender', 'Phone');
        $this->table->add_row(
                $ordering_person->getId(), $ordering_person->getNick(), mailto($ordering_person->getEmailAddress(), $ordering_person->getEmailAddress()), $ordering_person->getFirstName(), $ordering_person->getLastName(), ($ordering_person->getGender() == 0 ? 'Male' : 'Female'), $ordering_person->getPhoneNumber()
        );
        $data['table_data_user'] = $this->table->generate();

        $this->table->clear();

        // address table
        if ($single_paid_order_model_instance->getIsShippingAddressRegistrationAddress()) {
            log_message('debug', 'Loading registration address');
            $address = $this->address_model->get_address_by_id($ordering_person->getId());
            $this->table->set_heading('ID', 'Street', 'City', 'Zip', 'Country');
            $this->table->add_row(
                    $address->getId(), $address->getStreet(), $address->getCity(), $address->getZip(), $address->getCountry()
            );
            $data['table_data_address'] = $this->table->generate();
        } else {
            log_message('debug', 'Loading order address');
            $order_address = $this->order_address_model->get_order_address_by_id($single_paid_order_model_instance->getOrderAddress());
            $this->table->set_heading('ID', 'Name', 'Address', 'City', 'Zip', 'Country', 'Phone', 'Email');
            $this->table->add_row(
                    $order_address->getId(), $order_address->getName(), $order_address->getAddress(), $order_address->getCity(), $order_address->getZip(), $order_address->getCountry(), $order_address->getPhoneNumber(), $order_address->getEmailAddress()
            );
            $data['table_data_address'] = $this->table->generate();
        }

        $this->table->clear();

        // ordered products including full info
        $this->table->set_heading('ID', 'Count', 'Size', 'Product ID', 'Product name', 'Product price', 'Creator', 'Photo');
        foreach ($ordered_products_full_info as $ordered_product_item) {
            $atts = array(
                'width' => '800',
                'height' => '600',
                'scrollbars' => 'yes',
                'status' => 'yes',
                'resizable' => 'yes',
                'screenx' => '0',
                'screeny' => '0'
            );
            $photoAnchor = anchor_popup('c_admin/product_photo_index/' . $ordered_product_item->getProductId(), 'Photo', $atts);
            $this->table->add_row(
                    $ordered_product_item->getOrderedProductId(), $ordered_product_item->getOrderedProductCount(), $ordered_product_item->getPossibleSizeForProductName(), $ordered_product_item->getProductId(), $ordered_product_item->getProductName(), $ordered_product_item->getProductPrice(), $ordered_product_item->getCreatorNick(), $photoAnchor
            );
        }
        $data['table_data_ordered_products'] = $this->table->generate();

        $data['order_id'] = $orderId;
        $data['order_actual_status'] = $single_paid_order_model_instance->getStatus();
        if ( $single_paid_order_model_instance->getStatus() === Order_model::ORDER_STATUS_OPEN ){
            $data['order_next_status'] = Order_model::ORDER_STATUS_PAID;
        }else if( $single_paid_order_model_instance->getStatus() === Order_model::ORDER_STATUS_PAID ){
            $data['order_next_status'] = Order_model::ORDER_STATUS_SHIPPING;
        }

        $template_data = array();
        $this->set_title($template_data, 'Order detail');
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/orders/v_admin_order_detail', $data);
    }

    public function product_photo_index($productId) {

        if (!$this->authentify_provider()) {
            $this->redirectToHomePage();
            return;
        }

        $sup_povs = $this->supported_point_of_view_model->get_by_product($productId);

        $urls = array();

        if ($sup_povs !== NULL) {
            foreach ($sup_povs as $sup_pov_item) {
                $rasters = $this->supported_point_of_view_model->get_rasters_urls_by_pov($sup_pov_item->getId(), 'url');
                //log_message('debug', print_r($rasters, true));
                foreach ($rasters as $raster_item) {
                    $urls[] = $raster_item->url;
                }
            }
        }

        $product_screen_representation = new Product_screen_representation(
                        NULL, NULL, $urls);

        $data['product_screen_representation'] = $product_screen_representation;

        $template_data = array();
        $this->set_title($template_data, 'Photo detail');
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/orders/v_admin_photo_detail', $data);
    }

    public function change_order_status() {

        if (!$this->authentify_provider()) {
            $this->redirectToHomePage();
            return;
        }
        
        $params = $this->uri->uri_to_assoc(3);
        
        $order_id = $params['order_id'];
        $next_status = $params['next_status'];

        if (is_null($order_id) || !isset($order_id) || !is_numeric($order_id)) {
            log_message('error', 'Parameter for order status change incorrect.' . $order_id);
            redirect('c_admin/orders_admin');
            return;
        }

        $order = $this->order_model->get_order_by_id($order_id);

        if( strtoupper( $next_status ) == 'PAID' ){
            $order->setStatus(Order_model::ORDER_STATUS_PAID);
        }else if ( strtoupper( $next_status ) == 'SHIPPING' ){
            $order->setStatus(Order_model::ORDER_STATUS_SHIPPING);
        }else{
            log_message('error', 'Incorrect params for change_order_status(): ' . print_r( $params ,true));
            redirect('c_admin/orders_admin');
        }
        
        if ($order->update_order() <= 0) {
            // set some flash data
            log_message('error', 'Order update with order ID = ' . $order_id . ' has failed!');
            redirect('c_admin/orders_admin');
        }

        redirect('c_admin/orders_admin', 'refresh');
    }

}

/* End of file c_admin.php */
    /* Location: ./application/controllers/c_admin.php */