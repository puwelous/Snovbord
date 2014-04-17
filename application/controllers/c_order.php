<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


//require_once ("paypalfunctions.php");

class C_order extends MY_Controller {
    /*
     * TODO: show order
     */

    public function index() {
        echo 'Showing order not supported yet ;) TODO!';
//        $template_data = array();
//
//        $this->set_title($template_data, 'Order');
//        $this->load_header_templates($template_data);
//
//        $actual_user_id = $this->get_user_id();
//
//        // check if user logged in
//        if (is_null($actual_user_id) || $actual_user_id === NULL) {
//            log_message('debug', 'Attempt to check shopping cart for unlogged user. Redirect!');
//            redirect('/c_registration/index', 'refresh');
//        }
//
//        // get shopping cart from DB
//        $shopping_cart_of_user = $this->cart_model->get_open_cart_by_owner_id($actual_user_id, TRUE);
//
//        // redirect to 'shopping cart is empty' screen
//        if (is_null($shopping_cart_of_user) || $shopping_cart_of_user === NULL || empty($shopping_cart_of_user)) {
//            $this->set_title($template_data, 'Empty shopping cart');
//            $this->load->view('templates/header', $template_data);
//            $this->load->view('v_shopping_cart_empty');
//            $this->load->view('templates/footer');
//            return;
//        }
//
//        log_message('debug', 'Shopping cart: ' . print_r($shopping_cart_of_user, TRUE));
//
//        $db_odered_products_full_info = $this->ordered_product_model->get_ordered_product_full_info_by_cart_id($shopping_cart_of_user->c_id);
//
//        if (is_null($db_odered_products_full_info) || empty($db_odered_products_full_info)) {
//            log_message('error', 'Shopping cart with ID: ' . $shopping_cart_of_user->c_id . ' was initialized but seems to be empty.');
//            redirect('/c_finalproducts/index', 'refresh');
//            return;
//        }
//        log_message('debug', print_r($db_odered_products_full_info, TRUE));
//
//        $all_shipping_methods = $this->shipping_method_model->get_all();
//        $all_payment_methods = $this->payment_method_model->get_all();
//
//        //*** setting view data for rendering shopping cart screen
//        // setting id of cart
//        $data['shopping_cart_id'] = $shopping_cart_of_user->c_id; //
//        // setting sum of cart
//        $data['shopping_cart_sum'] = $shopping_cart_of_user->c_sum;
//        // setting ordered product details
//        $data['ordered_products'] = $db_odered_products_full_info;
//        // set shipping methods
//        $data['shipping_methods'] = $all_shipping_methods;
//        // set payment methods
//        $data['payment_methods'] = $all_payment_methods;
//        // set II. section subtotal as subtotal from I.section + first shipping method 
//        $data['second_section_subtotal'] = $shopping_cart_of_user->c_sum + $all_shipping_methods[0]->sm_price;
//        // fetch this user data
//        $data['user_data'] = $this->user_model->get($actual_user_id);
//
//        $this->load->view('templates/header', $template_data);
//        $this->load->view('v_shopping_cart', $data);
//        $this->load->view('templates/footer');
    }

    /**
     * Creates order object and saves to DB.
     * Renders HTML page for payment options.
     * @retval string
     *  HTML representation of order page.
     */
    public function create_order() {

        $actual_user_id = $this->get_user_id();

        // check if user logged in
        if (is_null($actual_user_id) || $actual_user_id === NULL) {
            log_message('debug', 'Attempt to check shopping cart for unlogged user. Redirect!');
            redirect('/c_registration/index', 'refresh');
            return;
        }


        /*         * * start TRANSACTION ** */
        $this->db->trans_begin();
        {
            // create and store order_address if necessary
            $is_order_address_set = $this->session->userdata('is_order_address_set');
            log_message('debug', 'is_order_address_set = ' . $is_order_address_set);

            if ($is_order_address_set == true) {
                $new_order_address = new Order_address_model();

                // read from cookies
                $new_order_address->instantiate(
                        $this->session->userdata('oa_name'), $this->session->userdata('oa_address'), $this->session->userdata('oa_city'), $this->session->userdata('oa_zip'), $this->session->userdata('oa_country'), $this->session->userdata('oa_phone_number'), $this->session->userdata('oa_email_address')
                );

                $new_order_address_id = $new_order_address->insert_order_address();

                log_message('debug', 'new_order_address_id = ' . $new_order_address_id);
            } else {
                $new_order_address_id = NULL;
            }
            // create order
            $newOrder = new Order_model();
            $newOrder->instantiate(
                    $this->input->post('cart_id'), $this->input->post('shipping_method_id'), $this->input->post('payment_method_id'), ($is_order_address_set ? '1' : '0'), 'OPEN', $this->input->post('total_sum'), $new_order_address_id);
            log_message('debug', 'New order: ' . print_r($newOrder, true));

            $new_order_id = $newOrder->insert_order();
            log_message('debug', '$new_order_id :' . print_r($new_order_id, TRUE));

            if (is_null($new_order_id) || $new_order_id == NULL || empty($new_order_id)) {
                log_message('debug', 'Creation of order failed!. Redirect!');
                log_message('debug', 'Rolling the transaction back!');
                $this->db->trans_rollback();
                redirect('/c_shopping_cart/index', 'refresh');
            }

            // edit cart info -> setting order for a cart
            $updated_cart_result = $this->cart_model->update($this->input->post('cart_id'), array('o_id' => $new_order_id, 'c_status' => 'closed'));

            if ($updated_cart_result < 0) {
                log_message('debug', 'Update of cart failed!. Redirect!');
                log_message('debug', 'Rolling the transaction back!');
                $this->db->trans_rollback();
                redirect('/c_shopping_cart/index', 'refresh');
            }
        }

        if ($this->db->trans_status() === FALSE) {
            log_message('debug', 'Transaction status is FALSE! Rolling the transaction back!');
            $this->db->trans_rollback();
            redirect('/c_shopping_cart/index', 'refresh');
            return;
        } else {
            log_message('debug', '... commiting transaction ...!');
            $this->db->trans_commit();
        }

        $data['invoice_id'] = $new_order_id;
        $this->session->set_userdata(array('invoice_id' => $new_order_id));
        $data['total'] = $this->input->post('total_sum');
        $data['ordered_products'] = $this->session->userdata('ordered_products'); //$this->ordered_product_model->get_ordered_product_full_info_by_cart_id( $this->input->post('cart_id') );
        $data['order_address'] = $this->session->userdata('order_address');
        $data['payment_method'] = $this->session->userdata('payment_method');
        $data['shipping_method'] = $this->session->userdata('shipping_method');

        $this->session->set_userdata(array('payment_amount' => $data['total']));

        //$data for e-banking !
        // render payment screen for order
        $template_data = array();
        $this->set_title($template_data, 'Payment!');
        $this->load_header_templates($template_data);
        $this->load->view('templates/header', $template_data);
        $this->load->view('order/v_order_payment', $data);
        $this->load->view('templates/footer');
    }

//    public function view_order() {
//
//        $template_data = array();
//
//        $this->set_title($template_data, 'Order');
//        $this->load_header_templates($template_data);
//
//        $actual_user_id = $this->get_user_id();
//
//        // check if user logged in
//        if (is_null($actual_user_id) || $actual_user_id === NULL) {
//            log_message('debug', 'Attempt to check shopping cart for unlogged user. Redirect!');
//            redirect('/c_registration/index', 'refresh');
//            return;
//        }
//
//        $products = array();
//
//        foreach ($this->input->post(NULL, TRUE) as $key => $val) {
//
//            if (!$this->_startsWith($key, 'ordered_product_')) {
//                continue;
//            }
//
//            $regex_result = array();
//
//            // process products infoL ordered_product_1_id , ordered_product_1_item_price , ordered_product_1_amount 
//            if (preg_match("/(^ordered_product_)([0-9]*)_(.*)/", $key, $regex_result)) {
//
//
//                if (!key_exists($regex_result[2], $products)) {
//                    $products[$regex_result[2]] = array('item_id' => $regex_result[2], 'item_price' => '', 'item_amount' => '');
//                }
//
//                if ($regex_result[3] === 'amount') {
//                    $products[$regex_result[2]]['item_amount'] = $val;
//                } else if ($regex_result[3] === 'item_price') {
//                    $products[$regex_result[2]]['item_price'] = $val;
//                } else {
//                    // parsing ID, not important
//                }
//            }
//        }
//
//        // var_dump($products);
//        $cart_final_sum = 0.0;
//
//        $cart_id = $this->input->post('cart_id');
//        $shipping_method = $this->input->post('shipping_method');
//        $payment_method = $this->input->post('payment_method');
//        $is_shipping_address_regist_address = ( $this->input->post('selected_address_type') === 'same' ? true : false);
//
//        $order_address = array();
//        if ($is_shipping_address_regist_address === false) {
//            // prepare order_address from form
//            $order_address['oa_name'] = $this->input->post('tf_name');
//            $order_address['oa_address'] = $this->input->post('tf_address');
//            $order_address['oa_city'] = $this->input->post('tf_city');
//            $order_address['oa_zip'] = $this->input->post('tf_zip');
//            $order_address['oa_country'] = $this->input->post('tf_country');
//            $order_address['oa_phone_number'] = $this->input->post('tf_phone_number');
//            $order_address['oa_email_address'] = $this->input->post('tf_email_address');
//
//            // clear first
//            $this->session->unset_userdata($order_address);
//
//            // set flag
//            $this->session->set_userdata(array('is_order_address_set' => true));
//
//            // set data
//            $this->session->set_userdata($order_address);
//        } else {
//            // prepare order_address from user settings
//            // load data
//            $actual_user_data = $this->user_model->get($actual_user_id);
//
//            // set fields
//            $order_address['oa_name'] = $actual_user_data->u_firstname . ' ' . $actual_user_data->u_lastname;
//            $order_address['oa_address'] = $actual_user_data->u_address;
//            $order_address['oa_city'] = $actual_user_data->u_city;
//            $order_address['oa_zip'] = $actual_user_data->u_zip;
//            $order_address['oa_country'] = $actual_user_data->u_country;
//            $order_address['oa_phone_number'] = '';
//            $order_address['oa_email_address'] = $actual_user_data->u_email_address;
//
//            // clear first
//            $this->session->unset_userdata($order_address);
//
//            // set flag
//            $this->session->set_userdata(array('is_order_address_set' => false));
//
//            //$this->session->set_userdata($order_address);             
//        }
//
//
//
//        $db_odered_products_full_info = $this->ordered_product_model->get_ordered_product_full_info_by_cart_id($cart_id);
//
//        $data['shopping_cart_id'] = $cart_id;
//        $data['ordered_products'] = $db_odered_products_full_info;
//        $data['payment_method'] = $this->payment_method_model->get($payment_method);
//        $data['shipping_method'] = $this->shipping_method_model->get($shipping_method);
//        $data['order_address'] = $order_address;
//
//        $total_final_sum = $cart_final_sum + $data['payment_method']->pm_cost + $data['shipping_method']->sm_price;
//
//        $data['total'] = $total_final_sum;
//
//        $this->load->view('templates/header', $template_data);
//        $this->load->view('v_order_proposal', $data);
//        $this->load->view('templates/footer');
//    }



}

/* End of file c_shopping_cart.php */
/* Location: ./application/controllers/c_shopping_cart.php */