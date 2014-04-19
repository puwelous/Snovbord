<?php

class User_type_model extends MY_Model {//, 

    protected $_table = 'sb_user_type';
    protected $primary_key = 'usrtp_id';
    
    private $userTypeId;
    private $userTypeName;

    public $protected_attributes = array( 'usrtp_id' );
    
    public function __construct() {
        parent::__construct();
    }

    public function instantiate(
             $userTypeName) {
        $this->userTypeName = $userTypeName;
    }

    public function setUserTypeId( $id ){
        $this->userTypeId = $id;
    }
    
    public function getUserTypeId( ){
        return $this->userTypeId;
    }    
    
    public function save() {
        return $this->user_type_model->insert(
                array(
                    'usrtp_name' => $this->userTypeName
        ));
    }
    
    public function get_by_user_type_name( $value ){
        $row = $this->user_type_model->as_object()->get_by( 'usrtp_name', $value );
        
        $result = new User_type_model();
        $result->instantiate( $row->usrtp_name );
        $result->setUserTypeId( $row->usrtp_id );
        
        return $result;
    }
}

/* End of file user_type_model.php */
/* Location: ./application/models/user_type_model.php */
