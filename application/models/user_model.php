<?php

class User_model extends MY_Model {

    public $_table = 'pp_user';
    public $primary_key = 'u_id';
    public $nick;
    public $firstname;
    public $lastname;
    public $emailAddress;
    public $password;
    public $gender;
    public $deliveryAddress;
    public $address;
    public $city;
    public $zip;
    public $country;
    public $isAdmin;

    public $protected_attributes = array( 'u_id' );
    
    public function __construct() {
        parent::__construct();
    }

    public function setAll($nick, $firstname, $lastname, $emailAddress, $password, $gender, $address, $deliveryAddress, $city, $zip, $country, $isAdmin) {

        $this->nick = $nick;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->emailAddress = $emailAddress;
        $this->password = md5($password); //MD5
        if ($gender == 'male') {
            $this->gender = 0;
        } else if ($gender == 'female') {
            $this->gender = 1;
        } else {
            $this->gender = -1;
        }
        $this->deliveryAddress = $deliveryAddress;
        $this->address = $address;
        $this->city = $city;
        $this->zip = $zip;
        $this->country = $country;
        if ($isAdmin == FALSE) {
            $this->isAdmin = 0;
        } else {
            $this->isAdmin = 1;
        }
    }

    public function add_user(User_model $user) {

        $this->user_model->insert(
                array(
                    'u_nick' => $user->nick,
                    'u_firstname' => $user->firstname,
                    'u_lastname' => $user->lastname,
                    'u_email_address' => $user->emailAddress,
                    'u_password' => $user->password,
                    'u_gender' => $user->gender,
                    'u_delivery_address' => $user->deliveryAddress,
                    'u_address' => $user->address,
                    'u_city' => $user->city,
                    'u_zip' => $user->zip,
                    'u_country' => $user->country,
                    'u_is_admin' => $user->isAdmin
        ));
    }

    public function get_by_email_or_nick_and_password($email_or_nick, $password) {

        $where = "(u_email_address=".$this->db->escape($email_or_nick)." OR u_nick=". $this->db->escape($email_or_nick) .") AND u_password=".$this->db->escape(md5($password))."";
        $this->db->where($where);
        $this->db->limit(1);

        $query = $this->db->get($this->_table);

        log_message('debug', print_r($this->db->last_query(), TRUE));

        if ($query->num_rows() > 0) {
            $row = $query->row();
            log_message('debug', print_r($row, TRUE));
            return $row;
        } else {
            return NULL;
        }
    }
    
    public function is_present_by( $column, $value){
        $row = $this->user_model->as_object()->get_by( $column, $value );
        
        return $row;
    }
    
    public function get_user_by( $column, $value){
        $row = $this->user_model->as_object()->get_by( $column, $value );
        
        return $row;
    }    

}

/* End of file company_model.php */
/* Location: ./application/models/company_model.php */
