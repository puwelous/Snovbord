<?php

class Payment_method_model extends MY_Model {

    public $_table = 'sb_payment_method';
    public $primary_key = 'pm_id';
    
    private $id;
    private $name;
    private $cost;
    
    public $protected_attributes = array('pm_id');

    
    /* basic constructor */
    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    public function instantiate($name, $cost) {

        $this->name = $name;
        $this->cost = $cost;
    }

    /*     * * database operations ** */
    
    public function save() {

        $this->payment_method_model->insert(
                array(
                    'pm_name' => $this->name,
                    'pm_cost' => $this->cost
        ));
    }
    
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
    public function setId( $newId ){
        $this->id = $newId;
    }
    
    public function setName($newName) {
        $this->name = $newName;
    }

    public function setCost($newCost) {
        $this->cost = $newCost;
    }

    /*     * ********* getters *********** */
    public function getId(){
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getCost() {
        return $this->cost;
    }
}

/* End of file payment_method_model.php */
/* Location: ./application/models/payment_method_model.php */
