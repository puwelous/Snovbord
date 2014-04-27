<?php

class Product_model extends MY_Model {

    const PRODUCT_STATUS_PROPOSED = 'PROPOSED';
    const PRODUCT_STATUS_DECLINED_UNSEEN = 'DECLINED_UNSEEN';
    const PRODUCT_STATUS_DECLINED_SEEN = 'DECLINED_SEEN';
    const PRODUCT_STATUS_ACCEPTED = 'ACCEPTED';

    public $_table = 'sb_product';
    public $primary_key = 'prdct_id';
    private $id;
    private $name;
    private $price;
    private $description;
    private $sex;
    private $acceptanceStatus;
    private $creator_id;
    private $basic_product_id;
    private $photo_url;
    public $protected_attributes = array('prdct_id');

    /* basic constructor */

    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    public function instantiate($name, $price, $description, $sex, $acceptanceStatus, $creator_id, $basic_product_id, $photo_url) {

        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->sex = $sex;
        $this->acceptanceStatus = $acceptanceStatus;
        $this->creator_id = $creator_id;
        $this->basic_product_id = $basic_product_id;
        $this->photo_url = $photo_url;
    }

    /*     * * database operations ** */

    /*
     * create
     */
//prdct_id, prdct_name, prdct_price, prdct_description, prdct_sex, prdct_acceptance_status, prdct_creator, 
    public function save() {
        return $this->product_model->insert(
                        array(
                            'prdct_name' => $this->name,
                            'prdct_price' => $this->price,
                            'prdct_description' => $this->description,
                            'prdct_sex' => $this->sex,
                            'prdct_acceptance_status' => $this->acceptanceStatus,
                            'prdct_creator' => ( $this->creator_id instanceof User_model ? $this->creator_id->getUserId() : $this->creator_id ),
                            'prdct_basic_product_id' => $this->basic_product_id,
                            'prdct_photo_url' => $this->photo_url
                ));
    }
    
    public function update_product() {
        return $this->product_model->update(
                        $this->getId(), array(
                            'prdct_name' => $this->name,
                            'prdct_price' => $this->price,
                            'prdct_description' => $this->description,
                            'prdct_sex' => $this->sex,
                            'prdct_acceptance_status' => $this->acceptanceStatus,
                            'prdct_creator' => ( $this->creator_id instanceof User_model ? $this->creator_id->getUserId() : $this->creator_id ),
                            'prdct_basic_product_id' => $this->basic_product_id,
                            'prdct_photo_url' => $this->photo_url
                ));
    }     

    public function get_product($productId) {
        $selected_product = $this->product_model->get($productId);

        if (!$selected_product) {
            return NULL;
        }

        $loaded_product = new Product_model();

        $loaded_product->instantiate(
                $selected_product->prdct_name, 
                $selected_product->prdct_price, 
                $selected_product->prdct_description, 
                $selected_product->prdct_sex, 
                $selected_product->prdct_acceptance_status, 
                $selected_product->prdct_creator, 
                $selected_product->prdct_basic_product_id,
                $selected_product->prdct_photo_url);

        $loaded_product->setId($selected_product->prdct_id);

        return $loaded_product;
    }

    public function get_any_single_product() {

        $query = $this->db->query('SELECT * FROM sb_product LIMIT 1;');

        if ($query->num_rows() <= 0) {
            return NULL;
        }

        $selected_product = $query->row();

        $loaded_product = new Product_model();

        $loaded_product->instantiate($selected_product->prdct_name, $selected_product->prdct_price, $selected_product->prdct_description, $selected_product->prdct_sex, $selected_product->prdct_acceptance_status, $selected_product->prdct_creator,  $selected_product->prdct_basic_product_id, $selected_product->prdct_photo_url);

        $loaded_product->setId($selected_product->prdct_id);

        return $loaded_product;
    }

    public function get_all_products() {
        $all_products = $this->product_model->get_all();

        if (!isset($all_products) || is_null($all_products)) {
            return NULL;
        } else {
            $products_instances_array = array();

            foreach ($all_products as $item) {
                $product_instance = new Product_model();
                $product_instance->instantiate($item->prdct_name, $item->prdct_price, $item->prdct_description, $item->prdct_sex, $item->prdct_acceptance_status, $item->prdct_creator,  $item->prdct_basic_product_id, $item->prdct_photo_url);
                $product_instance->setId($item->prdct_id);
                $products_instances_array[] = $product_instance;
            }

            return $products_instances_array;
        };
    }
    
    public function get_accepted_products() {
         return $this->get_products_by_status(Product_model::PRODUCT_STATUS_ACCEPTED);
    }    
    
    public function get_proposed_products() {
        return $this->get_products_by_status(Product_model::PRODUCT_STATUS_PROPOSED);
    }

    private function get_products_by_status( $status ){
        $all_products = $this->product_model->get_many_by('prdct_acceptance_status', $status);

        if (!isset($all_products) || is_null($all_products)) {
            return NULL;
        } else {
            $products_instances_array = array();

            foreach ($all_products as $item) {
                $product_instance = new Product_model();
                $product_instance->instantiate($item->prdct_name, $item->prdct_price, $item->prdct_description, $item->prdct_sex, $item->prdct_acceptance_status, $item->prdct_creator, $item->prdct_basic_product_id, $item->prdct_photo_url);
                $product_instance->setId($item->prdct_id);
                $products_instances_array[] = $product_instance;
            }

            return $products_instances_array;
        };
    }
    
    public function get_products_by_creator( $creator ) {
        
        $creatorId = ( $creator instanceof User_model ? $creator->getId() : $creator );
        
        $all_products = $this->product_model->get_many_by('prdct_creator', $creatorId);

        if (!isset($all_products) || is_null($all_products)) {
            return NULL;
        } else {
            $products_instances_array = array();

            foreach ($all_products as $item) {
                $product_instance = new Product_model();
                $product_instance->instantiate($item->prdct_name, $item->prdct_price, $item->prdct_description, $item->prdct_sex, $item->prdct_acceptance_status, $item->prdct_creator, $item->prdct_basic_product_id, $item->prdct_photo_url);
                $product_instance->setId($item->prdct_id);
                $products_instances_array[] = $product_instance;
            }

            return $products_instances_array;
        };
    }     
    /*     * ********* getters *********** */

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getSex() {
        return $this->sex;
    }

    public function getAcceptanceStatus() {
        return $this->acceptanceStatus;
    }

    public function getCreator() {
        return $this->creator_id;
    }
    
    public function getBasicProduct() {
        return $this->basic_product_id;
    }    
    
    public function getPhotoUrl(){
        return $this->photo_url;
    }

    /*     * ********* setters *********** */

    private function setId($newId) {
        $this->id = $newId;
    }

    public function setName($newName) {
        $this->name = $newName;
    }

    public function setPrice($newPrice) {
        $this->price = $newPrice;
    }

    public function setDescription($newDesc) {
        $this->description = $newDesc;
    }

    public function setSex($newSex) {
        $this->sex = $newSex;
    }

    public function setAcceptanceStatus($newAcceptanceStatus) {
        $this->acceptanceStatus = $newAcceptanceStatus;
    }

    public function setCreator($newCreator) {
        $this->creator_id = $newCreator;
    }

    public function setBasicProduct( $newBasicProduct){
        $this->basic_product_id = $newBasicProduct;
    }
    
    public function setPhotoUrl( $newPhotoUrl){
        $this->photo_url = $newPhotoUrl;
    }
}

/* End of file product_model.php */
/* Location: ./application/models/product_model.php */
