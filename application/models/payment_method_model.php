<?php

class Payment_method_model extends MY_Model {

    public $_table = 'pp_payment_method';
    public $primary_key = 'pm_id';
    
    public $name;
    public $cost;
    
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
    
    public function insert_payment_method(Payment_method_model $payment_method_instance) {

        $this->payment_method_model->insert(
                array(
                    'pm_name' => $payment_method_instance->name,
                    'pm_cost' => $payment_method_instance->cost
        ));
    }

    /*     * ********* setters *********** */

    public function setName($newName) {
        $this->name = $newName;
    }

    public function setCost($newCost) {
        $this->cost = $newCost;
    }

    /*     * ********* getters *********** */

    public function getName() {
        return $this->name;
    }

    public function getCost() {
        return $this->cost;
    }
}

/* End of file payment_method_model.php */
/* Location: ./application/models/payment_method_model.php */
