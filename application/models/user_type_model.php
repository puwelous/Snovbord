<?php

/**
 * Model class representing type of a user.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class User_type_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */

    protected $_table = 'sb_user_type';

    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */
    protected $primary_key = 'usrtp_id';

    /**
     *
     * @var int $userTypeId
     * ID of a user type
     */
    private $userTypeId;

    /**
     *
     * @var string $userTypeName
     *  User type name
     */
    private $userTypeName;

    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */
    public $protected_attributes = array('usrtp_id');

    /**
     * Basic constructor calling parent CRUD abstraction layer contructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Constructor-like method for instantiating object of the class.
     * 
     * @param string $userTypeName
     *  Name of a new user type
     */
    public function instantiate(
    $userTypeName) {
        $this->userTypeName = $userTypeName;
    }

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */
    public function save() {
        return $this->user_type_model->insert(
                        array(
                            'usrtp_name' => $this->userTypeName
                ));
    }


    /**
     * Selects a user type from database according to passed ID
     * @param int $userTypeId
     *  ID of a type of user
     * @return null|\User_type_model
     *  NULL if such a user type does not exist or single user type instance
     */
    public function get_user_type_by_id($userTypeId) {
        $row = $this->user_type_model->as_object()->get($userTypeId);
        if (!$row) {
            return NULL;
        } else {
            $result = new User_type_model();
            $result->instantiate($row->usrtp_name);
            $result->setUserTypeId($row->usrtp_id);

            return $result;
        }
    }

    /**
     * Selects a user type from database according to passed name
     * 
     * @param string $value
     *  Name of a user type
     * @return null|\User_type_model
     *  NULL if such a user type does not exist or single user type instance
     */
    public function get_by_user_type_name($value) {
        $row = $this->user_type_model->as_object()->get_by('usrtp_name', $value);
        if (!$row) {
            return NULL;
        } else {
            $result = new User_type_model();
            $result->instantiate($row->usrtp_name);
            $result->setUserTypeId($row->usrtp_id);

            return $result;
        }
    }

    /** setters **/
    /**
     * Setter for ID
     * @param int $id
     *  User type ID
     */
    public function setUserTypeId($id) {
        $this->userTypeId = $id;
    }

    /**
     * Getter for ID
     * @return int
     *  User type ID
     */
    public function getUserTypeId() {
        return $this->userTypeId;
    }

    /**
     * Getter for name
     * @return string
     *  User type name
     */
    public function getUserTypeName() {
        return $this->userTypeName;
    }

}

/* End of file user_type_model.php */
/* Location: ./application/models/user_type_model.php */
