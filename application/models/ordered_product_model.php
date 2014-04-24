<?php

require_once( APPPATH . '/models/DataHolders/ordered_product_full_info.php');

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

    public function instantiate($product, $count, $possible_size_for_product, $cart) {

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
    
    public function update_ordered_product(){
        return $this->ordered_product_model->update( 
                $this->getId() , 
                array(
            'ordrd_prdct_id' => $this->product,
            'ordrd_prdct_count' => $this->count,
            'ordrd_prdct_psfp_id' => ( $this->possible_size_for_product instanceof Possible_size_for_product_model ? $this->possible_size_for_product->getId() : $this->possible_size_for_product ),
            'ordrd_prdct_crt_id' => ( $this->cart instanceof Cart_model ? $this->cart->getId() : $this->cart )
            ));
    }    

    public function get_ordered_product_by_id( $ordered_product_id ) {
        $result = $this->ordered_product_model->get( $ordered_product_id );

        if (!$result) {
            return NULL;
        } else {
            $loaded_ordered_product = new Ordered_product_model();
            $loaded_ordered_product->instantiate(
                    $result->ordrd_prdct_id, $result->ordrd_prdct_count, $result->ordrd_prdct_psfp_id, $result->ordrd_prdct_crt_id);
            
            $loaded_ordered_product->setId($result->ordrd_prdct_id);

            return $loaded_ordered_product;
        }
    }

    public function get_ordered_product_full_info_by_cart_id($cartId) {
        $this->db->select(
                'sb_ordered_product.ordrd_prdct_id as "orderedProductId", sb_ordered_product.ordrd_prdct_count as "orderedProductCount", sb_ordered_product.ordrd_prdct_crt_id, sb_possible_size_for_product.psfp_name as "possibleSizeForProductName", sb_possible_size_for_product.psfp_product_id, sb_product.prdct_id as "productId", sb_product.prdct_name as "productName", sb_product.prdct_price as "productPrice", sb_user.usr_nick as "creatorNick" ');
        $this->db->from('sb_ordered_product');
        $this->db->where('sb_ordered_product.ordrd_prdct_crt_id', $cartId);
        $this->db->join('sb_possible_size_for_product', 'sb_ordered_product.ordrd_prdct_psfp_id = sb_possible_size_for_product.psfp_id');
        $this->db->join('sb_product', 'sb_possible_size_for_product.psfp_product_id = sb_product.prdct_id');
        $this->db->join('sb_user', 'sb_product.prdct_creator = sb_user.usr_id');

        $query = $this->db->get();

        if ($query->num_rows() <= 0) {
            return NULL;
        }

        $ordered_products_full_info_array = array();

        foreach ($query->result() as $raw_data) {
            $ordered_product_full_info_instance = new Ordered_product_full_info(
                            $raw_data->orderedProductId,
                            $raw_data->orderedProductCount,
                            $raw_data->possibleSizeForProductName,
                            $raw_data->productId,
                            $raw_data->productName,
                            $raw_data->productPrice,
                            $raw_data->creatorNick,
                            NULL // screen representation comes later
            );
            $ordered_products_full_info_array[] = $ordered_product_full_info_instance;
        }

        return $ordered_products_full_info_array;
    }

    public function get_all_ordered_products_by_cart_id($cart_id) {
        $result = $this->ordered_product_model->as_object()->get_many_by('c_id', $cart_id);

        return $result;
    }

    public function get_all_ordered_products_price_including_by_cart_id($cartId) {
        $this->db->select(
                'sb_ordered_product.ordrd_prdct_id as "orderedProductId", sb_ordered_product.ordrd_prdct_count as "orderedProductCount", sb_ordered_product.ordrd_prdct_crt_id, sb_possible_size_for_product.psfp_product_id, sb_product.prdct_id as "productId", sb_product.prdct_price as "productPrice" ');
        $this->db->from('sb_ordered_product');
        $this->db->where('sb_ordered_product.ordrd_prdct_crt_id', $cartId);
        $this->db->join('sb_possible_size_for_product', 'sb_ordered_product.ordrd_prdct_psfp_id = sb_possible_size_for_product.psfp_id');
        $this->db->join('sb_product', 'sb_possible_size_for_product.psfp_product_id = sb_product.prdct_id');

        $query = $this->db->get();

        if ($query->num_rows() <= 0) {
            return NULL;
        }

        $ordered_products_full_info_array = array();

        foreach ($query->result() as $raw_data) {
            $ordered_product_full_info_instance = new Ordered_product_full_info(
                            NULL,
                            $raw_data->orderedProductCount, // this
                            NULL,
                            NULL,
                            NULL,
                            $raw_data->productPrice, // this
                            NULL,
                            NULL
            );
            $ordered_products_full_info_array[] = $ordered_product_full_info_instance;
        }

        return $ordered_products_full_info_array;
    }

    /*     * ********* setters *********** */

    public function setId($newId) {
        $this->id = $newId;
    }

    public function setProductDefinition($newProductDefinition) {
        $this->product_definition = $newProductDefinition;
    }

    public function setCount($newCount) {
        $this->count = $newCount;
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

    public function getId() {
        return $this->id;
    }

    public function getProductDefinition() {
        return $this->product_definition;
    }

    public function getCount() {
        return $this->count;
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
