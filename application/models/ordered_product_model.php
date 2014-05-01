<?php

require_once( APPPATH . '/models/DataHolders/ordered_product_full_info.php');

/**
 * Model class representing ordered product.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Ordered_product_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */    
    public $_table = 'sb_ordered_product';
    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */    
    public $primary_key = 'ordrd_prdct_id';
    
    /**
     * 
     * @var int $id
     * ID of ordered product
     */
    private $id;
    /**
     *
     * @var int $product
     *  ID of referenced product
     */
    public $product;
    /**
     *
     * @var int $count
     *  Amount of ordered product
     */
    public $count;
    /**
     *
     * @var int $possible_size_for_product
     *  ID of possible size for product
     */
    public $possible_size_for_product;
    /**
     *
     * @var int $cart
     * ID of referenced cart
     */
    public $cart;
    
    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */    
    public $protected_attributes = array('ordrd_prdct_id');

    /**
     * Basic constructor calling parent CRUD abstraction layer contructor
     */
    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    /**
     * Constructor-like method for instantiating object of the class.
     * 
     * @param int $product
     *  ID of referenced product
     * @param int $count
     *  Amount of ordered product
     * @param int $possible_size_for_product
     *  ID of referenced possible size
     * @param int $cart
     *  ID of referenced cart
     */
    public function instantiate($product, $count, $possible_size_for_product, $cart) {

        $this->product = $product;
        $this->count = $count;
        $this->possible_size_for_product = $possible_size_for_product;
        $this->cart = $cart;
    }

    /*     * * database operations ** */

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */     
    public function save() {

        return $this->ordered_product_model->insert(
                        array(
                            'ordrd_prdct_id' => $this->product,
                            'ordrd_prdct_count' => $this->count,
                            'ordrd_prdct_psfp_id' => ( $this->possible_size_for_product instanceof Possible_size_for_product_model ? $this->possible_size_for_product->getId() : $this->possible_size_for_product ),
                            'ordrd_prdct_crt_id' => ( $this->cart instanceof Cart_model ? $this->cart->getId() : $this->cart )
                ));
    }
    
    /**
     * Updates this object and propagates to a database. Database update operation
     * @return object
     *  NULL or object as a result of update (ID)
     */     
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

    /**
     * Selects single ordered product model from database according to passed ID
     * @param int $ordered_product_id
     *  ID of selected ordered product
     * @return null|Ordered_product_model
     *  Either NULL if such a ordered product does not exist or single ordered product object instance
     */
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

    /**
     * Selects extended information about ordered products, their size and so on according to cart ID
     * @param int $cartId
     *  ID of a cart
     * @return null|Ordered_product_full_info
     *  Returns NULL if there is no such a cart or return Ordered_product_full_info object
     */
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

    /**
     *  Selects all ordered products + additional info which belong to specific cart defined by it's ID
     * @param int $cartId
     *  ID of a cart
     * @return null|Ordered_product_full_info
     *  Either NULL if there is no relevant info related to specified cart or array of Ordered_product_full_info objects
     */
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
    /**
     * Setter for ordered product ID
     * @param int $newId
     *  New ID of ordered product
     */
    public function setId($newId) {
        $this->id = $newId;
    }

    /**
     * Setter for ordered product referenced product
     * @param int $newProductDefinition
     *  New referenced product
     */
    public function setProductDefinition($newProductDefinition) {
        $this->product_definition = $newProductDefinition;
    }

    /**
     * Setter for ordered product count
     * @param int $newCount
     *  Ordered product's new count to be set
     */
    public function setCount($newCount) {
        $this->count = $newCount;
    }

    /**
     * Setter for ordered product size name
     * @param string $newSizeName
     *  New size name to be set
     */
    public function setSizeName($newSizeName) {
        $this->size_name = $newSizeName;
    }

    /**
     * Setter for ordered product cart
     * @param int $newCart
     *  ID of new cart to be set
     */
    public function setCart($newCart) {
        $this->cart = $newCart;
    }

    /**
     * Setter for ordered product referenced order
     * @param int $newOrderer
     * ID of new order to be set
     */
    public function setOrderer($newOrderer) {
        $this->orderer = $newOrderer;
    }

    /*     * ********* getters *********** */
    /**
     * Getter for ordered product ID
     * @return int
     *  ID of ordered product
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Getter for ordered product referenced product
     * @return int
     *  ID of referenced product
     */    
    public function getProductDefinition() {
        return $this->product_definition;
    }

    /**
     * Getter for ordered product count
     * @return int
     *  Count of ordered product
     */
    public function getCount() {
        return $this->count;
    }

    /**
     * getter for ordered product's size name
     * @return string
     *  Size name of ordered product
     */
    public function getSizeName() {
        return $this->size_name;
    }

    /**
     * Getter for ordered product referenced cart
     * @return int
     *  ID of referenced cart
     */        
    public function getCart() {
        return $this->cart;
    }
}

/* End of file ordered_product_model.php */
/* Location: ./application/models/ordered_product_model.php */
