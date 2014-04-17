<?php

class Shipping_method_model extends MY_Model {

    public $_table = 'pp_shipping_method';
    public $primary_key = 'sm_id';

    public $name;
    public $price;
    
    public $protected_attributes = array('sm_id');

    
    /* basic constructor */
    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    public function instantiate($name, $price) {

        $this->name=$name;
        $this->price=$price;
    }

    /*     * * database operations ** */
    
    public function insert_shipping_method(Shipping_method_model $shipping_method_instance) {

        $this->shipping_method_model->insert(
                array(
                    'sm_name' => $shipping_method_instance->name,
                    'sm_price'=> $shipping_method_instance->price
        ));
    }

    /*     * ********* setters *********** */

    public function setName($newName) {
        $this->name = $newName;
    }
    
    public function setPrice($newPrice) {
        $this->price = $newPrice;
    }    

    /*     * ********* getters *********** */

    public function getName() {
        return $this->name;
    }
    
    public function getPrice() {
        return $this->price;
    }    
}

/* End of file shipping_method_model.php */
/* Location: ./application/models/shipping_method_model.php */
