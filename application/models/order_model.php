<?php

/**
 * Model class representing order.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Order_model extends MY_Model {
    /**
     * Open - order status
     */
    const ORDER_STATUS_OPEN = 'OPEN';
    /**
     * Paid - order status
     */
    const ORDER_STATUS_PAID = 'PAID';
    /**
     * Shipping - order status
     */
    const ORDER_STATUS_SHIPPING = 'SHIPPING';

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */
    public $_table = 'sb_order';

    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */
    public $primary_key = 'ordr_id';

    /**
     * 
     * @var int $id
     *  ID of order model
     */
    private $id;

    /**
     *
     * @var double $finalSum
     *  Final sum of order model
     */
    private $finalSum;

    /**
     *
     * @var string $status
     *  Status of order model
     */
    private $status;

    /**
     *
     * @var int $assignedCart
     *  Assigned cart of order model
     */
    private $assignedCart;

    /**
     *
     * @var int $assignedShippingMethod
     *  Assigned shipping method of order model
     */
    private $assignedShippingMethod;

    /**
     *
     * @var int $assignedPaymentMethod
     *  Assigned payment method of order model
     */
    private $assignedPaymentMethod;

    /**
     *
     * @var boolean $isShippingAddressRegistrationAddress
     *  Flag specifying whether shipping address is the same as registration one
     */
    private $isShippingAddressRegistrationAddress;

    /**
     *
     * @var int $orderAddress
     *  Assigned order addres (if any)
     */
    public $orderAddress;

    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */
    public $protected_attributes = array('ordr_id');

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
     * @param double $final_sum
     *  Final sum
     * @param string $status
     *  Actual status
     * @param int $cart
     *  Assigned cart
     * @param int $shippingMethod
     *  Assigned shipping method
     * @param int $paymentMethod
     *  Assigned payment method
     * @param boolean $is_shipping_address_regist_address
     *  Flag specifying whether shipping address is the same as registration one
     * @param int $order_address
     *  Assigned order addres (if any)
     */
    public function instantiate($final_sum, $status, $cart, $shippingMethod, $paymentMethod, $is_shipping_address_regist_address, $order_address) {

        $this->finalSum = $final_sum;
        $this->status = $status;
        $this->assignedCart = $cart;
        $this->assignedShippingMethod = $shippingMethod;
        $this->assignedPaymentMethod = $paymentMethod;
        $this->isShippingAddressRegistrationAddress = $is_shipping_address_regist_address;
        $this->orderAddress = $order_address;
    }

    /*     * * database operations ** */

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */
    public function save() {

        return $this->order_model->insert(
                        array(
                            'ordr_final_sum' => $this->finalSum,
                            'ordr_status' => $this->status,
                            'ordr_assigned_cart' => $this->assignedCart,
                            'ordr_assigned_shipping_method' => $this->assignedShippingMethod,
                            'ordr_assigned_payment_method' => $this->assignedPaymentMethod,
                            'ordr_is_ship_addr_regist_addr' => $this->isShippingAddressRegistrationAddress,
                            'ordr_order_address_id' => $this->orderAddress
                ));
    }

    /**
     * Updates this object and propagates to a database. Database update operation
     * @return object
     *  NULL or object as a result of update (ID)
     */
    public function update_order() {
        return $this->order_model->update(
                        $this->getId(), array(
                    'ordr_final_sum' => $this->finalSum,
                    'ordr_status' => $this->status,
                    'ordr_assigned_cart' => $this->assignedCart,
                    'ordr_assigned_shipping_method' => $this->assignedShippingMethod,
                    'ordr_assigned_payment_method' => $this->assignedPaymentMethod,
                    'ordr_is_ship_addr_regist_addr' => $this->isShippingAddressRegistrationAddress,
                    'ordr_order_address_id' => $this->orderAddress
                ));
    }

    /**
     * Selects order model instance according to specified ID
     * @param int $orderId
     *  ID of the order
     * @return null|Order_model
     *  Either NULL if such an order does not exist or selected order model instance
     */
    public function get_order_by_id($orderId) {
        $result = $this->order_model->get($orderId);

        if (!$result) {
            return NULL;
        }

        $order_instance = new Order_model();
        $order_instance->instantiate(
                $result->ordr_final_sum, $result->ordr_status, $result->ordr_assigned_cart, $result->ordr_assigned_shipping_method, $result->ordr_assigned_payment_method, $result->ordr_is_ship_addr_regist_addr, $result->ordr_order_address_id);

        $order_instance->setId($result->ordr_id);

        return $order_instance;
    }

    /**
     * Selects all open orders
     * @return Order_model
     * Array of all open order models with OPEN status
     */
    public function get_all_open_orders() {
        $result = $this->order_model->get_many_by('ordr_status', Order_model::ORDER_STATUS_OPEN);

        $result_array = array();

        foreach ($result as $order_instance_std_obj) {
            $order_model_inst = new Order_model();
            $order_model_inst->instantiate(
                    $order_instance_std_obj->ordr_final_sum, $order_instance_std_obj->ordr_status, $order_instance_std_obj->ordr_assigned_cart, $order_instance_std_obj->ordr_assigned_shipping_method, $order_instance_std_obj->ordr_assigned_payment_method, $order_instance_std_obj->ordr_is_ship_addr_regist_addr, $order_instance_std_obj->ordr_order_address_id
            );
            $order_model_inst->setId($order_instance_std_obj->ordr_id);
            $result_array[] = $order_model_inst;
        }

        return $result_array;
    }

    /**
     * Selects all paid orders
     * @return Order_model
     * Array of all paid order models with PAID status
     */
    public function get_all_paid_orders() {
        $result = $this->order_model->get_many_by('ordr_status', Order_model::ORDER_STATUS_PAID);

        $result_array = array();

        foreach ($result as $order_instance_std_obj) {
            $order_model_inst = new Order_model();
            $order_model_inst->instantiate(
                    $order_instance_std_obj->ordr_final_sum, $order_instance_std_obj->ordr_status, $order_instance_std_obj->ordr_assigned_cart, $order_instance_std_obj->ordr_assigned_shipping_method, $order_instance_std_obj->ordr_assigned_payment_method, $order_instance_std_obj->ordr_is_ship_addr_regist_addr, $order_instance_std_obj->ordr_order_address_id
            );
            $order_model_inst->setId($order_instance_std_obj->ordr_id);
            $result_array[] = $order_model_inst;
        }

        return $result_array;
    }

    /**
     * Selects all shipping orders
     * @return Order_model
     * Array of all shipping order models with SHIPPING status
     */
    public function get_all_shipping_orders() {
        $result = $this->order_model->get_many_by('ordr_status', Order_model::ORDER_STATUS_SHIPPING);

        $result_array = array();

        foreach ($result as $order_instance_std_obj) {
            $order_model_inst = new Order_model();
            $order_model_inst->instantiate(
                    $order_instance_std_obj->ordr_final_sum, $order_instance_std_obj->ordr_status, $order_instance_std_obj->ordr_assigned_cart, $order_instance_std_obj->ordr_assigned_shipping_method, $order_instance_std_obj->ordr_assigned_payment_method, $order_instance_std_obj->ordr_is_ship_addr_regist_addr, $order_instance_std_obj->ordr_order_address_id
            );
            $order_model_inst->setId($order_instance_std_obj->ordr_id);
            $result_array[] = $order_model_inst;
        }

        return $result_array;
    }

    /**
     * Selects all order model instances assigned to specified user
     * @param int $userId
     *  ID of user order model instances are looked up by
     * @return null|Order_model
     * Either NULL if there are no order models for this user or array of all order models that belong to the user
     */
    public function get_all_by_user_id($userId) {

        $query = $this->db->query('SELECT sb_order.* FROM sb_order WHERE sb_order.ordr_assigned_cart IN ( SELECT sb_cart.crt_id FROM sb_cart WHERE sb_cart.crt_ordering_person_id = ' . $this->db->escape($userId) . ' );');

        if ($query->num_rows() <= 0) {
            return NULL;
        }

        $orders_array = array();

        foreach ($query->result() as $raw_data) {
            $order_model_inst = new Order_model();
            $order_model_inst->instantiate(
                    $raw_data->ordr_final_sum, $raw_data->ordr_status, $raw_data->ordr_assigned_cart, $raw_data->ordr_assigned_shipping_method, $raw_data->ordr_assigned_payment_method, $raw_data->ordr_is_ship_addr_regist_addr, $raw_data->ordr_order_address_id
            );
            $order_model_inst->setId($raw_data->ordr_id);
            $orders_array[] = $order_model_inst;
        }

        return $orders_array;
    }

    /*     * ********* setters *********** */

    /**
     * Setter for order model ID
     * @param int $newId
     *  New order model ID
     */
    public function setId($newId) {
        $this->id = $newId;
    }

    /**
     * Setter for order model cart ID
     * @param int $newCart
     *  New order model cart ID
     */
    public function setCart($newCart) {
        $this->assignedCart = $newCart;
    }

    /**
     * Setter for order model shipping method ID
     * @param int $newShippingMethod
     *  New order model shipping method ID
     */
    public function setShippingMethod($newShippingMethod) {
        $this->assignedShippingMethod = $newShippingMethod;
    }

    /**
     * Setter for order model payment method ID
     * @param int $newPaymentMethod
     *  New order model payment method ID
     */
    public function setPaymentMethod($newPaymentMethod) {
        $this->assignedPaymentMethod = $newPaymentMethod;
    }

    /**
     * Setter for order model shipping address = registration address flag
     * @param boolean $newIsShippAddRegAddr
     *  New order model shipping address = registration address flag
     */
    public function setIsShippingAddressRegistrationAddress($newIsShippAddRegAddr) {
        $this->isShippingAddressRegistrationAddress = $newIsShippAddRegAddr;
    }

    /**
     * Setter for order model status
     * @param string $newStatus
     *  New order model status
     */
    public function setStatus($newStatus) {
        $this->status = $newStatus;
    }

    /**
     * Setter for order model final sum
     * @param double $newFinalSum
     *  New order model final sum
     */
    public function setFinalSum($newFinalSum) {
        $this->finalSum = $newFinalSum;
    }

    /**
     * Setter for order model order address ID
     * @param int $newOrderAddress
     *  New order model order address ID
     */
    public function setOrderAddress($newOrderAddress) {
        $this->orderAddress = $newOrderAddress;
    }

    /*     * ********* getters *********** */

    /**
     * Getter for ID
     * @return int
     *  Order model's ID
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Getter for order's cart ID
     * @return int
     *  Order model's cart ID
     */
    public function getCart() {
        return $this->assignedCart;
    }

    /**
     * Getter for order's shipping method ID
     * @return int
     *  Order model's shipping method ID
     */
    public function getShippingMethod() {
        return $this->assignedShippingMethod;
    }

    /**
     * Getter for order's payment method ID
     * @return int
     *  Order model's payment method ID
     */
    public function getPaymentMethod() {
        return $this->assignedPaymentMethod;
    }

    /**
     * Getter for getIsShippingAddressRegistrationAddress flag
     * @return boolean
     *  Order model's - registration addres same as shipping one - flag
     */
    public function getIsShippingAddressRegistrationAddress() {
        return $this->isShippingAddressRegistrationAddress;
    }

    /**
     * Getter for order's status
     * @return string
     *  Order model's status
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Getter for order's final sum
     * @return double
     *  Order model's final sum
     */
    public function getFinalSum() {
        return $this->finalSum;
    }

    /**
     * Getter for order's address model
     * @return int
     *  Order model's address model ID
     */
    public function getOrderAddress() {
        return $this->orderAddress;
    }

}

/* End of file order_model.php */
/* Location: ./application/models/order_model.php */
