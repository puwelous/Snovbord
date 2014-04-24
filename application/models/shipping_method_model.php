<?php

class Shipping_method_model extends MY_Model {

    public $_table = 'sb_shipping_method';
    public $primary_key = 'sm_id';

    private $id;
    private $name;
    private $cost;
    
    public $protected_attributes = array('sm_id');

    
    /* basic constructor */
    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    public function instantiate($name, $price) {

        $this->name=$name;
        $this->cost=$price;
    }

    /*     * * database operations ** */
    
    public function save() {

        return $this->shipping_method_model->insert(
                array(
                    'sm_name' => $this->name,
                    'sm_cost'=> $this->cost
        ));
    }
    
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

/* End of file shipping_method_model.php */
/* Location: ./application/models/shipping_method_model.php */
