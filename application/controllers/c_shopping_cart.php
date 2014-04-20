<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_shopping_cart extends MY_Controller {

    /**
     * Renders index page for shopping cart, either empty or with a content.
     * 
     * @retval string
     *  Shopping cart HTML page. 
     */
    public function index() {
        $template_data = array();

        $this->set_title($template_data, 'Shopping cart');
        $this->load_header_templates($template_data);

        $actual_user_id = $this->get_user_id();

        // check if user logged in
        if (is_null($actual_user_id) || $actual_user_id === NULL) {
            log_message('debug', 'Attempt to check shopping cart for unlogged user. Redirect!');
            redirect('/c_registration/index', 'refresh');
        }

        // get shopping cart from DB
        $shopping_cart_of_user = $this->cart_model->get_open_cart_by_owner_id($actual_user_id, TRUE);

        // redirect to 'shopping cart is empty' screen
        if (is_null($shopping_cart_of_user) || $shopping_cart_of_user === NULL || empty($shopping_cart_of_user)) {
            $this->set_title($template_data, 'Empty shopping cart');
            $this->load->view('templates/header', $template_data);
            $this->load->view('shopping_cart/v_shopping_cart_empty');
            return;
        }

        log_message('debug', 'Shopping cart: ' . print_r($shopping_cart_of_user, TRUE));

        $db_odered_products_full_info = $this->ordered_product_model->get_ordered_product_full_info_by_cart_id($shopping_cart_of_user->c_id);

        if (is_null($db_odered_products_full_info) || empty($db_odered_products_full_info)) {
            log_message('error', 'Shopping cart with ID: ' . $shopping_cart_of_user->c_id . ' was initialized but seems to be empty.');
            redirect('/c_finalproducts/index', 'refresh');
            return;
        }
        log_message('debug', print_r($db_odered_products_full_info, TRUE));

        $all_shipping_methods = $this->shipping_method_model->get_all();
        $all_payment_methods = $this->payment_method_model->get_all();

        //*** setting view data for rendering shopping cart screen
        // setting id of cart
        $data['shopping_cart_id'] = $shopping_cart_of_user->c_id; //
        // setting sum of cart
        $data['shopping_cart_sum'] = $shopping_cart_of_user->c_sum;
        // setting ordered product details
        $data['ordered_products'] = $db_odered_products_full_info;
        // set shipping methods
        $data['shipping_methods'] = $all_shipping_methods;
        // set payment methods
        $data['payment_methods'] = $all_payment_methods;
        // set II. section subtotal as subtotal from I.section + first shipping method 
        $data['second_section_subtotal'] = $shopping_cart_of_user->c_sum + $all_shipping_methods[0]->sm_price;
        // fetch this user data
        $data['user_data'] = $this->user_model->get($actual_user_id);

        $this->load->view('templates/header', $template_data);
        $this->load->view('shopping_cart/v_shopping_cart_nonempty', $data);
    }

    public function show_order_preview() {

        $actual_user_id = $this->get_user_id();

        // check if user logged in
        if (is_null($actual_user_id) || $actual_user_id === NULL) {
            log_message('debug', 'Attempt to check shopping cart for unlogged user. Redirect!');
            redirect('/c_registration/index', 'refresh');
            return;
        }

        $products = $this->_parse_products_from_post();
        $cart_id = $this->input->post('cart_id');
        $shipping_method = $this->input->post('shipping_method');
        $payment_method = $this->input->post('payment_method');
        $is_shipping_address_regist_address = ( $this->input->post('selected_address_type') === 'same' ? true : false);

        $order_address = array();
        if ($is_shipping_address_regist_address === false) {

            // prepare order_address from form
            $order_address = $this->_parse_order_address_from_post();

            // set flag
            $this->session->set_userdata(array('is_order_address_set' => true));
        } else {
            // prepare order_address from user settings
            $order_address = $this->_prepare_order_address_acc_to_user_id($actual_user_id);

            // set flag
            $this->session->set_userdata(array('is_order_address_set' => false));
        }

        // clear session first
        $this->session->unset_userdata('order_address');
        // set new data // later!
        //$this->session->set_userdata($order_address);

        $cart_final_sum = 0.0;

        /*         * *  TRANSACTION begin - update all ordered product ** */
        $this->db->trans_begin(); {


            foreach ($products as $product_item) {

                $updated_ordered_product_result = $this->ordered_product_model->update($product_item['item_id'], array('op_amount' => $product_item['item_amount']));
                if ($updated_ordered_product_result < 0) {
                    log_message('debug', 'Update of ordered product failed!. Redirect!');
                    log_message('debug', 'Rolling the transaction back!');
                    $this->db->trans_rollback();
                    redirect('/c_shopping_cart/index', 'refresh');
                }
                $cart_final_sum += ($product_item['item_amount'] * $product_item['item_price']);
            }

            $updated_cart_result = $this->cart_model->update($cart_id, array('c_sum' => $cart_final_sum));
            if ($updated_cart_result < 0) {
                log_message('debug', 'Update of cart failed!. Redirect!');
                log_message('debug', 'Rolling the transaction back!');
                $this->db->trans_rollback();
                redirect('/c_shopping_cart/index', 'refresh');
            }
        }
        /*         * * TRANSACTION end  ** */

        if ($this->db->trans_status() === FALSE) {
            log_message('debug', 'Transaction status is FALSE! Rolling the transaction back!');
            $this->db->trans_rollback();
        } else {
            log_message('debug', '... commiting transaction ...!');
            $this->db->trans_commit();
        }

        $db_odered_products_full_info = $this->ordered_product_model->get_ordered_product_full_info_by_cart_id($cart_id);

        $data['shopping_cart_id'] = $cart_id;
        $data['ordered_products'] = $db_odered_products_full_info;
        $this->session->set_userdata(array('ordered_products' => $db_odered_products_full_info));

        $data['payment_method'] = $this->payment_method_model->get($payment_method);
        $this->session->set_userdata(array('payment_method' => $data['payment_method']));

        $data['shipping_method'] = $this->shipping_method_model->get($shipping_method);
        $this->session->set_userdata(array('shipping_method' => $data['shipping_method']));

        $data['order_address'] = $order_address;
        $this->session->set_userdata(array('order_address' => $order_address));

        $total_final_sum = $cart_final_sum + $data['payment_method']->pm_cost + $data['shipping_method']->sm_price;

        $data['total'] = $total_final_sum;
        $this->session->set_userdata(array('total' => $total_final_sum));


        $template_data = array();
        $this->set_title($template_data, 'Order preview');
        $this->load_header_templates($template_data);
        $this->load->view('templates/header', $template_data);
        $this->load->view('order/v_order_preview', $data);
    }

    private function _parse_products_from_post() {

        $this->load->helper('my_string_helper');

        $products = array();

        foreach ($this->input->post(NULL, TRUE) as $key => $val) {

            if (!startsWith($key, 'ordered_product_')) {
                continue;
            }

            $regex_result = array();

            // process products infoL ordered_product_1_id , ordered_product_1_item_price , ordered_product_1_amount 
            if (preg_match("/(^ordered_product_)([0-9]*)_(.*)/", $key, $regex_result)) {


                if (!key_exists($regex_result[2], $products)) {
                    $products[$regex_result[2]] = array('item_id' => $regex_result[2], 'item_price' => '', 'item_amount' => '');
                }

                if ($regex_result[3] === 'amount') {
                    $products[$regex_result[2]]['item_amount'] = $val;
                } else if ($regex_result[3] === 'item_price') {
                    $products[$regex_result[2]]['item_price'] = $val;
                } else {
                    // parsing ID, not important
                }
            }
        }

        return $products;
    }

    private function _parse_order_address_from_post() {

        $order_address = array();
        $order_address['oa_first_name'] = $this->input->post('tf_first_name');
        $order_address['oa_last_name'] = $this->input->post('tf_last_name');
        $order_address['oa_address'] = $this->input->post('tf_address');
        $order_address['oa_city'] = $this->input->post('tf_city');
        $order_address['oa_zip'] = $this->input->post('tf_zip');
        $order_address['oa_country'] = $this->input->post('tf_country');
        $order_address['oa_phone_number'] = $this->input->post('tf_phone_number');
        $order_address['oa_email_address'] = $this->input->post('tf_email_address');
        return $order_address;
    }

    private function _prepare_order_address_acc_to_user_id($actual_user_id) {

        $actual_user_data = $this->user_model->get($actual_user_id);

        $order_address = array();
        $order_address['oa_first_name'] = $actual_user_data->u_firstname;
        $order_address['oa_last_name'] = $actual_user_data->u_lastname;
        $order_address['oa_address'] = $actual_user_data->u_address;
        $order_address['oa_city'] = $actual_user_data->u_city;
        $order_address['oa_zip'] = $actual_user_data->u_zip;
        $order_address['oa_country'] = $actual_user_data->u_country;
        $order_address['oa_phone_number'] = '';
        $order_address['oa_email_address'] = $actual_user_data->u_email_address;
        return $order_address;
    }

    public function remove_item($ordered_product_id) {

        $template_data = array();


        // get user id
        $actual_user_id = $this->get_user_id();

        // check if user logged in
        if (is_null($actual_user_id) || $actual_user_id === NULL) {
            log_message('debug', 'Attempt to remove ordered product from cart for unlogged user. Redirect!');
            redirect('/c_registration/index', 'refresh');
        }

        // load user cart accordig to his id
        $shopping_cart_of_user = $this->cart_model->get_open_cart_by_owner_id( $actual_user_id );

        // redirect to 'shopping cart is empty' screen
        if (is_null($shopping_cart_of_user) || $shopping_cart_of_user === NULL || empty($shopping_cart_of_user)) {
            log_message('debug', 'Inconsistency of data. Someone tried to remove ordered product from a cart but there is no cart in DB!');
            $this->set_title($template_data, 'No cart for user.');
            $this->load_header_templates($template_data);
            redirect('c_welcome/index');
            return;
        }

        // load ordered_product according to parameter passes
        // delete ordered product from pp_ordered_product table
        $delete_result = $this->ordered_product_model->delete($ordered_product_id);
        if ($delete_result <= 0) {
            log_message('debug', 'Result of deletion is nonpositive. How come user tried to delete ordered product that does not exist? Form generation failed?');
            $this->set_title($template_data, 'Product deletion failed.');
            $this->load_header_templates($template_data);
            $this->load->view('templates/header', $template_data);
            // TODO: redirect to special page
            redirect('c_welcome/index');
            return;
        }

        try{
            // recalculate shopping cart value and store it
            $this->_recal_cart_after_ord_prod_deletion( $shopping_cart_of_user->c_id );
        }catch( EmptyCartException $ece ){
            log_message('debug', $ece->getMessage());
            // delete cart
            $result_of_deletion = $this->cart_model->delete( $shopping_cart_of_user->c_id );
            if ($result_of_deletion <= 0) {
                log_message('error', 'Cart deletion failed! Cart ID: ' . $shopping_cart_of_user->c_id );
            }
        } catch( CartUpdateException $cue){
            log_message('error', $cue );
        }
        
        // refresh page
        redirect('c_shopping_cart/index', 'refresh');
    }

    private function _recal_cart_after_ord_prod_deletion( $shopping_cart_id_of_user ) {
        // get all ordered_products that belong to shopping cart
        $finalSum = 0.0;

        $ordered_products_price_incl = $this->ordered_product_model->get_all_ordered_products_price_including_by_cart_id($shopping_cart_id_of_user);

        if (count( $ordered_products_price_incl ) == 0) {
            // no ordered products in a cart
            throw new EmptyCartException('Cart(ID:'. $shopping_cart_id_of_user .') is empty. Not necessary to calculate it`s value.');
        } else {
            // some products in a cart still left, calculate value
            foreach ($ordered_products_price_incl as $single_ordered_product_with_price) {
                $price_per_ordered_product = ($single_ordered_product_with_price->pd_price * $single_ordered_product_with_price->op_amount);
                $finalSum = $finalSum + $price_per_ordered_product ;
            }
            // update price of a shopping cart
            $update_result = $this->cart_model->update( $shopping_cart_id_of_user, array('c_sum' => $finalSum));
            
            if ( $update_result <= 0 ) {
                // amount of cart could not be updated, throw exception
                throw new CartUpdateException('Cart (' . $shopping_cart_id_of_user . ') could not be updated to price ' . $finalSum );
            }else{
                log_message('debug', 'Shopping cart`s (' . $shopping_cart_id_of_user . ') price successfully updated.');
            }
        }
    }

}

class EmptyCartException extends Exception{
    
    
}

class CartUpdateException extends Exception{
    
    
}

/* End of file c_shopping_cart.php */
/* Location: ./application/controllers/c_shopping_cart.php */