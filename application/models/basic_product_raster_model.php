<?php

class Basic_product_raster_model extends MY_Model {

// 
    protected $_table = 'sb_basic_prod_raster_representation';
    protected $primary_key = 'bs_prdct_rstr_rep_id';
    private $id;
    private $photoUrl;
    private $basicProduct;
    private $pointOfView;
    public $protected_attributes = array('bs_prdct_rstr_rep_id');

    public function __construct() {
        parent::__construct();
    }

    public function instantiate(
    $photoUrl, $basicProduct, $pointOfView) {

        $this->photoUrl = $photoUrl;
        $this->basicProduct = $basicProduct;
        $this->pointOfView = $pointOfView;
    }

    public function save() {
        return $this->basic_product_raster_model->insert(
                        array(
                            'bs_prdct_rstr_rep_photo_url' => $this->photoUrl,
                            'bs_prdct_rstr_rep_basic_product_id' => ( $this->basicProduct instanceof Basic_product_model ? $this->basicProduct->getId() : $this->basicProduct),
                            'bs_prdct_rstr_rep_basic_product_point_of_view_id' => ( $this->pointOfView instanceof Point_of_view_model ? $this->pointOfView->getId() : $this->pointOfView)
                ));
    }

    public function get_single_basic_product_raster_by_product_id_and_pov_id($productId, $povId) {


        $productId = $this->db->escape($productId);
        $povId = $this->db->escape($povId);

        $query = $this->db->query('SELECT bprr.* FROM sb_basic_prod_raster_representation bprr WHERE bprr.bs_prdct_rstr_rep_basic_product_point_of_view_id = ' . $povId . ' AND bprr.bs_prdct_rstr_rep_basic_product_id = ( SELECT bp.bsc_prdct_id FROM sb_basic_product bp WHERE bp.bsc_prdct_product_id = ' . $productId . ') LIMIT 1;');

        if ($query->num_rows() <= 0) {
            return NULL;
        }

        $raw_basic_product_raster_item = $query->row(); 
         
        $basic_product_raster = new Basic_product_raster_model();
        $basic_product_raster->instantiate(
                $raw_basic_product_raster_item->bs_prdct_rstr_rep_photo_url, 
                $raw_basic_product_raster_item->bs_prdct_rstr_rep_basic_product_id, 
                $raw_basic_product_raster_item->bs_prdct_rstr_rep_basic_product_point_of_view_id
                );
        $basic_product_raster->setId($raw_basic_product_raster_item->bs_prdct_rstr_rep_id);
        
        return $basic_product_raster;
    }


    public function getId() {
        return $this->id;
    }
    
    public function getPhotoUrl(){
        return $this->photoUrl;
    }
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
