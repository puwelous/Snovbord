<?php

class User_model extends MY_Model {

    protected $_table = 'sb_user';
    protected $primary_key = 'usr_id';
    private $userId;
    private $nick;
    private $emailAddress;
    private $firstname;
    private $lastname;
    private $phoneNumber;
    private $gender;
    private $password;
    private $address;
    private $userType;
//    private $city;
//    private $zip;
//    private $country;
//    private $isAdmin;

    public $protected_attributes = array('usr_id');

    public function __construct() {
        parent::__construct();
    }

    public function instantiate(
    $nick, $emailAddress, $firstname, $lastname, $phoneNumber, $gender, $password, $address, $userType) {

        $this->nick = $nick;
        $this->emailAddress = $emailAddress;

        $this->firstname = $firstname;
        $this->lastname = $lastname;

        $this->phoneNumber = $phoneNumber;

        $this->password = md5($password); //MD5

        if ($gender == 'male') {
            $this->gender = 0;
        } else if ($gender == 'female') {
            $this->gender = 1;
        } else {
            $this->gender = -1;
        }

        $this->address = $address;
        $this->userType = $userType;
    }

    public function save() {
        return $this->user_model->insert(
                        array(
                            'usr_nick' => $this->nick,
                            'usr_email_address' => $this->emailAddress,
                            'usr_firstname' => $this->firstname,
                            'usr_lastname' => $this->lastname,
                            'usr_phone_number' => $this->phoneNumber,
                            'usr_gender' => $this->gender,
                            'usr_password' => $this->password,
                            'usr_address_id' => ( $this->address instanceof Address_model ? $this->address->getAddressId() : $this->address ),
                            'usr_user_type_id' => ( $this->userType instanceof User_type_model ? $this->userType->getUserTypeId() : $this->userType )
                ));
    }
    
    public function update_user_type( $user_id, $new_user_type_id) {
        return $this->user_model->update( $user_id , array('usr_user_type_id' => $new_user_type_id) );
    }

    public function get_by_email_or_nick_and_password($email_or_nick, $password) {

        $where = "(usr_email_address=" . $this->db->escape($email_or_nick) . " OR usr_nick=" . $this->db->escape($email_or_nick) . ") AND usr_password=" . $this->db->escape(md5($password)) . "";
        $this->db->where($where);
        $this->db->limit(1);

        $query = $this->db->get($this->_table);

        log_message('debug', print_r($this->db->last_query(), TRUE));

        if ($query->num_rows() > 0) {
            $row = $query->row();
            log_message('debug', print_r($row, TRUE));
            $result = new User_model();
            $result->instantiate($row->usr_nick, $row->usr_email_address, $row->usr_firstname, $row->usr_lastname, $row->usr_phone_number, $row->usr_gender, $row->usr_password, $row->usr_address_id, $row->usr_user_type_id);

            $result->setUserId($row->usr_id);

            return $result;
        } else {
            return NULL;
        }
    }

    public function is_present_by($column, $value) {
        $row = $this->user_model->as_object()->get_by($column, $value);

        if (count($row) <= 0) {
            return NULL;
        }
        
        $result = new User_model();
        $result->instantiate($row->usr_nick, $row->usr_email_address, $row->usr_firstname, $row->usr_lastname, $row->usr_phone_number, $row->usr_gender, $row->usr_password, $row->usr_address_id, $row->usr_user_type_id);

        $result->setUserId($row->usr_id);
        
        return $result;
    }

    public function get_user_by($column, $value) {
        $row = $this->user_model->as_object()->get_by($column, $value);

        return $row;
    }
    
    public function get_all_users(){
        $result = $this->user_model->get_all();
        
        $result_array = array();
        
        foreach ($result as $user_instance_std_obj) {
            $user_model_inst = new User_model();
            $user_model_inst->instantiate(
                    $user_instance_std_obj->usr_nick, $user_instance_std_obj->usr_email_address, $user_instance_std_obj->usr_firstname, $user_instance_std_obj->usr_lastname, $user_instance_std_obj->usr_phone_number,
                    $user_instance_std_obj->usr_gender, 'SECRET', $user_instance_std_obj->usr_address_id, $user_instance_std_obj->usr_user_type_id);
            $user_model_inst->setUserId($user_instance_std_obj->usr_id);
            $result_array[] = $user_model_inst;
        }
        
        return $result_array;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getNick() {
        return $this->nick;
    }

    public function getEmailAddress() {
        return $this->emailAddress;
    }

    public function getFirstName() {
        return $this->firstname;
    }

    public function getLastName() {
        return $this->lastname;
    }

    public function getGender() {
        return $this->gender;
    }

    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getUserType() {
        return $this->userType;
    }

    protected function setUserId($usrId) {
        $this->userId = $usrId;
    }

}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
