<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once( APPPATH . '/models/DataHolders/product_screen_representation.php');

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
        $shopping_cart_of_user = $this->cart_model->get_open_cart_by_owner_id($actual_user_id);

        // redirect to 'shopping cart is empty' screen
        if (is_null($shopping_cart_of_user) || $shopping_cart_of_user === NULL) {
            $this->set_title($template_data, 'Empty shopping cart');
            $this->load->view('templates/header', $template_data);
            $this->load->view('shopping_cart/v_shopping_cart_empty');
            return;
        }

        log_message('debug', 'Shopping cart: ' . print_r($shopping_cart_of_user, TRUE));

        $db_odered_products_full_info = $this->ordered_product_model->get_ordered_product_full_info_by_cart_id($shopping_cart_of_user->getId());

        if (is_null($db_odered_products_full_info) || empty($db_odered_products_full_info)) {
            log_message('error', 'Shopping cart with ID: ' . $shopping_cart_of_user->getId() . ' was initialized but seems to be empty.');
            redirect('/c_finalproducts/index', 'refresh');
            return;
        }
        log_message('debug', print_r($db_odered_products_full_info, TRUE));

        foreach ($db_odered_products_full_info as $db_odered_products_full_info_item) {
            $product_instance_id = $db_odered_products_full_info_item->getProductId();
            $sup_povs = $this->supported_point_of_view_model->get_by_product($product_instance_id);
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

            $db_odered_products_full_info_item->setProductScreenRepresentation(
                    new Product_screen_representation(
                            $db_odered_products_full_info_item->getProductId(), $db_odered_products_full_info_item->getProductName(), $urls)
            );
        }



        $all_shipping_methods = $this->shipping_method_model->get_all_shipping_methods();
        //log_message('debug', 'Shipping methods: ' . print_r($all_shipping_methods, TRUE));
        $all_payment_methods = $this->payment_method_model->get_all_payment_methods();
        //log_message('debug', 'Shipping methods: ' . print_r($all_payment_methods, TRUE));
        //*** setting view data for rendering shopping cart screen
        // setting id of cart
        $data['shopping_cart_id'] = $shopping_cart_of_user->getId();
        // setting sum of cart
        $data['shopping_cart_sum'] = $shopping_cart_of_user->getSum();
        // setting ordered product details
        $data['ordered_products_full_info'] = $db_odered_products_full_info;
        // set shipping methods
        $data['shipping_methods'] = $all_shipping_methods;
        // set payment methods
        $data['payment_methods'] = $all_payment_methods;
        // set II. section subtotal as subtotal from I.section + first shipping method 
        $data['second_section_subtotal'] = $shopping_cart_of_user->getSum() + $all_shipping_methods[0]->getCost();
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
        $this->db->trans_begin();
        {


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
        $shopping_cart_of_user = $this->cart_model->get_open_cart_by_owner_id($actual_user_id);

        // redirect to 'shopping cart is empty' screen
        if (is_null($shopping_cart_of_user) || $shopping_cart_of_user === NULL) {
            log_message('debug', 'Inconsistency of data. Someone tried to remove ordered product from a cart but there is no cart in DB!');
            $this->set_title($template_data, 'No cart for user.');
            $this->load_header_templates($template_data);
            redirect('c_welcome/index');
            return;
        }


        $this->db->trans_begin(); {
            try {
                $shopping_cart_of_user->remove_ordered_product($ordered_product_id);
                $update_result = $shopping_cart_of_user->update_cart();
                if ($update_result <= 0) {
                    throw new CartUpdateException('Cart (' . $shopping_cart_of_user->getId() . ') could not be updated to price ' . $shopping_cart_of_user->getSum());
                }
            } catch (EmptyCartException $ece) {
                log_message('debug', $ece->getMessage());
                // delete cart
                $result_of_deletion = $this->cart_model->delete($shopping_cart_of_user->getId());
                if ($result_of_deletion <= 0) {
                    log_message('error', 'Cart deletion failed! Cart ID: ' . $shopping_cart_of_user->getId());
                    log_message('error', 'Rolling the transaction back!');
                    $this->db->trans_rollback();
                    redirect('/c_shopping_cart/index', 'refresh');return;
                }
            } catch (CartUpdateException $cue) {
                log_message('error', $cue);
                log_message('error', 'Rolling the transaction back!');
                $this->db->trans_rollback();
                redirect('/c_shopping_cart/index', 'refresh');return;
            } catch (Exception $e) {
                log_message('error', $e);
                log_message('error', 'Rolling the transaction back!');
                $this->db->trans_rollback();
                redirect('/c_shopping_cart/index', 'refresh');return;
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

            redirect('/c_shopping_cart/index', 'refresh');
        }
    }

}

class EmptyCartException extends Exception {
    
}

class CartUpdateException extends Exception {
    
}

/* End of file c_shopping_cart.php */
/* Location: ./application/controllers/c_shopping_cart.php */