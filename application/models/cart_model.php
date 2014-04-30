<?php

/**
 * Model class representing shopping cart.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Cart_model extends MY_Model {

    const CART_STATUS_INITIALIZED = 'INITIALIZED';
    const CART_STATUS_OPEN = 'OPEN';
    const CART_STATUS_CLOSED = 'CLOSED';

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */    
    public $_table = 'sb_cart';
    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */    
    public $primary_key = 'crt_id';
    
    /**
     *
     * @var int $id
     *  ID of cart
     */
    protected $id;
    /**
     *
     * @var double $sum
     *  Sum of cart
     */
    protected $sum;
    /**
     *
     * @var string $status
     *  Status of cart
     */
    protected $status;
    /**
     *
     * @var int $assignedOrder
     *  Cart's assigned order
     */
    protected $assignedOrder;
    /**
     *
     * @var int $ordering_person
     *  Ordering person
     */
    protected $ordering_person;
    
    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */    
    public $protected_attributes = array('crt_id');
    
    public $has_many = array('ordered_product' => array('model' => 'ordered_product', 'primary_key' => 'crt_id'));

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
     * @param double $sum
     *  Actual sum of cart
     * @param string $status
     *  Cart status
     * @param int $order
     *  Order ID
     * @param User_model $ordering_person
     * Person who makes an order
     */
    public function instantiate($sum, $status, $order, $ordering_person) {

        $this->sum = $sum;
        $this->status = $status;
        $this->assignedOrder = $order;
        $this->ordering_person = ($ordering_person instanceof User_model ? $ordering_person->getId() : $ordering_person );
    }

    /*     * * database operations ** */
    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */     
    public function save() {

        return $this->cart_model->insert(
                        array(
                            'crt_sum' => $this->sum,
                            'crt_status' => $this->status,
                            'crt_assigned_order' => ($this->assignedOrder instanceof Order_model ? $this->assignedOrder->getId() : $this->assignedOrder ),
                            'crt_ordering_person_id' => ( $this->ordering_person instanceof User_model ? $this->ordering_person->getId() : $this->ordering_person)
                ));
    }

    /**
     * Updates this object and propagates to a database. Database update operation
     * @return object
     *  NULL or object as a result of update (ID)
     */     
    public function update_cart() {
        return $this->cart_model->update(
                        $this->getId(), array(
                    'crt_sum' => $this->getSum(),
                    'crt_status' => $this->getStatus(),
                    'crt_assigned_order' => ( $this->getOrder() instanceof Order_model ? $this->getOrder()->getId() : $this->getOrder() ),
                    'crt_ordering_person_id' => ( $this->getOrderingPerson() instanceof User_model ? $this->getOrderingPerson()->getId() : $this->getOrderingPerson() )
                ));
    }

    public function is_present_by($column, $value, $asObject = TRUE) {
        if ($asObject) {
            $row = $this->cart_model->as_object()->get_by($column, $value);
        } else {
            $row = $this->cart_model->as_array()->get_by($column, $value);
        }

        return $row;
    }
    
    public function get_cart_by_id( $cartId ) {
        $result = $this->cart_model->get( $cartId );

        if (!$result) {
            return NULL;
        }

        $cart_instance = new Cart_model();
        $cart_instance->instantiate($result->crt_sum, $result->crt_status, $result->crt_assigned_order, $result->crt_ordering_person_id);
        $cart_instance->setId($result->crt_id);

        return $cart_instance;
    }    

    public function get_cart_by_owner_id($owner_id, $asObject = TRUE) {
        return $this->is_present_by('u_ordering_person_id', $owner_id, $asObject);
    }

    public function get_open_cart_by_owner_id($owner_id) {

        $result = $this->cart_model->get_by(array('crt_ordering_person_id' => $owner_id, 'crt_status' => self::CART_STATUS_OPEN));

        if (!$result) {
            return NULL;
        }

        $cart_instance = new Cart_model();
        $cart_instance->instantiate($result->crt_sum, $result->crt_status, $result->crt_assigned_order, $result->crt_ordering_person_id);
        $cart_instance->setId($result->crt_id);

        return $cart_instance;
    }

    public function remove_ordered_product($orderedProduct) {

        $ordered_product_id = ( $orderedProduct instanceof Ordered_product_model ? $orderedProduct->getId() : $orderedProduct );

        log_message('debug', 'Trying to remove ordered_product ' . $ordered_product_id . ' from cart ' . $this->id);

        // load ordered_product according to parameter passes
        // delete ordered product from sb_ordered_product table
        $delete_result = $this->ordered_product_model->delete($ordered_product_id);
        if ($delete_result <= 0) {
            log_message('debug', 'Result of deletion is nonpositive. How come user tried to delete ordered product that does not exist? Form generation failed?');
            throw new Exception('Deleting ordered_product from DB failed.');
            return;
        }

        // recalculation
        $this->recalculate_sum();
    }

    public function recalculate_sum() {
        $finalSum = 0.0;

        // get all ordered_products that belong to shopping cart
        $ordered_products_price_incl = $this->ordered_product_model->get_all_ordered_products_price_including_by_cart_id($this->id);

        if (count($ordered_products_price_incl) == 0) {
            // no ordered products in a cart
            throw new EmptyCartException('Cart(ID:' . $this->id . ') is empty. Not necessary to calculate it`s value.');
           // throw new Exception('Cart(ID:' . $this->id . ') is empty. Not necessary to calculate it`s value.');
        } else {
            // some products in a cart still left, calculate value
            foreach ($ordered_products_price_incl as $single_ordered_product_with_price) {
                $price_per_ordered_product = ($single_ordered_product_with_price->getProductPrice() * $single_ordered_product_with_price->getOrderedProductCount());
                $finalSum = $finalSum + $price_per_ordered_product;
            }
            $this->setSum($finalSum);
            return $finalSum;
        }
    }

    /*     * ********* setters *********** */
    public function setId($newId) {
        $this->id = $newId;
    }

    public function setSum($newSum) {
        $this->sum = $newSum;
    }

    public function setStatus($newStatus) {
        $this->status = $newStatus;
    }

    public function setOrder($newOrder) {
        $this->assignedOrder = $newOrder;
    }

    public function setOrderingPerson($newOrderingPerson) {
        $this->ordering_person = $newOrderingPerson;
    }

    /*     * ********* getters *********** */

    public function getId() {
        return $this->id;
    }

    public function getSum() {
        return $this->sum;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getOrder() {
        return $this->assignedOrder;
    }

    public function getOrderingPerson() {
        return $this->ordering_person;
    }

}

/* End of file cart_model.php */
/* Location: ./application/models/cart_model.php */
