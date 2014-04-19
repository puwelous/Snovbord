<?php

class Possible_size_for_product_model extends MY_Model {

    public $_table = 'sb_possible_size_for_product';
    public $primary_key = 'psfp_id';

    public $product_definition;//refers to product_definition model by id or not????
    public $name;
    public $amount;
    
    public $protected_attributes = array('psfp_id');
    
    /* basic constructor */
    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    public function instantiate($product_definition, $name, $amount) {

        $this->product_definition   = $product_definition;
        $this->name                 = $name;
        $this->amount               = $amount;
    }

    /*     * * database operations ** */
    
    public function insert_possible_size_for_product() {

        $id_of_psfp = $this->possible_size_for_product_model->insert(
                array(
                    'pd_id'         => $this->product_definition,
                    'psfp_name'     => $this->name,
                    'psfp_amount'   => $this->amount
        ));
        
        return $id_of_psfp;
    }
    
    public function get_possible_size_for_product_by( $column, $value){
        $row = $this->possible_size_for_product_model->as_object()->get_by( $column, $value );

        return $row;
    }
    
    public function get_all_possible_sizes_for_product_by( $column, $value, $asObject = TRUE){
        if( $asObject ){
            $row = $this->possible_size_for_product_model->as_object()->get_many_by( $column, $value );
        }else{
            $row = $this->possible_size_for_product_model->as_array()->get_many_by( $column, $value );
        }

        return $row;
    }    

    /*     * ********* setters *********** */
    public function setProductDefinition($newProductDefinition) {
        $this->product_definition = $newProductDefinition;
    }
    
    public function setName($newName) {
        $this->name = $newName;
    }
    
    public function setAmount($newAmount) {
        $this->amount = $newAmount;
    }    

    /*     * ********* getters *********** */

    public function getProductDefinition() {
        return $this->product_definition;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getAmount() {
        return $this->amount;
    }     
}

/* End of file possible_size_for_product_model.php */
/* Location: ./application/models/possible_size_for_product_model.php */
