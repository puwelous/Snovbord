<?php

class Basic_product_raster_model extends MY_Model {

    protected $_table = 'sb_basic_prod_raster_representation';
    protected $primary_key = 'bs_prdct_rstr_rep_id';

    private $id;
    private $photoUrl;
    private $basicProduct;
    private $supportedPointOfView;

    public $protected_attributes = array('bs_prdct_rstr_rep_id');

    public function __construct() {
        parent::__construct();
    }

    public function instantiate(
    $photoUrl, $basicProduct, $supportedPointOfView) {

        $this->photoUrl = $photoUrl;
        $this->basicProduct = $basicProduct;
        $this->supportedPointOfView = $supportedPointOfView;
    }

    public function save() {
        return $this->basic_product_raster_model->insert(
                        array(
                            'bs_prdct_rstr_rep_photo_url' => $this->photoUrl,
                            'bs_prdct_rstr_rep_basic_product_id' => ( $this->basicProduct instanceof Basic_product_model ? $this->basicProduct->getId() : $this->basicProduct),
                            'bs_prdct_rstr_rep_basic_product_supported_point_of_view_id' => ( $this->supportedPointOfView instanceof Supported_point_of_view_model ? $this->supportedPointOfView->getId() : $this->supportedPointOfView)
                ));
    }
    
    public function getId(){
        return $this->id;
    }
    
//  
//    public function getUserId() {
//        return $this->userId;
//    }
//
//    public function getNick() {
//        return $this->nick;
//    }
//
//    public function getEmailAddress() {
//        return $this->emailAddress;
//    }
//
//    public function getFirstName() {
//        return $this->firstname;
//    }
//
//    public function getLastName() {
//        return $this->lastname;
//    }
//
//    public function getGender() {
//        return $this->gender;
//    }
//
//    public function getPhoneNumber() {
//        return $this->phoneNumber;
//    }
//
//    public function getAddress() {
//        return $this->address;
//    }
//
//    public function getUserType() {
//        return $this->userType;
//    }
//
//    protected function setUserId($usrId) {
//        $this->userId = $usrId;
//    }

}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
