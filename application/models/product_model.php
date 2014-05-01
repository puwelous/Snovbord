<?php

/**
 * Model class representing product.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Product_model extends MY_Model {

    /**
     * Proposed - product status
     */
    const PRODUCT_STATUS_PROPOSED = 'PROPOSED';
    /**
     * Declined unseen - product status
     */    
    const PRODUCT_STATUS_DECLINED_UNSEEN = 'DECLINED_UNSEEN';
    /**
     * Declined seen - product status
     */    
    const PRODUCT_STATUS_DECLINED_SEEN = 'DECLINED_SEEN';
    /**
     * Accepted - product status
     */    
    const PRODUCT_STATUS_ACCEPTED = 'ACCEPTED';

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */    
    public $_table = 'sb_product';
    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */    
    public $primary_key = 'prdct_id';
    
    /**
     *
     * @var int $id
     *  ID of product
     */
    private $id;
    /**
     *
     * @var string $name
     *  Name of product
     */
    private $name;
    /**
     *
     * @var double $price
     *  Price of product
     */
    private $price;
    /**
     *
     * @var string $description
     *  Description of product
     */
    private $description;
    /**
     *
     * @var string $sex 
     * Sex of product's target
     */
    private $sex;
    /**
     *
     * @var string $acceptanceStatus
     *  Acceptance status of prodcut
     */
    private $acceptanceStatus;
    /**
     *
     * @var int $creator_id
     * ID of creator
     */
    private $creator_id;
    /**
     *
     * @var int $basic_product_id
     *  ID of basic product
     */
    private $basic_product_id;
    /**
     *
     * @var string $photo_url
     *  URL to products photo
     */
    private $photo_url;
    
    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */    
    public $protected_attributes = array('prdct_id');

    /**
     * Basic constructor calling parent CRUD abstraction layer contructor
     */
    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    /**
     * Constructor-like method for instantiating object of the class.
     * 
     * @param string $name
     *  Name of product
     * @param double $price
     *  Price of product
     * @param string $description
     *  Description of product
     * @param string $sex
     *  Sex of product's target
     * @param string $acceptanceStatus
     *  Acceptance status of product
     * @param int $creator_id
     *  Creator of product
     * @param int $basic_product_id
     *  ID for referencing basic product
     * @param string $photo_url
     *  Photo URL
     */
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

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */ 
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
    
    /**
     * Updates this object and propagates to a database. Database update operation
     * @return object
     *  NULL or object as a result of update (ID)
     */
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

    /**
     * Selects product from database according to passed ID
     * @param int $productId
     *  ID of selected product
     * @return null|Product_model
     *  Either NULL or single product model instance
     */
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

    /**
     * Selects any single product
     * @return null|Product_model
     *  Returns NULL if no product exists or single product model
     */
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

    /**
     * Selects all products from database
     * @return null|Product_model
     * Either null if there are no products in database or array of all product models
     */
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
    
    /**
     * Selects only accepted products
     * @return null|Product_model
     * Either null if there are no accepted products in database or array of all accepted product models
     */
    public function get_accepted_products() {
         return $this->get_products_by_status(Product_model::PRODUCT_STATUS_ACCEPTED);
    }    
    /**
     * Selects only proposed products
     * @return null|Product_model
     * Either null if there are no proposed products in database or array of all proposed product models
     */
    public function get_proposed_products() {
        return $this->get_products_by_status(Product_model::PRODUCT_STATUS_PROPOSED);
    }

    /**
     * Selects products by specified status
     * @param string $status
     *  Acceptance status of products to be selected
     * @return null|Product_model
     *  Either null if there are no specified products in database or array of all specified product models
     */
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
    
    /**
     * Selects all products from database according to passed creator argument
     * @param User_model $creator
     *  Creator of producct
     * @return null|Product_model
     *  Either null if there are no user's product or array of all products that belong to specified user
     */
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

    /**
     * Getter for product ID
     * @return int
     *  ID of product
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Getter for product name
     * @return string
     *  Name of a product
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Getter for product price
     * @return double
     *  Price of a product
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * Getter for product description
     * @return string
     *  Description of a product
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Getter for product's target sex
     * @return string
     *  Sex of product's target
     */
    public function getSex() {
        return $this->sex;
    }

    /**
     * Getter for acceptance status
     * @return string
     *  Product's acceptance status
     */
    public function getAcceptanceStatus() {
        return $this->acceptanceStatus;
    }

    /**
     * Getter for product's creator
     * @return int
     *  ID of product's creator
     */
    public function getCreator() {
        return $this->creator_id;
    }
    
    /**
     * Getter for product's basic representation
     * @return int
     *  ID of basic product
     */
    public function getBasicProduct() {
        return $this->basic_product_id;
    }    
    
    /**
     * Getter for product's photo url
     * @return string
     *  URL to product photo
     */
    public function getPhotoUrl(){
        return $this->photo_url;
    }

    /*     * ********* setters *********** */

    /**
     * Setter for product ID
     * @param int $newId
     *  New ID to be set
     */
    private function setId($newId) {
        $this->id = $newId;
    }

    /**
     * Setter for product name
     * @param string $newName
     *  New product name
     */
    public function setName($newName) {
        $this->name = $newName;
    }

    /**
     * Setter for new product price
     * @param double $newPrice
     *  New price to be set
     */
    public function setPrice($newPrice) {
        $this->price = $newPrice;
    }

    /**
     * Setter for product description
     * @param string $newDesc
     *  New description to be set
     */
    public function setDescription($newDesc) {
        $this->description = $newDesc;
    }

    /**
     * Setter for product target's sex
     * @param string $newSex
     *  New prodcut target's sex
     */
    public function setSex($newSex) {
        $this->sex = $newSex;
    }

    /**
     * Setter for new acceptance status
     * @param string $newAcceptanceStatus
     * New acceptance status to be set
     */
    public function setAcceptanceStatus($newAcceptanceStatus) {
        $this->acceptanceStatus = $newAcceptanceStatus;
    }

    /**
     * Setter for new creator of product
     * @param int $newCreator
     *  New creator of product
     */
    public function setCreator($newCreator) {
        $this->creator_id = $newCreator;
    }

    /**
     * Setter for new basic product model
     * @param int $newBasicProduct
     *  New referenced basic product
     */
    public function setBasicProduct( $newBasicProduct){
        $this->basic_product_id = $newBasicProduct;
    }
    
    /**
     * Setter for new URL to photo
     * @param string $newPhotoUrl
     *  Photo URL to be set
     */
    public function setPhotoUrl( $newPhotoUrl){
        $this->photo_url = $newPhotoUrl;
    }
}

/* End of file product_model.php */
/* Location: ./application/models/product_model.php */
