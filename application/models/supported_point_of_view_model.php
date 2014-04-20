<?php

class Supported_point_of_view_model extends MY_Model {

    protected $_table = 'sb_supported_point_of_view';
    protected $primary_key = 'spprtd_pov_id';
    private $id;
    private $name;
    private $product;
    public $protected_attributes = array('spprtd_pov_id');

    public function __construct() {
        parent::__construct();
    }

    public function instantiate(
    $name, $product) {

        $this->name = $name;
        $this->product = $product;
    }

    public function save() {
        return $this->supported_point_of_view_model->insert(
                        array(
                            'spprtd_pov_name' => $this->name,
                            'spprtd_pov_product_id' => ( $this->product instanceof Product_model ? $this->product->getId() : $this->product)
                ));
    }

    public function get_pov_names_distinct() {

        $this->db->select('spprtd_pov_name');
        $this->db->distinct();

        $query = $this->db->get($this->_table);

        $result = array();

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $stdObj) {
                $result[] = $stdObj->spprtd_pov_name;
            }
            //var_dump($result);
            return $result;
        } else {
            return NULL;
        }
    }

    public function get_by_product($product) {

        $product_id = ( $product instanceof Product_model ? $product->getId() : $product );
        $povs_by_product = $this->supported_point_of_view_model->get_many_by('spprtd_pov_product_id', $product_id);

        if (!isset($povs_by_product) || is_null($povs_by_product)) {
            return NULL;
        } else {
            $sup_pov_instances_array = array();

            foreach ($povs_by_product as $item) {
                $sup_pov_instance = new Supported_point_of_view_model();
                $sup_pov_instance->instantiate($item->spprtd_pov_name, $item->spprtd_pov_product_id);
                $sup_pov_instance->setId($item->spprtd_pov_id);
                $sup_pov_instances_array[] = $sup_pov_instance;
            }

            return $sup_pov_instances_array;
        };
    }

    public function get_rasters_urls_by_pov($pov, $url_column_alias = NULL) {

        $pov_id = ( $pov instanceof Supported_point_of_view_model ? $pov->getId() : $pov );

//SELECT bprr.bs_prdct_rstr_rep_photo_url as 'photo_url' FROM sb_basic_prod_raster_representation bprr WHERE bprr.bs_prdct_rstr_rep_basic_product_supported_point_of_view_id = 3
//UNION
//SE FROM sb_component_raster_representation crr WHERE crr.cmpnnt_rstr_rprsnttn_supported_point_of_view_id = 3

        $url_column_alias = ( $url_column_alias != NULL ? mysql_escape_string($url_column_alias) : 'photo_url' );
        $pov = ( $pov instanceof Supported_point_of_view_model ? $pov->getId() : $pov );

        $select_from_basic = 'SELECT bprr.bs_prdct_rstr_rep_photo_url as ' . $url_column_alias . ' FROM sb_basic_prod_raster_representation bprr WHERE bprr.bs_prdct_rstr_rep_basic_product_supported_point_of_view_id =' . $pov_id . ' ';
        $select_from_comp = 'SELECT crr.cmpnnt_rstr_rprsnttn_photo_url as ' . $url_column_alias . ' FROM sb_component_raster_representation crr WHERE crr.cmpnnt_rstr_rprsnttn_supported_point_of_view_id =' . $pov_id . ' ';

        $query = $this->db->query($select_from_basic . ' UNION ' . $select_from_comp . ';');

        //$query = $this->db->get();

        if ($query->num_rows() <= 0) {
            return NULL;
        }

        return $query->result();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($newId) {
        $this->id = $newId;
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
