<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controller class responsible for order creation and printing out payment screen.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class C_order extends MY_Controller {

    /**
     * Creates order object and saves to DB.
     * Renders HTML page for payment options.
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
        $this->db->trans_begin(); {
            // create and store order_address if necessary
            $is_order_address_set = $this->session->userdata('is_order_address_set');
            log_message('debug', 'is_order_address_set = ' . $is_order_address_set);

            if ($is_order_address_set == true) {
                $new_order_address = new Order_address_model();

                // read from cookies
                $order_address = $this->session->userdata('order_address');
                $new_order_address->instantiate(
                        $order_address['oa_first_name'] . ' ' . $order_address['oa_last_name'], $order_address['oa_address'], $order_address['oa_city'], $order_address['oa_zip'], $order_address['oa_country'], $order_address['oa_phone_number'], $order_address['oa_email_address']
                );

                $new_order_address_id = $new_order_address->save();

                log_message('debug', 'new_order_address_id = ' . $new_order_address_id);
            } else {
                $new_order_address_id = NULL;
            }
            // create order
            $newOrder = new Order_model();
            $newOrder->instantiate($this->input->post('total_sum'), Order_model::ORDER_STATUS_OPEN, $this->input->post('cart_id'), $this->input->post('shipping_method_id'), $this->input->post('payment_method_id'), ($is_order_address_set ? '0' : '1'), $new_order_address_id);
            log_message('debug', 'New order: ' . print_r($newOrder, true));

            $new_order_id = $newOrder->save();
            log_message('debug', '$new_order_id :' . print_r($new_order_id, TRUE));

            if (is_null($new_order_id) || $new_order_id == NULL) {
                log_message('debug', 'Creation of order failed!. Redirect!');
                log_message('debug', 'Rolling the transaction back!');
                $this->db->trans_rollback();
                redirect('/c_shopping_cart/index', 'refresh');
            }

            // edit cart info -> setting order for a cart
            $actual_cart = $this->cart_model->get_cart_by_id($this->input->post('cart_id'));
            $actual_cart->setOrder($new_order_id);
            $actual_cart->setStatus(Cart_model::CART_STATUS_CLOSED);

            $updated_cart_result = $actual_cart->update_cart();

            if ($updated_cart_result <= 0) {
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

        log_message('debug', 'Session data:');


        $data['invoice_id'] = $new_order_id;
        $this->session->set_userdata('invoice_id', $new_order_id);
        $data['total'] = $this->input->post('total_sum');
        $data['ordered_products_full_info'] = $this->ordered_product_model->get_ordered_product_full_info_by_cart_id($actual_cart->getId());
        $data['order_address'] = $this->session->userdata('order_address');

        $data['payment_method'] = $this->payment_method_model->get_payment_method_by_id($this->input->post('payment_method_id'));
        $data['shipping_method'] = $this->shipping_method_model->get_shipping_method_by_id($this->input->post('shipping_method_id'));

        $this->session->set_userdata('payment_amount', $data['total']);

        log_message('debug', print_r($this->session->all_userdata(), true));

        //$data for e-banking !
        // render payment screen for order
        $template_data = array();
        $this->set_title($template_data, 'Payment!');
        $this->load_header_templates($template_data);
        $this->load->view('templates/header', $template_data);
        $this->load->view('order/v_order_payment', $data);
    }
}

/* End of file c_order.php */
/* Location: ./application/controllers/c_order.php */