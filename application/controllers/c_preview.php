<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once( APPPATH . '/models/DataHolders/product_screen_representation.php');

/**
 * Controller class for handling product preview screen outprint.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class C_preview extends MY_Controller {

    /**
     * Basic constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Renders preview page for specific product defined by it's ID.
     * In case of preview page, it is not necessary to retrieve multiple vector or raster data per product, because all necessary graphic data includes single photo representing the product.
     * Photo URL of the product is products's model attribute.
     * 
     * @param int $prodId
     *  ID of the product model
     */
    public function show($prodId) {
        $template_data = array();

        $this->set_title($template_data, 'Product preview');
        $this->load_header_templates($template_data);

        if (is_null($prodId) || !isset($prodId) || !is_numeric($prodId)) {
            log_message('debug', 'Param for c_preview/show not initialized, redirecting to welcome page!');
            redirect('/c_products/index', 'refresh');
            return;
        }


        $previed_product = $this->product_model->get_product($prodId);

        if ($previed_product === NULL) {
            log_message('error', 'Preview screen required to render data for a product with non-existing ID');

            redirect('/c_products/index', 'refresh');
            return;
        }

        $creator = $this->user_model->get_user_by_id($previed_product->getCreator());

        $possible_sizes_array = $this->possible_size_for_product_model->get_all_possible_sizes_by_product($previed_product->getId());

        $output_sizes = array();

        foreach ($possible_sizes_array as $psfp_instance) {
            $output_sizes[$psfp_instance->getId()] = $psfp_instance->getName();
        }

        // photos
        $urls = array();

        $urls[] = $previed_product->getPhotoUrl();

        $product_screen_representation = new Product_screen_representation(
                        $previed_product->getId(), $previed_product->getName(), $urls);


        // set the data for the screen
        $data['previed_product'] = $previed_product;
        $data['previed_product_creator'] = $creator;
        $data['previed_product_size_options'] = $output_sizes;
        $data['previed_product_screen_representation'] = $product_screen_representation;

        /*
          $possible_sizes_array = $this->possible_size_for_product_model->get_all_possible_sizes_for_product_by('pd_id', $previewed_product->pd_id, FALSE);

          $output_sizes = array();

          for ($i = 0; $i < count($possible_sizes_array); $i++) {
          $output_sizes[$possible_sizes_array[$i]['psfp_id']] = $possible_sizes_array[$i]['psfp_name'];
          }

          //log_message('debug', print_r($data['previewed_product_size_options'], TRUE));
          $data['previewed_product_size_options'] = $output_sizes;
         */
        $this->load->view('templates/header', $template_data);
        $this->load->view('v_preview', $data);
    }

    /**
     * Adds product specified by its ID to the cart.
     * @param int $productId
     *  ID of the product to be added into a cart
     */
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

        $product_instance = $this->product_model->get_product($productId);

        log_message('debug', 'Product definition as follows:' . print_r($product_instance, TRUE));

        // check products definition if exists in DB at all
        if (is_null($product_instance) || empty($product_instance) || !isset($product_instance)) {
            log_message('debug', 'Such a product definition does not exist!');
            log_message('debug', 'Redirecting to final products screen');
            redirect('/c_finalproducts/index', 'refresh');
            return;
        }

        // load user's cart
        $users_cart = $this->cart_model->get_open_cart_by_owner_id($actual_user_id);

//        log_message('debug', 'ordered_size_id is:' . $ordered_size_id);
//        $ordered_size_name = $this->possible_size_for_product_model->get_possible_size_for_product_by_id( $ordered_size_id )->getName();
//        log_message('debug', 'Ordered size for product is:' . print_r($ordered_size_name, TRUE));


        /*         * * start TRANSACTION ** */
        $this->db->trans_begin();
        {
            // create user cart if necessary
            if (is_null($users_cart) || $users_cart == NULL || empty($users_cart)) {

                $users_cart = new Cart_model();
                $users_cart->instantiate(0.00, Cart_model::CART_STATUS_INITIALIZED, NULL, $actual_user_id);

                // save
                $users_cart_id = $users_cart->save();
                //$users_cart = $this->cart_model->get( $users_cart_id );

                if ($users_cart_id <= 0) {
                    log_message('debug', 'Creation of user\'s cart failed!. Redirect!');
                    redirect('/c_registration/index', 'refresh');
                } else {
                    $users_cart->setId($users_cart_id);
                }
            }

            $ordered_size_id = $this->input->post('pdf_product_sizes');

            // create ordered product
            $new_ordered_product = new Ordered_product_model();
            $new_ordered_product->instantiate($product_instance->getId(), 1, $ordered_size_id, $users_cart->getId(), $actual_user_id);
            log_message('debug', 'Instantiated ordered product:' . print_r($new_ordered_product, TRUE));
            $new_ordered_product_id = $new_ordered_product->save();
            log_message('debug', '$new_ordered_product_id :' . print_r($new_ordered_product_id, TRUE));

            if ($new_ordered_product_id <= 0) {
                log_message('debug', 'Creation of ordered product failed!. Redirect!');
                log_message('debug', 'Rolling the transaction back!');
                $this->db->trans_rollback();
                redirect('/c_finalproducts/index', 'refresh');
            } else {
                $new_ordered_product->setId($new_ordered_product_id);
            }
            log_message('debug', '$users_cart :' . print_r($users_cart, TRUE));
            // set the sum of a cart by product's definition price
            $users_cart->setSum($users_cart->getSum() + $product_instance->getPrice());

            // update cart's status
            $users_cart->setStatus(Cart_model::CART_STATUS_OPEN);
            log_message('debug', '$users_cart bef :' . print_r($users_cart, TRUE));
            $succ_of_update = $users_cart->update_cart();
            log_message('debug', '$users_cart aft :' . print_r($users_cart, TRUE));
            if ($succ_of_update <= 0) {
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


        redirect('/c_products/index', 'refresh');
    }

}

/* End of file c_preview.php */
/* Location: ./application/controllers/c_preview.php */