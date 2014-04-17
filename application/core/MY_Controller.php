<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    const LOGIN_TEMPLATE_PATH = 'templates/login';
    const LOGOUT_TEMPLATE_PATH = 'templates/logout';
    const ADMIN_TEMPLATE_PATH = 'templates/admin';
    const SHOPPING_CART_EMPTY_TEMPLATE_PATH = 'templates/shopping_cart_empty';
    const SHOPPING_CART_NONEMPTY_TEMPLATE_PATH = 'templates/shopping_cart_nonempty';

    public function __construct() {
        parent::__construct();
    }

    protected function redirectToHomePage() {
        redirect('c_welcome/index', 'refresh');
    }

    protected function get_session_data() {
        $session_data = $this->session->all_userdata();
        if (is_null($session_data) || empty($session_data))
            return NULL;
        else
            return $session_data;
    }

    protected function get_user_nick() {
        $session_data = $this->get_session_data();

        if (is_null($session_data)) {
            return NULL;
        } else {
            return $session_data['user_nick'];
        }
    }

    protected function get_user_id() {
        $session_data = $this->get_session_data();

        if (!isset($session_data['user_id']) || is_null($session_data['user_id']) || empty($session_data['user_id'])) {
            return NULL;
        } else {
            return $session_data['user_id'];
        }
    }

    protected function authentify() {
        // if session then ok
        if ($this->session->userdata('user_nick') != NULL && $this->session->userdata('logged_in') == 1) {

            return TRUE;
        }

        return FALSE;
    }

    protected function authentify_admin() {

        if (!$this->authentify()) {
            return FALSE;
        }

        // session ok, check if it's admin
        if ($this->session->userdata('user_is_admin') != NULL && $this->session->userdata('user_is_admin') == 1) {

            return TRUE;
        }

        return FALSE;
    }

    protected function set_title(&$template_data, $title = "PowPorn") {
        $template_data['title'] = $title;
    }

    protected function load_header_templates(&$template_data) {
        $this->load_log_in_or_out_template($template_data);
        $this->load_shopping_cart_template($template_data);
        $this->load_admin_template($template_data);
    }

    private function load_log_in_or_out_template(&$template_data) {

        $user_id = $this->session->userdata('user_id');

        if (!isset($user_id) || is_null($user_id) || $user_id == NULL) {
            // login <li></li> loaded
            $template_data['login_or_logout_template'] = $this->parser->parse(constant('MY_Controller::' . 'LOGIN_TEMPLATE_PATH'), array(), TRUE);
        } else {
            // logout <li></li> loaded
            $template_data['login_or_logout_template'] = $this->parser->parse(constant('MY_Controller::' . 'LOGOUT_TEMPLATE_PATH'), array(), TRUE);
        }
    }

    private function load_shopping_cart_template(&$template_data) {

        if (!$this->authentify()) {
            $template_data['shopping_cart_template'] = $this->parser->parse(constant('MY_Controller::' . 'SHOPPING_CART_EMPTY_TEMPLATE_PATH'), array(), TRUE);
            return;
        }

        $user_id = $this->session->userdata('user_id');

        $user_cart = $this->cart_model->get_open_cart_by_owner_id( $user_id );
        
        if ( empty( $user_cart ) || count( $user_cart ) <= 0 ) {
            $template_data['shopping_cart_template'] = $this->parser->parse(constant('MY_Controller::' . 'SHOPPING_CART_EMPTY_TEMPLATE_PATH'), array(), TRUE);
        }else{
            $template_data['shopping_cart_template'] = $this->parser->parse(constant('MY_Controller::' . 'SHOPPING_CART_NONEMPTY_TEMPLATE_PATH'), array(), TRUE);
        }
    }

    private function load_admin_template(&$template_data) {

        if ($this->authentify_admin()) {
            // load elements to header for admin
            $template_data['admin_template'] = $this->parser->parse(constant('MY_Controller::' . 'ADMIN_TEMPLATE_PATH'), array(), TRUE);
        }
    }

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
