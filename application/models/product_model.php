<?php

class Product_model extends MY_Model {

    public $_table = 'sb_product';
    public $primary_key = 'prdct_id';
    private $id;
    private $name;
    private $price;
    private $description;
    private $sex;
    private $creator_id;
    public $protected_attributes = array('prdct_id');

    /* basic constructor */

    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    public function instantiate($name, $price, $description, $sex, $creator_id) {

        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->sex = $sex;
        $this->creator_id = $creator_id;
    }

    /*     * * database operations ** */

    /*
     * create
     */

    public function save() {
        return $this->product_model->insert(
                        array(
                            'prdct_name' => $this->name,
                            'prdct_price' => $this->price,
                            'prdct_description' => $this->description,
                            'prdct_sex' => $this->sex,
                            'prdct_creator' => ( $this->creator_id instanceof User_model ? $this->creator_id->getUserId() : $this->creator_id )
                ));
    }
    
    public function get_product( $productId ){
        $selected_product = $this->product_model->get( $productId );
        #{ ["prdct_id"]=> string(2) "10" ["prdct_name"]=> string(18) "Snovbord Hoodie I." ["prdct_price"]=> string(6) "123.45" ["prdct_description"]=> string(62) "Unbelievably water-proof hoodie made of high-quality material." ["prdct_sex"]=> string(6) "female" ["prdct_creator"]=> string(1) "1" }
        
        if ( !$selected_product ){
            return NULL;
        }
        
        $loaded_product = new Product_model();
        
        $loaded_product->instantiate($selected_product->prdct_name,
                $selected_product->prdct_price, $selected_product->prdct_description,
                $selected_product->prdct_sex, $selected_product->prdct_creator);
        
        $loaded_product->setId( $selected_product->prdct_id );
        
       return  $loaded_product;
    }

    public function get_all_products() {
        $all_products = $this->product_model->get_all();

        if (!isset($all_products) || is_null($all_products)) {
            return NULL;
        } else {
            $products_instances_array = array();

            foreach ($all_products as $item) {
                $product_instance = new Product_model();
                $product_instance->instantiate($item->prdct_name, $item->prdct_price, $item->prdct_description, $item->prdct_sex, $item->prdct_creator);
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

    public function getCreator() {
        return $this->creator_id;
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

    public function setCreator($newCreator) {
        $this->creator_id = $newCreator;
    }

}

/* End of file product_model.php */
/* Location: ./application/models/product_model.php */
