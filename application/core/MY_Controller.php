<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Basic controller class for retrieving session data.
 * According to selected user type also loads head templates.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class MY_Controller extends CI_Controller {

    /**
     * Login template path
     */
    const LOGIN_TEMPLATE_PATH = 'templates/login';
    /**
     * Logout template path
     */    
    const LOGOUT_TEMPLATE_PATH = 'templates/logout';
    /**
     * Customer tempalte path
     */
    const CUSTOMER_TEMPLATE_PATH = 'templates/customer';
    /**
     * Admin template path
     */
    const ADMIN_TEMPLATE_PATH = 'templates/admin';
    /**
     * Producent template path
     */
    const PRODUCENT_TEMPLATE_PATH = 'templates/producent';
    /**
     * Graphic template path
     */
    const GRAPHIC_TEMPLATE_PATH = 'templates/graphic';
    /**
     * Template path to be rendered if the shoppping cart is empty
     */
    const SHOPPING_CART_EMPTY_TEMPLATE_PATH = 'templates/shopping_cart_empty';
    /**
     * Template path to be rendered if the shopping cart is not empty
     */
    const SHOPPING_CART_NONEMPTY_TEMPLATE_PATH = 'templates/shopping_cart_nonempty';
    
    /**
     * Customer type ID
     */
    const USER_TYPE_CUSTOMER_ID = 1;
    /**
     * Provider type ID
     */
    const USER_TYPE_PROVIDER_ID = 2;
    /**
     * Graphic type ID
     */
    const USER_TYPE_GRAPHIC_ID = 3;
    /**
     * Producer type ID
     */
    const USER_TYPE_PRODUCENT_ID = 4;

    /**
     * Standard constructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Function which handles redirection to welcome page
     */
    protected function redirectToHomePage() {
        redirect('c_welcome/index', 'refresh');
    }

    /**
     * Wrapper function for retrieving all user data from cookies
     * @retval array 
     *  all user cookies data
     */
    protected function get_session_data() {
        $session_data = $this->session->all_userdata();
        if (is_null($session_data) || empty($session_data))
            return NULL;
        else
            return $session_data;
    }

    /**
     * Retrieves user nick from session
     * @retval string
     *  User's nick
     */
    protected function get_user_nick() {
        $session_data = $this->get_session_data();

        if (is_null($session_data)) {
            return NULL;
        } else {
            return $session_data['user_nick'];
        }
    }

    /**
     * Retrieves user id from session
     * @retval string
     *  User's ID
     */
    protected function get_user_id() {
        $session_data = $this->get_session_data();

        if (!isset($session_data['user_id']) || is_null($session_data['user_id']) || empty($session_data['user_id'])) {
            return NULL;
        } else {
            return $session_data['user_id'];
        }
    }

    /**
     * Checks whether user is logged in or not.
     * 
     * @retval boolean
     *  true if user of any type is logged in, false if not
     */
    protected function authentify() {
        // if session then ok
        if ($this->session->userdata('user_nick') != NULL && $this->session->userdata('logged_in') == 1) {

            return TRUE;
        }

        return FALSE;
    }

     /**
     * Checks whether user is customer
     * 
     * @retval boolean
     *  true if user is customer, false if not
     */
    protected function authentify_customer() {

        if (!$this->authentify()) {
            return FALSE;
        }

        // session ok, check if it's admin
        if ($this->session->userdata('user_type') != NULL && $this->session->userdata('user_type') == self::USER_TYPE_CUSTOMER_ID) {

            return TRUE;
        }

        return FALSE;
    }

     /**
     * Checks whether user is provider
     * 
     * @retval boolean
     *  true if user is provider, false if not
     */    
    protected function authentify_provider() {

        if (!$this->authentify()) {
            return FALSE;
        }

        // session ok, check if it's admin
        if ($this->session->userdata('user_type') != NULL && $this->session->userdata('user_type') == self::USER_TYPE_PROVIDER_ID) {

            return TRUE;
        }

        return FALSE;
    }
    
     /**
     * Checks whether user is graphic
     * 
     * @retval boolean
     *  true if user is graphic, false if not
     */     
    protected function authentify_graphic() {

        if (!$this->authentify()) {
            return FALSE;
        }

        // session ok, check if it's admin
        if ($this->session->userdata('user_type') != NULL && $this->session->userdata('user_type') == self::USER_TYPE_GRAPHIC_ID) {

            return TRUE;
        }

        return FALSE;
    }
    
     /**
     * Checks whether user is producent
     * 
     * @retval boolean
     *  true if user is producent, false if not
     */
    protected function authentify_producent() {

        if (!$this->authentify()) {
            return FALSE;
        }

        // session ok, check if it's admin
        if ($this->session->userdata('user_type') != NULL && $this->session->userdata('user_type') == self::USER_TYPE_PRODUCENT_ID) {

            return TRUE;
        }

        return FALSE;
    }     

    /**
     * Sets the title of the page
     * 
     * @param array $template_data
     *  Actual template data array to add new $title on index 'title'
     * @param string $title
     *  Title for a rendered page
     */ 
    protected function set_title(&$template_data, $title = "SnovBord") {
        $template_data['title'] = $title;
    }

    /**
     * Loads header into template data array passed as argument
     * 
     * @param array $template_data
     *  Actual template data array to load headers into
     */
    protected function load_header_templates(&$template_data) {
        $this->load_log_in_or_out_template($template_data);
        $this->load_shopping_cart_template($template_data);
        $this->load_user_template($template_data);
    }

    /**
     * Loads login or logout temaple into template data array passed as argument
     * @param array $template_data
     *  Actual template data array to load login or logout templates into
     */
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

    /**
     * Loads shopping cart tempaltes into passed template data array.
     * 
     * @param array $template_data
     * @return Nothing if user is not authentified
     */
    private function load_shopping_cart_template(&$template_data) {

        if (!$this->authentify()) {
            $template_data['shopping_cart_template'] = $this->parser->parse(constant('MY_Controller::' . 'SHOPPING_CART_EMPTY_TEMPLATE_PATH'), array(), TRUE);
            return;
        }

        $user_id = $this->session->userdata('user_id');

        $user_cart = $this->cart_model->get_open_cart_by_owner_id($user_id);

        if (is_null($user_cart)) {
            $template_data['shopping_cart_template'] = $this->parser->parse(constant('MY_Controller::' . 'SHOPPING_CART_EMPTY_TEMPLATE_PATH'), array(), TRUE);
        } else {
            $template_data['shopping_cart_template'] = $this->parser->parse(constant('MY_Controller::' . 'SHOPPING_CART_NONEMPTY_TEMPLATE_PATH'), array(), TRUE);
        }
    }

    /**
     * Loads user tempaltes into passed template data array.
     * 
     * @param array $template_data
     */  
    private function load_user_template(&$template_data) {

        if ($this->authentify_customer()) {
            // load elements to header for customer/client
            $template_data['customer_template'] = $this->parser->parse(constant('MY_Controller::' . 'CUSTOMER_TEMPLATE_PATH'), array(), TRUE);
        } else if ($this->authentify_provider()) {
            // load elements to header for admin
            $template_data['admin_template'] = $this->parser->parse(constant('MY_Controller::' . 'ADMIN_TEMPLATE_PATH'), array(), TRUE);
        } else if( $this->authentify_producent()){
            // load elements to header for admin
            $template_data['admin_template'] = $this->parser->parse(constant('MY_Controller::' . 'PRODUCENT_TEMPLATE_PATH'), array(), TRUE);
        } if( $this->authentify_graphic()){
            // load elements to header for admin
            $template_data['admin_template'] = $this->parser->parse(constant('MY_Controller::' . 'GRAPHIC_TEMPLATE_PATH'), array(), TRUE);
        }
    }

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
