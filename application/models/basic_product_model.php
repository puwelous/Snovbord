<?php

class Basic_product_model extends MY_Model {

    public $_table = 'sb_basic_product';
    public $primary_key = 'bsc_prdct_id';

    private $id;
    
    private $product;
    private $creator;
    
    public $protected_attributes = array('bsc_prdct_id');

    
    /* basic constructor */
    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    public function instantiate( $product, $creator ) {

        $this->product = $product;
        $this->creator = $creator;
    }

    /*     * * database operations ** */
    /*
     * create
     */
    public function save() {
        return $this->basic_product_model->insert(
                array(
                    'bsc_prdct_product_id' => ( $this->product instanceof Product_model  ? $this->product->getId() : $this->product ),
                    'bsc_prdct_creator_id' => ( $this->creator instanceof User_model  ? $this->creator->getUserId() : $this->creator )
        ));

    }

    /*
     * read
     */
    public function get_all_product_definitions(){
        $all_prod_definitions = $this->product_definition_model->get_all();
        
        if( !isset($all_prod_definitions) || is_null($all_prod_definitions) ){
            return NULL;
        }else{
            return $all_prod_definitions;
        };
         
    }
    /*     * ********* setters *********** */

    public function setProductName($newProductName) {
        $this->product_name = $newProductName;
    }

    public function setPhotoUrl($newPhotoUrl) {
        $this->photo_url = $newPhotoUrl;
    }

    public function setProductCreator($newProductCreator) {
        $this->product_creator = $newProductCreator;
    }

    public function setType($newType) {
        $this->type = $newType;
    }
    
    public function setPrice($newPrice) {
        $this->price = $newPrice;
    }
    
    public function setSex($newSex){
        $this->sex = $newSex;
    }

    /*     * ********* getters *********** */

    public function getId(){
        return $this->id;
    }
    
    public function getProductName() {
        return $this->product_name;
    }

    public function getPhotoUrl() {
        return $this->photo_url;
    }

    public function getProductCreator() {
        return $this->product_creator;
    }

    public function getType() {
        return $this->type;
    }
    
    public function getPrice() {
        return $this->price;
    }
    
    public function getSex() {
        return $this->sex;
    }    

}

/* End of file product_definition_model.php */
/* Location: ./application/models/product_definition_model.php */
