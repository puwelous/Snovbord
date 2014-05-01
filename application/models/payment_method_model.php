<?php

/**
 * Model class representing payment method.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Payment_method_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */    
    public $_table = 'sb_payment_method';
    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */    
    public $primary_key = 'pm_id';
    
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
    public $protected_attributes = array('pm_id');

    
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
     * @param double $cost
     *  Cost for instantiated payment method
     */
    public function instantiate($name, $cost) {

        $this->name = $name;
        $this->cost = $cost;
    }


    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */     
    public function save() {

        $this->payment_method_model->insert(
                array(
                    'pm_name' => $this->name,
                    'pm_cost' => $this->cost
        ));
    }
    
    /**
     * Selects the payment method from database according to it's ID
     * 
     * @param int $paymentMethodId
     *  ID of a payment method to be selected
     * @return null|Payment_method_model
     *  Either null if such a payment method does not exist or payment method object
     */
    public function get_payment_method_by_id( $paymentMethodId ){
        
        $result = $this->payment_method_model->get( $paymentMethodId );
        
        if ( !$result ) {
            return NULL;
        } else {
            $loaded_payment_method = new Payment_method_model();
            $loaded_payment_method->instantiate($result->pm_name, $result->pm_cost);
            $loaded_payment_method->setId($result->pm_id);

            return $loaded_payment_method;
        }
    }    
    
    /**
     * Selects all payment methods from database
     * @return null|Payment_method_model
     *  NULL if there are no payment methods or array of all payment methods
     */
    public function get_all_payment_methods(){
        
        $payment_methods_raw = $this->payment_method_model->get_all();
        if ( !$payment_methods_raw ){
            return NULL;
        }
        
        $payment_methods_instances_array = array();
        
        foreach ( $payment_methods_raw as $payment_method_instance_raw ){
            $payment_method_instance = new Payment_method_model();
            $payment_method_instance->instantiate( $payment_method_instance_raw->pm_name, $payment_method_instance_raw->pm_cost);
            $payment_method_instance->setId($payment_method_instance_raw->pm_id);
            $payment_methods_instances_array[] = $payment_method_instance;
        }
        
        return $payment_methods_instances_array;
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
     *  ID of a payment method
     */
    public function getId(){
        return $this->id;
    }
    
    /**
     * Gets name
     * @return string
     *  Name of a payment method
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Gets cost(price) of a payment method
     * @return double
     *  Cost(price) of a payment method
     */
    public function getCost() {
        return $this->cost;
    }
}

/* End of file payment_method_model.php */
/* Location: ./application/models/payment_method_model.php */
