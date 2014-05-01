<?php

/**
 * Model class representing possible size of a product.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Possible_size_for_product_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */
    public $_table = 'sb_possible_size_for_product';

    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */
    public $primary_key = 'psfp_id';

    /**
     *
     * @var int $id
     *  Possible size for product ID
     */
    private $id;

    /**
     *
     * @var string $name
     *  Possible size for product name
     */
    private $name;

    /**
     *
     * @var int $count
     *  Possible size for product amount 
     */
    private $count;

    /**
     *
     * @var int $product
     *  Referenced product by this int ID attribute
     */
    private $product;

    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */
    public $protected_attributes = array('psfp_id');

    /**
     * Basic constructor calling parent CRUD abstraction layer contructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Constructor-like method for instantiating object of the class.
     * 
     * @param string $name
     *  Name of a possible size for product
     * @param int $count
     *  Count of possible size for product
     * @param int $product
     *  Referenced product ID
     */
    public function instantiate($name, $count, $product) {

        $this->name = $name;
        $this->count = $count;
        $this->product = $product;
    }

    /*     * * database operations ** */

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */
    public function save() {

        return $this->possible_size_for_product_model->insert(
                        array(
                            'psfp_name' => $this->name,
                            'psfp_count' => $this->count,
                            'psfp_product_id' => $this->product
                ));
    }

    /**
     * Selects single possible size for product from database by it's ID
     * 
     * @param int $psfpId
     *  ID of possible size for product
     * @return null|Possible_size_for_product_model
     *  Either null if such a possible size for product does not exist or single possible size for product object
     */
    public function get_possible_size_for_product_by_id($psfpId) {
        $result = $this->possible_size_for_product_model->get($psfpId);

        if (!$result) {
            return NULL;
        } else {
            $loaded_psfp = new Possible_size_for_product_model();
            $loaded_psfp->instantiate(
                    $result->psfp_name, $result->psfp_count, $result->psfp_product_id);
            $loaded_psfp->setId($result->psfp_id);

            return $loaded_psfp;
        }
    }

    /**
     * Selects all possible sizes for product from database by product's ID
     * 
     * @param int $product
     *  ID of product
     * @return null|Possible_size_for_product_model
     *  Either null if such a possible size for product does not exist or single possible size for product object
     */
    public function get_all_possible_sizes_by_product($product) {

        $possible_sizes_for_product = array();

        $result_raw = $this->possible_size_for_product_model->as_object()->get_many_by('psfp_product_id', $product);

        if (!$result_raw) {
            return NULL;
        } else {
            foreach ($result_raw as $psfp_raw_instance) {
                $psfp_instance = new Possible_size_for_product_model();
                $psfp_instance->instantiate($psfp_raw_instance->psfp_name, $psfp_raw_instance->psfp_count, $psfp_raw_instance->psfp_product_id);
                $psfp_instance->setId($psfp_raw_instance->psfp_id);

                $possible_sizes_for_product[] = $psfp_instance;
            }

            return $possible_sizes_for_product;
        }
    }

//    public function insert_possible_size_for_product() {
//
//        $id_of_psfp = $this->possible_size_for_product_model->insert(
//                array(
//                    'pd_id' => $this->product_definition,
//                    'psfp_name' => $this->name,
//                    'psfp_amount' => $this->amount
//                ));
//
//        return $id_of_psfp;
//    }
//
//    public function get_possible_size_for_product_by($column, $value) {
//        $row = $this->possible_size_for_product_model->as_object()->get_by($column, $value);
//
//        return $row;
//    }
//
//    public function get_all_possible_sizes_for_product_by($column, $value, $asObject = TRUE) {
//        if ($asObject) {
//            $row = $this->possible_size_for_product_model->as_object()->get_many_by($column, $value);
//        } else {
//            $row = $this->possible_size_for_product_model->as_array()->get_many_by($column, $value);
//        }
//
//        return $row;
//    }

    /*     * ********* setters *********** */

    /**
     * Setter for possible size ID
     * @param int $newId
     *  New ID to be set
     */
    public function setId($newId) {
        $this->id = $newId;
    }

    /**
     * Setter for possible size product
     * @param int $newProductDefinition
     *  New product to be set
     */    
    public function setProductDefinition($newProductDefinition) {
        $this->product_definition = $newProductDefinition;
    }

    /**
     * Setter for possible size name
     * @param string $newName
     *  New name to be set
     */     
    public function setName($newName) {
        $this->name = $newName;
    }

    /**
     * Setter for possible size amount
     * @param int $newAmount
     *  New amount to be set
     */
    public function setAmount($newAmount) {
        $this->amount = $newAmount;
    }

    /*     * ********* getters *********** */

    /**
     * Getter for possible size ID
     * @return int
     *  ID of possible size for product
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Getter for possible size product's referenced product
     * @return int
     *  ID of possible size for product's referenced product
     */    
    public function getProductDefinition() {
        return $this->product_definition;
    }

    /**
     * Getter for possible size name
     * @return string
     *  Name of possible size for product
     */    
    public function getName() {
        return $this->name;
    }

    /**
     * Getter for possible size amount
     * @return int
     *  Amount of possible size for product
     */     
    public function getAmount() {
        return $this->amount;
    }

}

/* End of file possible_size_for_product_model.php */
/* Location: ./application/models/possible_size_for_product_model.php */
