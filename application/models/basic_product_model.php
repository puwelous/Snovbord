<?php

/**
 * Model class representing basic product.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Basic_product_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */    
    public $_table = 'sb_basic_product';
    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */    
    public $primary_key = 'bsc_prdct_id';
    
    /**
     *
     * @var int $id
     *  ID of basic product
     */
    private $id;
    /**
     *
     * @var double $price
     *  Price of basic product
     */
    private $price;
    
    /**
     *
     * @var int $creator
     *  Creator of basic product 
     */
    private $creator;
    
    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */    
    public $protected_attributes = array('bsc_prdct_id');

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
 * @param double $price
 *  Price of basic product
 * @param int $creator
 *  Creator of basic product
 */
    public function instantiate($price, $creator) {
        $this->price = $price;
        $this->creator = $creator;
    }


    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */     
    public function save() {
        return $this->basic_product_model->insert(
                        array(
                            'bsc_prdct_price' => $this->price,
                            'bsc_prdct_creator_id' => ( $this->creator instanceof User_model ? $this->creator->getUserId() : $this->creator )
                ));
    }

//
//    //deprecated
//    public function get_by_product_id($productId) {
//        $result = $this->basic_product_model->get_by('bsc_prdct_product_id', $productId);
//        if (!$result) {
//            return NULL;
//        } else {
//            $loaded_basic_product_model = new Basic_product_model();
//            $loaded_basic_product_model->instantiate(
//                    $result->bsc_prdct_price, $result->bsc_prdct_product_id, $result->bsc_prdct_creator_id
//            );
//            $loaded_basic_product_model->setId($result->bsc_prdct_id);
//            return $loaded_basic_product_model;
//        }
//    }

    /*     * ********* setters *********** */

    /**
     * Setter for new ID
     * @param int $newId
     *  New ID to be set
     */
    public function setId($newId) {
        $this->id = $newId;
    }

    /**
     * Setter for new price
     * @param double $newPrice
     *  New price to be set
     */
    public function setPrice($newPrice) {
        $this->price = $newPrice;
    }
    /**
     * Setter for new creator
     * @param int $newCreator
     *  New creator model to be set
     */
    public function setCreator($newCreator) {
        $this->creator = $newCreator;
    }

    /*     * ********* getters *********** */

    /**
     * Getter for ID
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Getter for price
     * @return double
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * Getter for creator ID
     * @return int
     */
    public function getCreator() {
        return $this->creator;
    }

}

/* End of file basic_product_model.php */
/* Location: ./application/models/basic_product_model.php */
