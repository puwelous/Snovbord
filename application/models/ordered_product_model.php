<?php

class Ordered_product_model extends MY_Model {

    public $_table = 'sb_ordered_product';
    public $primary_key = 'ordrd_prdct_id';

    private $id;
   
    public $product;
    public $count;
    public $possible_size_for_product;
    public $cart;

    public $protected_attributes = array('ordrd_prdct_id');

    /* basic constructor */

    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    public function instantiate( $product, $count, $possible_size_for_product, $cart ) {

        $this->product = $product;
        $this->count = $count;
        $this->possible_size_for_product = $possible_size_for_product;
        $this->cart = $cart;

    }

    /*     * * database operations ** */

    public function save() {

        return $this->ordered_product_model->insert(
                        array(
                            'ordrd_prdct_id' => $this->product,
                            'ordrd_prdct_count' => $this->count,
                            'ordrd_prdct_psfp_id' => ( $this->possible_size_for_product instanceof Possible_size_for_product_model ? $this->possible_size_for_product->getId() : $this->possible_size_for_product ),
                            'ordrd_prdct_crt_id' => ( $this->cart instanceof Cart_model ? $this->cart->getId() : $this->cart )
                ));
    }

    public function get_ordered_product_full_info_by_cart_id($cart_id) {
        $this->db->select('sb_ordered_product.op_id, sb_ordered_product.psfp_name, sb_ordered_product.op_amount, sb_ordered_product.pd_id, sb_product_definition.pd_id, sb_product_definition.pd_product_name, sb_product_definition.pd_photo_url,  sb_product_definition.pd_price, sb_user.u_nick');
        $this->db->from('sb_ordered_product');
        $this->db->where('sb_ordered_product.c_id', $cart_id);
        $this->db->join('sb_product_definition', 'sb_ordered_product.pd_id = sb_product_definition.pd_id');
        $this->db->join('sb_user', 'sb_product_definition.pd_product_creator = sb_user.u_id');

        $query = $this->db->get();

        if ($query->num_rows() <= 0) {
            return NULL;
        }

        return $query->result();
    }
    
    public function get_all_ordered_products_by_cart_id( $cart_id ){
        $result = $this->ordered_product_model->as_object()->get_many_by('c_id', $cart_id);
        
        return $result;
    }
    
    public function get_all_ordered_products_price_including_by_cart_id( $cart_id ){
        $this->db->select('sb_ordered_product.*, sb_product_definition.pd_price');
        $this->db->from('sb_ordered_product');
        $this->db->where('sb_ordered_product.c_id', $cart_id);
        $this->db->join('sb_product_definition', 'sb_ordered_product.pd_id = sb_product_definition.pd_id');

        $query = $this->db->get();

        if ($query->num_rows() <= 0) {
            return NULL;
        }

        return $query->result();
    }    

    /*     * ********* setters *********** */
    public function setId( $newId ){
        $this->id = $newId;
    }
    
    public function setProductDefinition($newProductDefinition) {
        $this->product_definition = $newProductDefinition;
    }

    public function setAmount($newAmount) {
        $this->amount = $newAmount;
    }

    public function setSizeName($newSizeName) {
        $this->size_name = $newSizeName;
    }

    public function setCart($newCart) {
        $this->cart = $newCart;
    }

    public function setOrderer($newOrderer) {
        $this->orderer = $newOrderer;
    }

    /*     * ********* getters *********** */
    public function getId(){
        return $this->id;
    }
    
    public function getProductDefinition() {
        return $this->product_definition;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getSizeName() {
        return $this->size_name;
    }

    public function getCart() {
        return $this->cart;
    }

    public function getOrderer() {
        return $this->orderer;
    }

}

/* End of file product_definition_model.php */
/* Location: ./application/models/product_definition_model.php */
