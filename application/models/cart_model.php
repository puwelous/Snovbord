<?php

/**
 * Model class representing shopping cart.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Cart_model extends MY_Model {

    /**
     * Initialized - cart status
     */
    const CART_STATUS_INITIALIZED = 'INITIALIZED';
    /**
     * Open - cart status
     */    
    const CART_STATUS_OPEN = 'OPEN';
    /**
     * Closed - cart status
     */    
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
    
    /**
     * Selects cart from database according to specified ID
     * @param int $cartId
     *  ID of cart being selected
     * @return null|Cart_model
     *  Either NULL if such a cart does not exist or single cart model instance
     */
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

    /**
     * Selects cart from database according to specified user's ID
     * @param int $owner_id
     *  ID of owner of a cart
     * @return null|Cart_model
     *  Either NULL if such a cart does not exist or single cart model instance
     */    
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

    /**
     * Removes ordered product from a cart
     * 
     * @param Ordered_product_model $orderedProduct
     *  Ordered product being removed  
     * @throws Exception
     *  Exception thrown if database deletion fails
     */
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

    /**
     * Performs recalculation over the present ordered products in a database.
     * @return double
     *  Freshly calculated sum of all products in a cart.
     * @throws EmptyCartException
     *  Thrown if the cart is empty and there is nothing to calculate.
     */
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
    /**
     * Setter for ID
     * @param int $newId
     * New cart ID
     */
    public function setId($newId) {
        $this->id = $newId;
    }
    /**
     * Setter for sum
     * @param double $newSum
     * New cart sum
     */    
    public function setSum($newSum) {
        $this->sum = $newSum;
    }
    /**
     * Setter for status
     * @param string $newStatus
     * New cart status
     */
    public function setStatus($newStatus) {
        $this->status = $newStatus;
    }
    /**
     * Setter for order
     * @param int $newOrder
     * New cart referenced order
     */
    public function setOrder($newOrder) {
        $this->assignedOrder = $newOrder;
    }
    /**
     * Setter for ordering person
     * @param int $newOrderingPerson
     * New cart referenced ordering user
     */
    public function setOrderingPerson($newOrderingPerson) {
        $this->ordering_person = $newOrderingPerson;
    }

    /*     * ********* getters *********** */
    /**
     * Getter for cart ID
     * @return int
     *  ID of a cart
     */
    public function getId() {
        return $this->id;
    }
    /**
     * Getter for cart sum
     * @return double
     *  Sum of a cart
     */
    public function getSum() {
        return $this->sum;
    }
    /**
     * Getter for cart status
     * @return string
     *  Status of a cart
     */
    public function getStatus() {
        return $this->status;
    }
    /**
     * Getter for a cart order
     * @return int
     *  ID of a cart order
     */
    public function getOrder() {
        return $this->assignedOrder;
    }
    /**
     * Getter for cart's ordering person
     * @return int
     *  ID of a cart's ordering person
     */
    public function getOrderingPerson() {
        return $this->ordering_person;
    }
}

/* End of file cart_model.php */
/* Location: ./application/models/cart_model.php */
