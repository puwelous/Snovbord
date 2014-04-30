<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once( APPPATH . '/models/DataHolders/product_screen_representation.php');

class C_producent extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

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

    public function new_product_admin_index() {

        if (!$this->authentify_provider()) {
            $this->redirectToHomePage();
            return;
        }

        $data['actual_user_nick'] = $this->get_user_nick();

        $presentPointsOfView = $this->point_of_view_model->get_pov_names_distinct();
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


        $this->db->trans_begin();
        {        // field name, error message, validation rules
            $this->form_validation->set_rules('npf_product_name', 'Product name', 'trim|required|min_length[1]|max_length[32]|xss_clean');
            $this->form_validation->set_rules('npf_available_sizes', 'Product sizes', 'required');
            $this->form_validation->set_rules('npf_product_price', 'Product price', 'trim|required|greater_than[0]|max_length[32]|xss_clean|numeric');
            $this->form_validation->set_rules('npf_product_desc', 'Product description', 'trim|required|min_length[1]|max_length[256]');
            //$this->form_validation->set_rules('npf_point_of_view_name', 'Point of view', 'trim|required|min_length[1]|max_length[64]');

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
                return;
            }

            try {

                // create sb_basic_product
                $new_basic_product = new Basic_product_model();
                $new_basic_product->instantiate($product_price, $actual_user_id);
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

                // create sb_product
                $new_product = new Product_model();
                $new_product->instantiate($product_name, $product_price, $product_desc, $product_sex, Product_model::PRODUCT_STATUS_ACCEPTED, $actual_user_id, $new_basic_product_id);
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

                // point of view
                $basic_pov = $this->point_of_view_model->get_basic_pov();
                if ($basic_pov === NULL) {
                    // add error message
                    $data['error'] = 'No basic point of view in the database!';
                    $data['successful'] = NULL;
                    log_message('error', 'There is no basic POV in DB, rolling the transaction back!');
                    $this->db->trans_rollback();

                    $this->load->view('templates/header', $template_data);
                    $this->load->view('admin/v_admin_new_product_index', $data);
                    return;
                }

//                $pov_presence = $this->input->post('npf_is_point_of_view_present');
//                if ($pov_presence === 'new_pov') {
//                    // load product's pov
//                    $product_pov = $this->input->post('npf_point_of_view_name');
//                } else if ($pov_presence === 'old_pov') {
//                    $product_pov = $this->input->post('npf_present_povs');
//                    ;
//                } else {
//                    log_message('error', 'Input type radio value neither new_pov neither old_pov! How come!!!');
//                    log_message('error', 'POV creation in database failed! Rolling the transaction back!');
//                    $this->db->trans_rollback();
//                    $this->load->view('templates/header', $template_data);
//                    $this->load->view('admin/v_admin_new_product_index', $data);
//                    return;
//                }
//
//                $new_supported_point_of_view = new Supported_point_of_view_model();
//                $new_supported_point_of_view->instantiate($product_pov, $new_product_id);
//                $new_supported_point_of_view_id = $new_supported_point_of_view->save();
//                if ($new_supported_point_of_view_id <= 0) {
//                    // add error message
//                    $str = $this->db->last_query();
//                    log_message('error', 'Last query: ' . $str);
//
//                    $data['error'] = 'POV creation in database failed!';
//                    $data['successful'] = NULL;
//                    log_message('error', 'Point of view creation in database failed! Rolling the transaction back!');
//                    $this->db->trans_rollback();
//
//                    $this->load->view('templates/header', $template_data);
//                    $this->load->view('admin/v_admin_new_product_index', $data);
//                    return;
//                } else {
//                    log_message('debug', 'Point of view creation successful');
//                }
                // create sb_basic_product_raster_representation
                $new_basic_product_raster = new Basic_product_raster_model();
                $photoUrl = get_basic_product_upload_path($this->config) . $upload_photo_data['file_name'];
                $new_basic_product_raster->instantiate($photoUrl, $new_basic_product_id, $basic_pov->getId());
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
            for ($index = 0; $index <= 9; $index++) {
                $svg_value = $this->input->post('npf_vector_' . $index);
                if (strlen($svg_value) <= 0) {
                    continue;
                }

                $basic_product_vector_model = new Basic_product_vector_model();
                $basic_product_vector_model->instantiate($svg_value, $new_basic_product_id, $basic_pov->getId());
                if ($basic_product_vector_model->save() <= 0) {
                    log_message('debug', 'Cannot save vector representation for basic product!');
                    $this->db->trans_rollback();
                    $this->load->view('templates/header', $template_data);
                    $this->load->view('admin/v_admin_new_product_index', $data);
                    return;
                }
            }
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

        $this->db->trans_begin(); {
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

    public function qualify_component_admin() {

        if (!$this->authentify_provider()) {
            $this->redirectToHomePage();
            return;
        }


        $proposed_components = $this->component_model->get_proposed_components();
        if ( !$proposed_components) {
            $template_data = array();
            $this->set_title($template_data, 'No proposed components');
            $this->load_header_templates($template_data);
            $this->load->view('templates/header', $template_data);
            $this->load->view('admin/v_admin_no_proposed_components');
            return;
        }

        $this->load->library('table');
        $tmpl = array('table_open' => '<table border="1" class="admin_table">');

        $this->table->set_template($tmpl);
        $this->table->set_heading('ID', 'Name', 'Price' . ' &euro;', 'Status', 'Is stable?', 'Creator', 'Category', 'Photo');


        foreach ($proposed_components as $single_proposed_component_instance) {

            $form_instance = form_open('c_admin/change_component_status/' . $single_proposed_component_instance->getId());

            $statusSelectOptions = array(
                'declined_unseen' => 'Decline',
                'accepted' => 'Accept'
            );

            $statusSelect = form_dropdown('cf_status', $statusSelectOptions, 'accepted');

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

            $anchorPhoto = anchor_popup('c_admin/component_photo_index/' . $single_proposed_component_instance->getId(), 'Photo', $atts);
            $anchorUser = anchor_popup('c_admin/users_admin/' . $single_proposed_component_instance->getCreator(), $this->user_model->get_user_by_id($single_proposed_component_instance->getCreator())->getNick(), $atts);

            $categoryName = $this->category_model->get_category_by_id($single_proposed_component_instance->getCategory())->getName();

            $this->table->add_row(
                    $single_proposed_component_instance->getId(), $single_proposed_component_instance->getName(), $single_proposed_component_instance->getPrice(), $form_instance . $statusSelect . $submit_button . form_close(), ($single_proposed_component_instance->getIsStable() == '1' ? 'Yes' : 'No'), $anchorUser, $categoryName, $anchorPhoto
            );
        }

        $data['table_data'] = $this->table->generate();

        $template_data = array();
        $this->set_title($template_data, 'Prodposed components administration');
        $this->load_header_templates($template_data);
        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_proposed_components', $data);
    }

    public function components_admin() {

        if (!$this->authentify_provider()) {
            $this->redirectToHomePage();
            return;
        }

        $template_data = array();
        $this->set_title($template_data, 'Components administration');
        $this->load_header_templates($template_data);

        $data['actual_user_nick'] = $this->get_user_nick();

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_components', $data);
    }

    public function new_component_admin_index() {

        if (!$this->authentify_provider()) {
            $this->redirectToHomePage();
            return;
        }

        $data['actual_user_nick'] = $this->get_user_nick();

        $data['categories_dropdown'] = $this->_prepare_categories();


//        $presentPointsOfView = $this->point_of_view_model->get_pov_names_distinct();
//        log_message('debug', print_r($presentPointsOfView, TRUE));
//
//        $with_value_included_array = array();
//
//        foreach ($presentPointsOfView as $value) {
//            $with_value_included_array[$value] = $value;
//        }
//
//
//        $data['with_value_included_array'] = $with_value_included_array;
        //login or logout in menu
        $template_data = array();
        $this->set_title($template_data, 'New component interface');
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('admin/v_admin_new_component_index', $data);
    }

    private function _prepare_categories() {

        $all_categories = $this->category_model->get_all_categories();

        if (is_null($all_categories)) {
            // no categories!
            //let the array be empty, no problem
            return array();
        } else {
            $categories_dropdown = array();
            foreach ($all_categories as $single_category) {
                $categories_dropdown[$single_category->getId()] = $single_category->getName();
            }
            return $categories_dropdown;
        }
    }

    public function final_products_admin() {

        if (!$this->authentify_provider()) {
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


    public function users_admin($singleUserId = NULL) {

        if (!$this->authentify_provider()) {
            $this->redirectToHomePage();
            return;
        }

        $this->load->library('table');
        $tmpl = array('table_open' => '<table border="1" class="admin_table">');

        $this->table->set_template($tmpl);
        $this->table->set_heading('Nick', 'Firstname', 'Lastname', 'Email', 'Phone', 'Privilege', 'Change privilege');

        if (!isset($singleUserId) || is_null($singleUserId)) {
            $all_users = $this->user_model->get_all_users();
        } else {
            $all_users = array();
            $all_users[] = $this->user_model->get_user_by_id($singleUserId);
        }


        foreach ($all_users as $single_user_model_instance) {

            $user_type_name = $this->user_type_model->get($single_user_model_instance->getUserType())->usrtp_name;

            $form_instance = form_open('c_admin/change_privilege/' . $single_user_model_instance->getId());

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

    public function component_photo_index($componentId) {
        if (!$this->authentify_provider()) {
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
        $this->load->view('admin/v_admin_component_detail', $data);
    }

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

    public function change_component_status( $componentId ) {

        if (!$this->authentify_provider()) {
            $this->redirectToHomePage();
            return;
        }

        if (!isset($componentId) || is_null($componentId) || !is_numeric($componentId)) {
            log_message('debug', 'Param for c_admin/change_component_status not initialized, redirecting to admin page!');
            redirect('/c_admin/index', 'refresh');
            return;
        }

        $updated_component = $this->component_model->get_component_by_id( $componentId );
        if( is_null($updated_component )){
            log_message('debug', 'Such a component does not exist!, Searched for ID: ' . $componentId);
            redirect('/c_admin/index', 'refresh');
            return;
        }
        
        $selected_component_status_value = $this->input->post('cf_status');

        if(strtoupper($selected_component_status_value) == Component_model::COMPONENT_STATUS_ACCEPTED){
            $updated_component->setAcceptanceStatus( Component_model::COMPONENT_STATUS_ACCEPTED );
        }else{
            $updated_component->setAcceptanceStatus( Component_model::COMPONENT_STATUS_DECLINED_UNSEEN );
        }     

        $update_result = $updated_component->update_component();

        if ($update_result <= 0) {
            log_message('error', 'Changing component status not successful!');
            redirect('/c_admin/index', 'refresh');
            return;
        }

        log_message('info', 'Changing user privileges successful!');
        redirect('/c_admin/qualify_component_admin', 'refresh');
    }    
    
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

/* End of file c_admin.php */
    /* Location: ./application/controllers/c_admin.php */