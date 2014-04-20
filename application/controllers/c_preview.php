<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_preview extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function show($prodId) {
        $template_data = array();

        $this->set_title($template_data, 'Product preview');
        $this->load_header_templates($template_data);

        if (is_null($prodId) || !isset($prodId) || !is_numeric($prodId)) {
            log_message('debug', 'Param for c_preview/show not initialized, redirecting to welcome page!');
            redirect('/c_finalproducts', 'refresh');
            return;
        }


        $previewed_product = $this->product_definition_model->get($prodId);

        $data['previewed_product'] = $previewed_product;
        $data['previewed_product_creator'] = $this->user_model->get($previewed_product->pd_product_creator);
        $possible_sizes_array = $this->possible_size_for_product_model->get_all_possible_sizes_for_product_by('pd_id', $previewed_product->pd_id, FALSE);

        $output_sizes = array();

        for ($i = 0; $i < count($possible_sizes_array); $i++) {
            $output_sizes[$possible_sizes_array[$i]['psfp_id']] = $possible_sizes_array[$i]['psfp_name'];
        }

        //log_message('debug', print_r($data['previewed_product_size_options'], TRUE));
        $data['previewed_product_size_options'] = $output_sizes;

        $this->load->view('templates/header', $template_data);
        $this->load->view('v_preview', $data);
    }

    public function add_to_cart($productId) {

        log_message('debug', 'ATTEMPT: Adding product with pd_id = ' . $productId . ' to cart.');

        $actual_user_id = $this->get_user_id();

        if (is_null($actual_user_id) || $actual_user_id == NULL) {
            log_message('debug', 'Attempt to add product for unlogged user. Redirect!');
            redirect('/c_registration/index', 'refresh');
        }        
        

        // check param first
        if (empty($productId) || !isset($productId) || !is_numeric($productId)) {
            log_message('debug', 'Attempt for adding a product into a cart but function parameter not defined');
            log_message('debug', 'Redirecting to final products screen');
            redirect('/c_finalproducts/index', 'refresh');
            return;
        }

        $products_definition = $this->product_definition_model->get($productId);

        log_message('debug', 'Product definition as follows:' . print_r($products_definition, TRUE));

        // check products definition if exists in DB at all
        if (is_null($products_definition) || empty($products_definition) || !isset($products_definition)) {
            log_message('debug', 'Such a product definition does not exist!');
            log_message('debug', 'Redirecting to final products screen');
            redirect('/c_finalproducts/index', 'refresh');
            return;
        }

        // load user's cart
        $users_cart = $this->cart_model->get_open_cart_by_owner_id($actual_user_id, TRUE);

        // create user cart if necessary
        if (is_null($users_cart) || $users_cart == NULL || empty($users_cart)) {

            $users_cart = new Cart_model();
            $users_cart->instantiate(0, 'INITIALIZED', NULL, $actual_user_id);

            // save
            $users_cart_id = $users_cart->insert_cart();
            $users_cart = $this->cart_model->get($users_cart_id);

            if (is_null($users_cart) || $users_cart == NULL || empty($users_cart)) {
                log_message('debug', 'Creation of user\'s cart failed!. Redirect!');
                redirect('/c_registration/index', 'refresh');
            }
        }

        //var_dump($_POST);
        $ordered_size_id = $this->input->post('pdf_product_sizes');
        log_message('debug', 'ordered_size_id is:' . $ordered_size_id);
        $ordered_size = $this->possible_size_for_product_model->get($ordered_size_id)->psfp_name;
        log_message('debug', 'Ordered size for product is:' . print_r($ordered_size, TRUE));


        /*         * * start TRANSACTION ** */
        $this->db->trans_begin(); {
            // create ordered product
            $new_ordered_product = new Ordered_product_model();
            $new_ordered_product->instantiate($products_definition->pd_id, 1, $ordered_size, $users_cart->c_id, $actual_user_id); //$productId or from input post hidden
            log_message('debug', 'Instantiated ordered produc:' . print_r($new_ordered_product, TRUE));
            $new_ordered_product_id = $new_ordered_product->insert_ordered_product();
            log_message('debug', '$new_ordered_product_id :' . print_r($new_ordered_product_id, TRUE));

            if (is_null($new_ordered_product_id) || $new_ordered_product_id == NULL || empty($new_ordered_product_id)) {
                log_message('debug', 'Creation of ordered product failed!. Redirect!');
                log_message('debug', 'Rolling the transaction back!');
                $this->db->trans_rollback();
                redirect('/c_finalproducts/index', 'refresh');
            }
log_message('debug', '$users_cart :' . print_r($users_cart, TRUE));
            // set the sum of a cart by product's definition price
            $users_cart->c_sum =  $users_cart->c_sum + $products_definition->pd_price ;

            // update cart's sum
            $succ_of_update = $this->cart_model->update($users_cart->c_id, array('c_sum' => $users_cart->c_sum, 'c_status' => 'OPEN'));

            if ($succ_of_update < 0) {
                log_message('debug', 'Update of cart failed!. Redirect!');
                log_message('debug', 'Rolling the transaction back!');
                $this->db->trans_rollback();
                redirect('/c_finalproducts/index', 'refresh');
            }
        }

        if ($this->db->trans_status() === FALSE) {
            log_message('debug', 'Transaction status is FALSE! Rolling the transaction back!');
            $this->db->trans_rollback();
        } else {
            log_message('debug', '... commiting transaction ...!');
            $this->db->trans_commit();
        }


        redirect('/c_finalproducts/index', 'refresh');
    }

}

/* End of file preview.php */
/* Location: ./application/controllers/preview.php */