<?php

class Basic_product_model extends MY_Model {

    public $_table = 'sb_basic_product';
    public $primary_key = 'bsc_prdct_id';
    private $id;
    private $price;
    private $product;
    private $creator;
    public $protected_attributes = array('bsc_prdct_id');

    /* basic constructor */

    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */
    public function instantiate($price, $product, $creator) {
        $this->price = $price;
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
                            'bsc_prdct_price' => $this->price,
                            'bsc_prdct_product_id' => ( $this->product instanceof Product_model ? $this->product->getId() : $this->product ),
                            'bsc_prdct_creator_id' => ( $this->creator instanceof User_model ? $this->creator->getUserId() : $this->creator )
                ));
    }

    public function get_all_product_definitions() {
        $all_prod_definitions = $this->product_definition_model->get_all();

        if (!isset($all_prod_definitions) || is_null($all_prod_definitions)) {
            return NULL;
        } else {
            return $all_prod_definitions;
        };
    }

    /*     * ********* setters *********** */

    public function setId($newId) {
        $this->id = $newId;
    }

    public function setPrice($newPrice) {
        $this->price = $newPrice;
    }

    public function setProduct($newProduct) {
        $this->product = $newProduct;
    }

    public function setCreator($newCreator) {
        $this->creator = $newCreator;
    }

    /*     * ********* getters *********** */

    public function getId() {
        return $this->id;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getProduct() {
        return $this->product;
    }

    public function getCreator() {
        return $this->creator;
    }

}

/* End of file basic_product_model.php */
/* Location: ./application/models/basic_product_model.php */
