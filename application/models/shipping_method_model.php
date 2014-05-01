<?php

/**
 * Model class representing shipping method.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Shipping_method_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */    
    public $_table = 'sb_shipping_method';
    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */    
    public $primary_key = 'sm_id';

    /**
     *
     * @var int $id
     *  Payment method ID
     */
    private $id;
    /**
     *
     * @var string $name
     *  Payment method name
     */
    private $name;
    /**
     *
     * @var double $cost
     *  Payment method's cost(price)
     */
    private $cost;
    
    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */    
    public $protected_attributes = array('sm_id');

    
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
     *  Name of a payment method
     * @param double $price
     *  Cost for instantiated payment method
     */
    public function instantiate($name, $price) {

        $this->name=$name;
        $this->cost=$price;
    }

    /*     * * database operations ** */
    
    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */     
    public function save() {

        return $this->shipping_method_model->insert(
                array(
                    'sm_name' => $this->name,
                    'sm_cost'=> $this->cost
        ));
    }
    
    /**
     * Selects the shipping method from database according to it's ID
     * 
     * @param type $shippingMethodId
     *  ID of a shipping method to be selected
     * @return null|Shipping_method_model
     *  Either null if such a shipping method does not exist or shipping method object
     */
    public function get_shipping_method_by_id( $shippingMethodId ){
        
        $result = $this->shipping_method_model->get( $shippingMethodId );
        
        if ( !$result ) {
            return NULL;
        } else {
            $loaded_shipping_method = new Shipping_method_model();
            $loaded_shipping_method->instantiate($result->sm_name, $result->sm_cost);
            $loaded_shipping_method->setId($result->sm_id);

            return $loaded_shipping_method;
        }
    }     
    
    /**
     * Selects all shipping methods from database
     * @return null|Shipping_method_model
     *  NULL if there are no shipping methods or array of all shipping methods
     */
    public function get_all_shipping_methods(){
        
        $shipping_methods_raw = $this->shipping_method_model->as_object()->get_all();

        if ( !$shipping_methods_raw ){
            return NULL;
        }
        
        $shipping_methods_instances_array = array();
        
        foreach ( $shipping_methods_raw as $shipping_method_instance_raw ){
            $shipping_method_instance = new Shipping_method_model();
            $shipping_method_instance->instantiate( $shipping_method_instance_raw->sm_name, $shipping_method_instance_raw->sm_cost);
            $shipping_method_instance->setId($shipping_method_instance_raw->sm_id);
            $shipping_methods_instances_array[] = $shipping_method_instance;
        }
        
        return $shipping_methods_instances_array;
    }

    /*     * ********* setters *********** */
    /**
     * Sets ID
     * @param int $newId
     * New ID to be set
     */
    public function setId( $newId ){
        $this->id = $newId;
    }
    
     /** Sets name
     * @param string $newName
     * New name to be set
     */    
    public function setName($newName) {
        $this->name = $newName;
    }

     /** Sets cost
     * @param double $newCost
     * New cost to be set
     */     
    public function setCost($newCost) {
        $this->cost = $newCost;
    }

    /*     * ********* getters *********** */
    /**
     * Gets ID
     * @return int
     *  ID of a shipping method
     */
    public function getId(){
        return $this->id;
    }
    
    /**
     * Gets name
     * @return string
     *  Name of a shipping method
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Gets cost(price) of a shipping method
     * @return double
     *  Cost(price) of a shipping method
     */
    public function getCost() {
        return $this->cost;
    }
}

/* End of file shipping_method_model.php */
/* Location: ./application/models/shipping_method_model.php */
