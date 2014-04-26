<?php

class Basic_product_vector_model extends MY_Model {

    protected $_table = 'sb_basic_prod_vector_representation';
    protected $primary_key = 'bsc_prdct_vctr_rprsnttn_id';

    private $id;
    private $svgDefinition;
    private $basicProduct;
    private $pointOfView;

    public $protected_attributes = array('bsc_prdct_vctr_rprsnttn_id');

    public function __construct() {
        parent::__construct();
    }

    public function instantiate(
    $svgDefinition, $basicProduct, $pointOfView) {

        $this->svgDefinition = $svgDefinition;
        $this->basicProduct = $basicProduct;
        $this->pointOfView = $pointOfView;
    }

    public function save() {
        return $this->basic_product_vector_model->insert(
                        array(
                            'bsc_prdct_vctr_rprsnttn_svg_definition' => $this->svgDefinition,
                            'bsc_prdct_vctr_rprsnttn_basic_product_id' => ( $this->basicProduct instanceof Basic_product_model ? $this->basicProduct->getId() : $this->basicProduct),
                            'bsc_prdct_vctr_rprsnttn_point_of_view_id' => ( $this->pointOfView instanceof Point_of_view_model ? $this->pointOfView->getId() : $this->pointOfView)
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
    protected function setId($newId) {
        $this->id = $newId;
    }

}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
