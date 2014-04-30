<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once( APPPATH . '/models/DataHolders/product_screen_representation.php');

class C_customer extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        if (!$this->authentify_customer()) {
            $this->redirectToHomePage();
            return;
        }

        //login or logout in menu
        $template_data = array();
        $this->set_title($template_data, 'User interface');
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('v_customer');
    }

    public function profile() {
        if (!$this->authentify_customer()) {
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
        $this->load->view('customer/v_customer_profile', $data);
    }

    public function edit_profile() {

        if (!$this->authentify_customer()) {
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
            $this->load->view('customer/v_customer_profile');
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

        redirect('c_customer/profile', 'refresh');
    }

//    public function products_admin() {
//
//        if (!$this->authentify_provider()) {
//            $this->redirectToHomePage();
//            return;
//        }
//
//        //login or logout in menu
//        $template_data = array();
//        $this->set_title($template_data, 'Admin interface');
//        $this->load_header_templates($template_data);
//
//        $this->load->view('templates/header', $template_data);
//        $this->load->view('admin/v_admin_products');
//    }

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


    public function any_order_detail_index($orderId) {
throw new Exception("Should not be called at all!");
//        if (!$this->authentify_provider()) {
//            $this->redirectToHomePage();
//            return;
//        }
//
//        if (is_null($orderId) || !isset($orderId) || !is_numeric($orderId)) {
//            $this->redirectToHomePage();
//            return;
//        }
//
//        $single_paid_order_model_instance = $this->order_model->get_order_by_id($orderId);
//        $shipping_method = $this->shipping_method_model->get_shipping_method_by_id($single_paid_order_model_instance->getShippingMethod());
//        $payment_method = $this->payment_method_model->get_payment_method_by_id($single_paid_order_model_instance->getPaymentMethod());
//        $assigned_cart = $this->cart_model->get_cart_by_id($single_paid_order_model_instance->getCart());
//
//        $ordering_person = $this->user_model->get_user_by_id($assigned_cart->getOrderingPerson());
//
//        $ordered_products_full_info = $this->ordered_product_model->get_ordered_product_full_info_by_cart_id($assigned_cart->getId());
//
//        $this->load->library('table');
//        $tmpl = array('table_open' => '<table border="1" class="admin_table">');
//        $this->table->set_template($tmpl);
//
//
//        // order table
//        $this->table->set_heading('ID', 'Total', 'Cart', 'Shipping method', 'Payment method', 'Registration address?', 'Status');
//        $this->table->add_row(
//                $single_paid_order_model_instance->getId(), $single_paid_order_model_instance->getFinalSum() . ' &euro;', $single_paid_order_model_instance->getCart(), $shipping_method->getName(), $payment_method->getName(), ($single_paid_order_model_instance->getIsShippingAddressRegistrationAddress() == 0 ? 'No' : 'Yes'), $single_paid_order_model_instance->getStatus()
//        );
//        $data['table_data_order'] = $this->table->generate();
//
//        $this->table->clear();
//
//        // cart table
//        $this->table->set_heading('ID', 'Sum', 'Status');
//        $this->table->add_row(
//                $assigned_cart->getId(), $assigned_cart->getSum() . ' &euro;', $assigned_cart->getStatus()
//        );
//        $data['table_data_cart'] = $this->table->generate();
//
//        $this->table->clear();
//
//        // user table
//        $this->table->set_heading('ID', 'Nick', 'Email address', 'Firstname', 'Lastname', 'Gender', 'Phone');
//        $this->table->add_row(
//                $ordering_person->getId(), $ordering_person->getNick(), mailto($ordering_person->getEmailAddress(), $ordering_person->getEmailAddress()), $ordering_person->getFirstName(), $ordering_person->getLastName(), ($ordering_person->getGender() == 0 ? 'Male' : 'Female'), $ordering_person->getPhoneNumber()
//        );
//        $data['table_data_user'] = $this->table->generate();
//
//        $this->table->clear();
//
//        // address table
//        if ($single_paid_order_model_instance->getIsShippingAddressRegistrationAddress()) {
//            log_message('debug', 'Loading registration address');
//            $address = $this->address_model->get_address_by_id($ordering_person->getId());
//            $this->table->set_heading('ID', 'Street', 'City', 'Zip', 'Country');
//            $this->table->add_row(
//                    $address->getId(), $address->getStreet(), $address->getCity(), $address->getZip(), $address->getCountry()
//            );
//            $data['table_data_address'] = $this->table->generate();
//        } else {
//            log_message('debug', 'Loading order address');
//            $order_address = $this->order_address_model->get_order_address_by_id($single_paid_order_model_instance->getOrderAddress());
//            $this->table->set_heading('ID', 'Name', 'Address', 'City', 'Zip', 'Country', 'Phone', 'Email');
//            $this->table->add_row(
//                    $order_address->getId(), $order_address->getName(), $order_address->getAddress(), $order_address->getCity(), $order_address->getZip(), $order_address->getCountry(), $order_address->getPhoneNumber(), $order_address->getEmailAddress()
//            );
//            $data['table_data_address'] = $this->table->generate();
//        }
//
//        $this->table->clear();
//
//        // ordered products including full info
//        $this->table->set_heading('ID', 'Count', 'Size', 'Product ID', 'Product name', 'Product price', 'Creator', 'Photo');
//        foreach ($ordered_products_full_info as $ordered_product_item) {
//            $atts = array(
//                'width' => '800',
//                'height' => '600',
//                'scrollbars' => 'yes',
//                'status' => 'yes',
//                'resizable' => 'yes',
//                'screenx' => '0',
//                'screeny' => '0'
//            );
//            $photoAnchor = anchor_popup('c_admin/product_photo_index/' . $ordered_product_item->getProductId(), 'Photo', $atts);
//            $this->table->add_row(
//                    $ordered_product_item->getOrderedProductId(), $ordered_product_item->getOrderedProductCount(), $ordered_product_item->getPossibleSizeForProductName(), $ordered_product_item->getProductId(), $ordered_product_item->getProductName(), $ordered_product_item->getProductPrice(), $ordered_product_item->getCreatorNick(), $photoAnchor
//            );
//        }
//        $data['table_data_ordered_products'] = $this->table->generate();
//
//        $data['order_id'] = $orderId;
//        $data['order_actual_status'] = $single_paid_order_model_instance->getStatus();
//        if ($single_paid_order_model_instance->getStatus() === Order_model::ORDER_STATUS_OPEN) {
//            $data['order_next_status'] = Order_model::ORDER_STATUS_PAID;
//        } else if ($single_paid_order_model_instance->getStatus() === Order_model::ORDER_STATUS_PAID) {
//            $data['order_next_status'] = Order_model::ORDER_STATUS_SHIPPING;
//        }
//
//        $template_data = array();
//        $this->set_title($template_data, 'Order detail');
//        $this->load_header_templates($template_data);
//
//        $this->load->view('templates/header', $template_data);
//        $this->load->view('admin/orders/v_admin_order_detail', $data);
    }

    /*
     * necessary ! do not delete
     */
    public function product_photo_index($productId) {

        if (!$this->authentify_customer()) {
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
        $this->load->view('common/v_common_photo_detail', $data);        
//        
//        $sup_povs = $this->supported_point_of_view_model->get_by_product($productId);
//
//        $urls = array();
//
//        if ($sup_povs !== NULL) {
//            foreach ($sup_povs as $sup_pov_item) {
//                $rasters = $this->supported_point_of_view_model->get_rasters_urls_by_pov($sup_pov_item->getId(), 'url');
//                //log_message('debug', print_r($rasters, true));
//                foreach ($rasters as $raster_item) {
//                    $urls[] = $raster_item->url;
//                }
//            }
//        }
//
//        $product_screen_representation = new Product_screen_representation(
//                        NULL, NULL, $urls);
//
//        $data['product_screen_representation'] = $product_screen_representation;
//
//        $template_data = array();
//        $this->set_title($template_data, 'Photo detail');
//        $this->load_header_templates($template_data);
//
//        $this->load->view('templates/header', $template_data);
//        $this->load->view('admin/orders/v_admin_photo_detail', $data);
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

        if (strtoupper($next_status) == 'PAID') {
            $order->setStatus(Order_model::ORDER_STATUS_PAID);
        } else if (strtoupper($next_status) == 'SHIPPING') {
            $order->setStatus(Order_model::ORDER_STATUS_SHIPPING);
        } else {
            log_message('error', 'Incorrect params for change_order_status(): ' . print_r($params, true));
            redirect('c_admin/orders_admin');
        }

        if ($order->update_order() <= 0) {
            // set some flash data
            log_message('error', 'Order update with order ID = ' . $order_id . ' has failed!');
            redirect('c_admin/orders_admin');
        }

        redirect('c_admin/orders_admin', 'refresh');
    }

    public function orders() {

        if (!$this->authentify_customer()) {
            $this->redirectToHomePage();
            return;
        }

        $actual_customer_id = $this->get_user_id();


        $this->load->library('table');
        $tmpl = array('table_open' => '<table border="1" class="admin_table">');

        $this->table->set_template($tmpl);
        $this->table->set_heading('ID', 'Total', 'Cart', 'Shipping method', 'Payment method', 'Registration address?', 'Detail');

        $all_customer_orders = $this->order_model->get_all_by_user_id($actual_customer_id);

        if (is_null($all_customer_orders)) {
            $template_data = array();
            $this->set_title($template_data, 'No orders');
            $this->load_header_templates($template_data);
            $this->load->view('templates/header', $template_data);
            $this->load->view('customer/v_customer_no_orders');
            return;
        }

        foreach ($all_customer_orders as $single_customer_order_model_instance) {

            $atts = array(
                'width' => '800',
                'height' => '600',
                'scrollbars' => 'yes',
                'status' => 'yes',
                'resizable' => 'yes',
                'screenx' => '0',
                'screeny' => '0'
            );

            $anchor = anchor_popup('c_customer/order_detail_index/' . $single_customer_order_model_instance->getId(), 'Details', $atts);

            $this->table->add_row(
                    $single_customer_order_model_instance->getId(), $single_customer_order_model_instance->getFinalSum() . ' &euro;', $single_customer_order_model_instance->getCart(), $single_customer_order_model_instance->getShippingMethod(), $single_customer_order_model_instance->getPaymentMethod(), ($single_customer_order_model_instance->getIsShippingAddressRegistrationAddress() == 0 ? 'No' : 'Yes'), $anchor
            );
        }

        $data['table_data'] = $this->table->generate();

        $template_data = array();
        $this->set_title($template_data, 'My orders');
        $this->load_header_templates($template_data);
        $this->load->view('templates/header', $template_data);
        $this->load->view('customer/v_customer_orders', $data);
    }

    public function order_detail_index($orderId) {

        if (!$this->authentify_customer()) {
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
        $this->table->set_heading('ID', 'Total', 'Shipping method', 'Payment method', 'Registration address?', 'Status');
        $this->table->add_row(
                $single_paid_order_model_instance->getId(), $single_paid_order_model_instance->getFinalSum() . ' &euro;', $shipping_method->getName(), $payment_method->getName(), ($single_paid_order_model_instance->getIsShippingAddressRegistrationAddress() == 0 ? 'No' : 'Yes'), $single_paid_order_model_instance->getStatus()
        );
        $data['table_data_order'] = $this->table->generate();

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
            $this->table->set_heading('Street', 'City', 'Zip', 'Country');
            $this->table->add_row(
                    $address->getStreet(), $address->getCity(), $address->getZip(), $address->getCountry()
            );
            $data['table_data_address'] = $this->table->generate();
        } else {
            log_message('debug', 'Loading order address');
            $order_address = $this->order_address_model->get_order_address_by_id($single_paid_order_model_instance->getOrderAddress());
            $this->table->set_heading('Name', 'Address', 'City', 'Zip', 'Country', 'Phone', 'Email');
            $this->table->add_row(
                    $order_address->getName(), $order_address->getAddress(), $order_address->getCity(), $order_address->getZip(), $order_address->getCountry(), $order_address->getPhoneNumber(), $order_address->getEmailAddress()
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
            $photoAnchor = anchor_popup('c_customer/product_photo_index/' . $ordered_product_item->getProductId(), 'Photo', $atts);
            $this->table->add_row(
                    $ordered_product_item->getOrderedProductId(), $ordered_product_item->getOrderedProductCount(), $ordered_product_item->getPossibleSizeForProductName(), $ordered_product_item->getProductId(), $ordered_product_item->getProductName(), $ordered_product_item->getProductPrice(), $ordered_product_item->getCreatorNick(), $photoAnchor
            );
        }
        $data['table_data_ordered_products'] = $this->table->generate();

        $data['order_id'] = $orderId;

        $template_data = array();
        $this->set_title($template_data, 'Order detail');
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('customer/v_customer_order_detail', $data);
    }

    public function components_customer() {

        if (!$this->authentify_customer()) {
            $this->redirectToHomePage();
            return;
        }
        $template_data = array();
        $this->set_title($template_data, 'Components administration');
        $this->load_header_templates($template_data);

        $data['actual_user_nick'] = $this->get_user_nick();

        $this->load->view('templates/header', $template_data);
        $this->load->view('customer/v_customer_components', $data);
    }

    public function categories_customer_index() {

        if (!$this->authentify_customer()) {
            $this->redirectToHomePage();
            return;
        }

        $template_data = array();
        $this->set_title($template_data, 'Categories');
        $this->load_header_templates($template_data);

        $this->load->view('templates/header', $template_data);
        $this->load->view('customer/v_customer_categories');
    }

    public function add_category() {
        if (!$this->authentify_customer()) {
            $this->redirectToHomePage();
            return;
        }

        $template_data = array();
        $this->set_title($template_data, 'Categories');
        $this->load_header_templates($template_data);

        // field name, error message, validation rules
        $this->form_validation->set_rules('ncf_name', 'Category name', 'trim|required|min_length[1]|max_length[32]|xss_clean');
        $this->form_validation->set_rules('ncf_description', 'Category description', 'trim|max_length[128]|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            $data['error'] = NULL; // no need, prited out by library in a view
            $data['successful'] = NULL;

            // print out validation errors
            $this->load->view('templates/header', $template_data);
            $this->load->view('customer/v_customer_categories', $data);
            return;
        }

        $new_category_name = $this->input->post('ncf_name');
        $new_category_desc = $this->input->post('ncf_description');

        $newCategory = new Category_model();
        $newCategory->setName($new_category_name);
        $newCategory->setDescription($new_category_desc);
        if ($newCategory->save() <= 0) {
            $data['error'] = 'Cannot save category. Insert failed.';
            $data['successful'] = NULL;
            $this->load->view('templates/header', $template_data);
            $this->load->view('customer/v_customer_categories', $data);
            return;
        }

        $data['error'] = NULL;
        $data['successful'] = 'New category saved successfully.';
        $this->load->view('templates/header', $template_data);
        $this->load->view('customer/v_customer_categories', $data);
    }

    public function new_component_customer_index() {

        if (!$this->authentify_customer()) {
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
        $this->load->view('customer/v_customer_new_component_index', $data);
    }

    public function new_component_customer_add() {
        if (!$this->authentify_customer()) {
            $this->redirectToHomePage();
            return;
        }

        $template_data = array();
        $this->set_title($template_data, 'New component interface');
        $this->load_header_templates($template_data);

        $data['actual_user_nick'] = $this->get_user_nick();


        $this->db->trans_begin(); {
            // field name, error message, validation rules
            $this->form_validation->set_rules('ncf_component_name', 'Component name', 'trim|required|min_length[1]|max_length[32]|xss_clean');
            $this->form_validation->set_rules('ncf_component_price', 'Component price', 'trim|required|greater_than[0]|max_length[32]|xss_clean|numeric');
            $this->form_validation->set_rules('ncf_categories', 'Component category', 'required');

            if ($this->form_validation->run() == FALSE) {

                $data['error'] = NULL; // no need, printed out by library in a view
                $data['successful'] = NULL;

                $data['categories_dropdown'] = $this->_prepare_categories();

                // print out validation errors
                $this->load->view('templates/header', $template_data);
                $this->load->view('customer/v_customer_new_component_index', $data);
                return;
            }

            // validation ok
            // load basic upload config
            $components_upl_config = get_components_upload_configuration($this->config);
            // load user nick
            $actual_user_nick = $this->get_user_nick(); // check if not null later
            // load product's name
            $component_name = $this->input->post('ncf_component_name');

            // load product's type
            $component_price = $this->input->post('ncf_component_price');

            // load product's price
            $is_component_stable = ($this->input->post('ncf_component_is_stable') == 'TRUE' ? TRUE : FALSE );

            // load product's sex
            $category = $this->input->post('ncf_categories');

//            // load product's pov
//            $product_pov = $this->input->post('npf_point_of_view_name');
            // calculate file name using user's nick and product's name
            $generated_component_file_name = generate_product_file_name(
                    $actual_user_nick, $component_name
            );

            // add to config
            $components_upl_config['file_name'] = $generated_component_file_name;


            // init library
            $this->load->library('upload', $components_upl_config);

            // try to upload and handle if does not work
            if (!$this->upload->do_upload()) {

                $error = array('error' => $this->upload->display_errors());

                log_message('debug', print_r($error['error'], TRUE));
                log_message('debug', print_r($components_upl_config, TRUE));

                // add error message
                $data['error'] = $error['error'];
                $data['successful'] = NULL;

                $data['categories_dropdown'] = $this->_prepare_categories();

                $this->load->view('templates/header', $template_data);
                $this->load->view('customer/v_customer_new_component_index', $data);
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
                $data['successful'] = NULL;

                $data['categories_dropdown'] = $this->_prepare_categories();

                $this->load->view('templates/header', $template_data);
                $this->load->view('customer/v_customer_new_component_index', $data);
                return;
            }

            try {
                // create component
                $new_component = new Component_model();
                $new_component->instantiate($component_name, $component_price, Component_model::COMPONENT_STATUS_PROPOSED, $is_component_stable, $actual_user_id, $category);
                $new_component_id = $new_component->save();
                if ($new_component_id <= 0) {
                    $data['error'] = 'Cannot save new component into database! Rolling transaction back!';
                    $data['successful'] = NULL;
                    log_message('debug', 'Cannot save new component into database! Rolling transaction back!');
                    $this->db->trans_rollback();

                    $data['categories_dropdown'] = $this->_prepare_categories();
                    $this->load->view('templates/header', $template_data);
                    $this->load->view('customer/v_customer_new_component_index', $data);
                    return;
                } else {
                    log_message('debug', 'Component creation successful');
                }

                // point of view
                $basic_pov = $this->point_of_view_model->get_basic_pov();
                if ($basic_pov === NULL) {
                    // add error message
                    $data['error'] = 'No basic point of view in the database!';
                    $data['successful'] = NULL;
                    log_message('error', 'There is no basic POV in DB, rolling the transaction back!');
                    $this->db->trans_rollback();
                    $data['categories_dropdown'] = $this->_prepare_categories();
                    $this->load->view('templates/header', $template_data);
                    $this->load->view('customer/v_customer_new_component_index', $data);
                    return;
                }

                // create sb_component_raster_representation
                $new_component_raster_model = new Component_raster_model();
                $photoUrl = get_components_upload_path($this->config) . $upload_photo_data['file_name'];
                $new_component_raster_model->instantiate($photoUrl, $new_component_id, $basic_pov->getId());
                $new_component_raster_model_id = $new_component_raster_model->save();

                if (!$new_component_raster_model_id) {
                    // add error message
                    $data['error'] = 'Component raster representation creation in database failed!';
                    $data['successful'] = NULL;
                    log_message('error', 'Component raster representation creation in database failed! Rolling the transaction back!');
                    $this->db->trans_rollback();

                    $data['categories_dropdown'] = $this->_prepare_categories();
                    $this->load->view('templates/header', $template_data);
                    $this->load->view('customer/v_customer_new_component_index', $data);
                    return;
                } else {
                    log_message('debug', 'Component raster representation creation successful');
                }
            } catch (Exception $e) {
                log_message('error', print_r($e, TRUE));
                $data['error'] = $e;
                $data['successful'] = NULL;
                $data['categories_dropdown'] = $this->_prepare_categories();
                $this->db->trans_rollback();
                $this->load->view('templates/header', $template_data);
                $this->load->view('customer/v_customer_new_component_index', $data);
                return;
            }

            // create set of sb_component_vector_representation 
            for ($index = 0; $index <= 9; $index++) {
                $svg_value = $this->input->post('ncf_vector_' . $index);
                if (strlen($svg_value) <= 0) {
                    continue;
                }

                $component_vector_model = new Component_vector_model();
                $component_vector_model->instantiate($svg_value, $new_component_id, $basic_pov->getId());
                if (!$component_vector_model->save()) {
                    log_message('debug', 'Cannot save vector representation for component!');
                    $data['error'] = 'Cannot save vector representation for component!';
                    $this->db->trans_rollback();
                    $data['categories_dropdown'] = $this->_prepare_categories();
                    $this->load->view('templates/header', $template_data);
                    $this->load->view('customer/v_customer_new_component_index', $data);
                    return;
                }
            }

            // create set of sb_component_colours 
            for ($index = 0; $index <= 11; $index++) {
                $colour_value = $this->input->post('colour_' . $index);
                if (strtolower($colour_value) == 'false') {
                    continue;
                }

                $new_component_colour = new Component_colour_model();
                $new_component_colour->instantiate($colour_value, $new_component_id);
                if (!$new_component_colour->save()) {
                    log_message('debug', 'Cannot save colour for component!');
                    $data['error'] = 'Cannot save colour for component!';
                    $this->db->trans_rollback();
                    $data['categories_dropdown'] = $this->_prepare_categories();
                    $this->load->view('templates/header', $template_data);
                    $this->load->view('customer/v_customer_new_component_index', $data);
                    return;
                }
            }
        }
        if ($this->db->trans_status() === FALSE) {
            log_message('debug', 'Transaction status is FALSE! Rolling the transaction back!');
            $data['error'] = 'Transaction status is FALSE! Rolling the transaction back!';
            $this->db->trans_rollback();

            $data['categories_dropdown'] = $this->_prepare_categories();
            $this->load->view('templates/header', $template_data);
            $this->load->view('customer/v_customer_new_component_index', $data);
            return;
        } else {
            log_message('debug', '... commiting transaction ...!');
            $this->db->trans_commit();

            $data['error'] = NULL;
            $data['successful'] = 'New basic product created succesfully!';

            $data['categories_dropdown'] = $this->_prepare_categories();
            $this->load->view('templates/header', $template_data);
            $this->load->view('customer/v_customer_new_component_index', $data);
            return;
        }
    }

    public function products_customer() {

        if (!$this->authentify_customer()) {
            $this->redirectToHomePage();
            return;
        }

        $actual_customer_id = $this->get_user_id();


        $this->load->library('table');
        $tmpl = array('table_open' => '<table border="1" class="admin_table">');

        $this->table->set_template($tmpl);
        $this->table->set_heading('ID', 'Name', 'Price' . ' &euro;', 'Description', 'Sex', 'Status', 'Photo');

        $all_customer_products = $this->product_model->get_products_by_creator($actual_customer_id);

        if (is_null($all_customer_products)) {
            $template_data = array();
            $this->set_title($template_data, 'No own products');
            $this->load_header_templates($template_data);
            $this->load->view('templates/header', $template_data);
            $this->load->view('customer/v_customer_no_own_products');
            return;
        }

        foreach ($all_customer_products as $single_customer_product_model_instance) {

            $atts = array(
                'width' => '800',
                'height' => '600',
                'scrollbars' => 'yes',
                'status' => 'yes',
                'resizable' => 'yes',
                'screenx' => '0',
                'screeny' => '0'
            );

            $anchor = anchor_popup('c_customer/product_detail_index/' . $single_customer_product_model_instance->getId(), 'Photo', $atts);

            $this->table->add_row(
                    $single_customer_product_model_instance->getId(), $single_customer_product_model_instance->getName(), $single_customer_product_model_instance->getPrice(), $single_customer_product_model_instance->getDescription(), $single_customer_product_model_instance->getSex(), $single_customer_product_model_instance->getAcceptanceStatus(), $anchor
            );
        }

        $data['table_data'] = $this->table->generate();

        $template_data = array();
        $this->set_title($template_data, 'My orders');
        $this->load_header_templates($template_data);
        $this->load->view('templates/header', $template_data);
        $this->load->view('customer/v_customer_products', $data);
    }

    public function product_detail_index( $productId ) {
        
        if (!$this->authentify_customer()) {
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
        $this->load->view('customer/v_customer_product_detail', $data);
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

}

/* End of file c_admin.php */
    /* Location: ./application/controllers/c_admin.php */