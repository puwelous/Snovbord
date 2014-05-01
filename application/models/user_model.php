<?php

/**
 * Model class representing user.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class User_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */
    protected $_table = 'sb_user';

    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */
    protected $primary_key = 'usr_id';

    /**
     *
     * @var int $userId
     *  ID of a user
     */
    private $userId;

    /**
     *
     * @var string $nick
     *  User's nick
     */
    private $nick;

    /**
     *
     * @var string $emailAddress
     *  User's email address
     */
    private $emailAddress;

    /**
     *
     * @var string $firstname
     *  User's first name
     */
    private $firstname;

    /**
     *
     * @var string $lastname
     *  User's last name
     */
    private $lastname;

    /**
     *
     * @var string $phoneNumber
     *  User's phone number
     */
    private $phoneNumber;

    /**
     *
     * @var int $gender
     *  User's gender. Either 0 for Male or 1 for Female
     */
    private $gender;

    /**
     *
     * @var string $password
     *  User's password
     */
    private $password;

    /**
     *
     * @var int $address
     *  User's address ID
     */
    private $address;

    /**
     *
     * @var int $userType
     *  Type of a user
     */
    private $userType;

    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */
    public $protected_attributes = array('usr_id');

    /**
     * Basic constructor calling parent CRUD abstraction layer contructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Constructor-like method for instantiating object of the class.
     * 
     * @param string $nick
     *  User's nick
     * @param string $emailAddress
     *  User's email
     * @param string $firstname
     *  User's first name
     * @param string $lastname
     *  User's last name
     * @param string $phoneNumber
     *  User's phone number
     * @param int $gender
     *  User's sex
     * @param string $password
     *  User's password
     * @param int $address
     *  User's address ID
     * @param int $userType
     *  User's type ID
     */
    public function instantiate(
    $nick, $emailAddress, $firstname, $lastname, $phoneNumber, $gender, $password, $address, $userType) {

        $this->nick = $nick;
        $this->emailAddress = $emailAddress;

        $this->firstname = $firstname;
        $this->lastname = $lastname;

        $this->phoneNumber = $phoneNumber;

        $this->password = md5($password); //MD5
//        if ($gender == 'male') {
//            $this->gender = 0;
//        } else if ($gender == 'female') {
//            $this->gender = 1;
//        } else {
//            $this->gender = -1;
//        }
        $this->gender = $gender;

        $this->address = $address;
        $this->userType = $userType;
    }

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */
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
                            'usr_address_id' => ( $this->address instanceof Address_model ? $this->address->getId() : $this->address ),
                            'usr_user_type_id' => ( $this->userType instanceof User_type_model ? $this->userType->getUserTypeId() : $this->userType )
                ));
    }

    /**
     * Updates this object and propagates to a database. Database update operation
     * @return object
     *  NULL or object as a result of update (ID)
     */
    public function update_user() {
        return $this->user_model->update(
                        $this->getId(), array(
                    'usr_nick' => $this->nick,
                    'usr_email_address' => $this->emailAddress,
                    'usr_firstname' => $this->firstname,
                    'usr_lastname' => $this->lastname,
                    'usr_phone_number' => $this->phoneNumber,
                    'usr_gender' => $this->gender,
                    'usr_password' => $this->password,
                    'usr_address_id' => ( $this->address instanceof Address_model ? $this->address->getId() : $this->address ),
                    'usr_user_type_id' => ( $this->userType instanceof User_type_model ? $this->userType->getUserTypeId() : $this->userType )
                ));
    }

    /**
     * Updates the type of user
     * @param int $user_id
     *  ID of user whose type should be updated
     * @param int $new_user_type_id
     *  ID of user type to be updated to
     * @return int
     *  Returns ID of updated user as a update operation result
     */
    public function update_user_type($user_id, $new_user_type_id) {
        return $this->user_model->update($user_id, array('usr_user_type_id' => $new_user_type_id));
    }

    /**
     * Selects single user from database according to his/her ID
     * @param int $userId
     *  ID of user
     * @return null|User_model
     *  Either NULL if such a user does not exist or single user object
     */
    public function get_user_by_id($userId) {

        $result = $this->user_model->get($userId);

        if (!$result) {
            return NULL;
        } else {
            $loaded_user = new User_model();
            $loaded_user->instantiate($result->usr_nick, $result->usr_email_address, $result->usr_firstname, $result->usr_lastname, $result->usr_phone_number, $result->usr_gender, $result->usr_password, $result->usr_address_id, $result->usr_user_type_id);

            $loaded_user->setId($result->usr_id);

            return $loaded_user;
        }
    }

    /**
     * Selects single user from database by (email or nick) and his/her password
     * @param string $email_or_nick
     *  Either email or nick
     * @param type $password
     *  User's password
     * @return User_model|null
     *  Either selected(found) user or NULL if such a user does not exist
     */
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

            $result->setId($row->usr_id);

            return $result;
        } else {
            return NULL;
        }
    }

    /**
     * Selects user according to passed nick
     * @param string $nick
     *  User's nick
     * @return null|User_model
     *  Either null if such a user does not exist or single user object
     */
    public function get_user_by_nick($nick) {
        $result = $this->user_model->get_by('usr_nick', $nick);

        if (!$result) {
            return NULL;
        } else {
            $loaded_user = new User_model();
            $loaded_user->instantiate($result->usr_nick, $result->usr_email_address, $result->usr_firstname, $result->usr_lastname, $result->usr_phone_number, $result->usr_gender, $result->usr_password, $result->usr_address_id, $result->usr_user_type_id);

            $loaded_user->setId($result->usr_id);

            return $loaded_user;
        }
    }

    /**
     * Selects user by specified column and concrete value
     * @param string $column
     *  Column name to choose user by
     * @param string $value
     *  Value of queried column
     * @return null|User_model
     *  Either null if such a user does not exist or single user object
     */
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

    /**
     * Selects all users from database.
     * 
     * @return null|User_model
     *  Either NULL if there are no users in database or array of user model objects
     */
    public function get_all_users() {
        $result = $this->user_model->get_all();
        if (!result) {
            return NULL;
        }

        $result_array = array();

        foreach ($result as $user_instance_std_obj) {
            $user_model_inst = new User_model();
            $user_model_inst->instantiate(
                    $user_instance_std_obj->usr_nick, $user_instance_std_obj->usr_email_address, $user_instance_std_obj->usr_firstname, $user_instance_std_obj->usr_lastname, $user_instance_std_obj->usr_phone_number, $user_instance_std_obj->usr_gender, 'SECRET', $user_instance_std_obj->usr_address_id, $user_instance_std_obj->usr_user_type_id);
            $user_model_inst->setUserId($user_instance_std_obj->usr_id);
            $result_array[] = $user_model_inst;
        }

        return $result_array;
    }

    /* getters */

    /**
     * Getter for user ID
     * @return int
     *  ID of user
     */
    public function getId() {
        return $this->userId;
    }

    /**
     * Getter of user ID
     * @return int
     * ID of user
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * Getter for user nick
     * @return string
     * Nick of user
     */
    public function getNick() {
        return $this->nick;
    }

    /**
     * Getter for user email address
     * @return string
     *  User's email address
     */
    public function getEmailAddress() {
        return $this->emailAddress;
    }

    /**
     * Getter for user's firstname
     * @return string
     *  User's first name
     */
    public function getFirstName() {
        return $this->firstname;
    }

    /**
     * Getter for user's last name
     * @return string
     *  User's last name
     */
    public function getLastName() {
        return $this->lastname;
    }

    /**
     * Getter for user's gender
     * @return int
     *  Gender of a user
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Getter for user's phone number
     * @return string
     *  User's phone number
     */
    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    /**
     * Getter for user's addres
     * @return int
     *  ID of user's address model
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Getter for user's password
     * @return string
     *  Password of user
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Getter for user's type
     * @return string
     *  Type of user.
     */
    public function getUserType() {
        return $this->userType;
    }

    /* setters */

    /**
     * Setter for new ID
     * @param int $usrId
     *  New ID to be set
     */
    protected function setUserId($usrId) {
        $this->userId = $usrId;
    }

    /**
     * Setter for new ID
     * @param int $newId
     *  New ID to be set
     */
    protected function setId($newId) {
        $this->userId = $newId;
    }

    /**
     * Setter for new nick
     * @param string $newNick
     *  New nick to be set
     */
    public function setNick($newNick) {
        $this->nick = $newNick;
    }

    /**
     * Setter for new email address
     * @param string $newEmail
     *  New email address to be set
     */
    public function setEmailAddress($newEmail) {
        $this->emailAddress = $newEmail;
    }

    /**
     * Setter for new first name
     * @param string $newFirstname
     *  New first name to be set
     */
    public function setFirstName($newFirstname) {
        $this->firstname = $newFirstname;
    }

    /**
     * Setter for new last name
     * @param string $newLastname
     *  New last name to be set
     */
    public function setLastName($newLastname) {
        $this->lastname = $newLastname;
    }

    /**
     * Setter for new gender
     * @param int $newGender
     *  New gender to be set
     */
    public function setGender($newGender) {
        $this->gender = $newGender;
    }

    /**
     * Setter for new phone number
     * @param string $newPhoneNumber
     *  New phone number to be set
     */
    public function setPhoneNumber($newPhoneNumber) {
        $this->phoneNumber = $newPhoneNumber;
    }

    /**
     * Setter for new address model
     * @param int $newAddress
     *  New address model ID to be set
     */
    public function setAddress($newAddress) {
        $this->address = $newAddress;
    }

    /**
     * Setter for new password
     * @param string $newPassword
     *  New password to be set
     */
    public function setPassword($newPassword) {
        $this->password = md5($newPassword);
    }

    /**
     * Setter for new user type model
     * @param int $newUserType
     *  New user type model ID
     */
    public function setUserType($newUserType) {
        $this->userType = $newUserType;
    }

}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
