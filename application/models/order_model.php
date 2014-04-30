<?php

/**
 * Model class representing order.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Order_model extends MY_Model {

    const ORDER_STATUS_OPEN = 'OPEN';
    const ORDER_STATUS_PAID = 'PAID';
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

    private $finalSum;
    private $status;
    private $assignedCart;
    private $assignedShippingMethod;
    private $assignedPaymentMethod;
    private $isShippingAddressRegistrationAddress;
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

    public function instantiate($final_sum, $status, $cart, $shippingMethod, $paymentMethod, $is_shipping_address_regist_address,   $order_address) {

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
    public function update_order(){
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
    
    public function get_order_by_id( $orderId ){
        $result = $this->order_model->get( $orderId );

        if (!$result) {
            return NULL;
        }

        $order_instance = new Order_model();
        $order_instance->instantiate(
                $result->ordr_final_sum,
                $result->ordr_status,
                $result->ordr_assigned_cart,
                $result->ordr_assigned_shipping_method,
                $result->ordr_assigned_payment_method,
                $result->ordr_is_ship_addr_regist_addr,
                $result->ordr_order_address_id);
        
        $order_instance->setId($result->ordr_id);

        return $order_instance;
    }
    
    public function get_all_open_orders(){
        $result = $this->order_model->get_many_by('ordr_status', Order_model::ORDER_STATUS_OPEN );
        
        $result_array = array();
        
        foreach ($result as $order_instance_std_obj) {
            $order_model_inst = new Order_model();
            $order_model_inst->instantiate(
                    $order_instance_std_obj->ordr_final_sum,
                    $order_instance_std_obj->ordr_status,
                    $order_instance_std_obj->ordr_assigned_cart,
                    $order_instance_std_obj->ordr_assigned_shipping_method,
                    $order_instance_std_obj->ordr_assigned_payment_method,
                    $order_instance_std_obj->ordr_is_ship_addr_regist_addr,
                    $order_instance_std_obj->ordr_order_address_id
                    );
            $order_model_inst->setId( $order_instance_std_obj->ordr_id );
            $result_array[] = $order_model_inst;
        }
        
        return $result_array;
    }
    
    public function get_all_paid_orders(){
        $result = $this->order_model->get_many_by('ordr_status', Order_model::ORDER_STATUS_PAID);
        
        $result_array = array();
        
        foreach ($result as $order_instance_std_obj) {
            $order_model_inst = new Order_model();
            $order_model_inst->instantiate(
                    $order_instance_std_obj->ordr_final_sum,
                    $order_instance_std_obj->ordr_status,
                    $order_instance_std_obj->ordr_assigned_cart,
                    $order_instance_std_obj->ordr_assigned_shipping_method,
                    $order_instance_std_obj->ordr_assigned_payment_method,
                    $order_instance_std_obj->ordr_is_ship_addr_regist_addr,
                    $order_instance_std_obj->ordr_order_address_id
                    );
            $order_model_inst->setId( $order_instance_std_obj->ordr_id );
            $result_array[] = $order_model_inst;
        }
        
        return $result_array;
    }    
    
    public function get_all_shipping_orders(){
        $result = $this->order_model->get_many_by('ordr_status', Order_model::ORDER_STATUS_SHIPPING);
        
        $result_array = array();
        
        foreach ($result as $order_instance_std_obj) {
            $order_model_inst = new Order_model();
            $order_model_inst->instantiate(
                    $order_instance_std_obj->ordr_final_sum,
                    $order_instance_std_obj->ordr_status,
                    $order_instance_std_obj->ordr_assigned_cart,
                    $order_instance_std_obj->ordr_assigned_shipping_method,
                    $order_instance_std_obj->ordr_assigned_payment_method,
                    $order_instance_std_obj->ordr_is_ship_addr_regist_addr,
                    $order_instance_std_obj->ordr_order_address_id
                    );
            $order_model_inst->setId( $order_instance_std_obj->ordr_id );
            $result_array[] = $order_model_inst;
        }
        
        return $result_array;
    }
    
    public function get_all_by_user_id( $userId ){
        
        $query = $this->db->query('SELECT sb_order.* FROM sb_order WHERE sb_order.ordr_assigned_cart IN ( SELECT sb_cart.crt_id FROM sb_cart WHERE sb_cart.crt_ordering_person_id = '. $this->db->escape( $userId ) .' );');

        if ( $query->num_rows() <= 0 ) {
            return NULL;
        }

        $orders_array = array();

        foreach ($query->result() as $raw_data) {
            $order_model_inst = new Order_model();
            $order_model_inst->instantiate(
                    $raw_data->ordr_final_sum,
                    $raw_data->ordr_status,
                    $raw_data->ordr_assigned_cart,
                    $raw_data->ordr_assigned_shipping_method,
                    $raw_data->ordr_assigned_payment_method,
                    $raw_data->ordr_is_ship_addr_regist_addr,
                    $raw_data->ordr_order_address_id
                    );
            $order_model_inst->setId( $raw_data->ordr_id );
            $orders_array[] = $order_model_inst;
        }

        return $orders_array;
    }     

    /*     * ********* setters *********** */
    public function setId( $newId ){
        $this->id = $newId;
    }
    
    public function setCart($newCart) {
        $this->assignedCart = $newCart;
    }

    public function setShippingMethod($newShippingMethod) {
        $this->assignedShippingMethod = $newShippingMethod;
    }

    public function setPaymentMethod($newPaymentMethod) {
        $this->assignedPaymentMethod = $newPaymentMethod;
    }

    public function setIsShippingAddressRegistrationAddress($newIsShippAddRegAddr) {
        $this->isShippingAddressRegistrationAddress = $newIsShippAddRegAddr;
    }
    
    public function setStatus($newStatus) {
        $this->status = $newStatus;
    }
    
    public function setFinalSum($newFinalSum) {
        $this->finalSum = $newFinalSum;
    }    
    
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
    public function getCart() {
        return $this->assignedCart;
    }

    public function getShippingMethod() {
        return $this->assignedShippingMethod;
    }

    public function getPaymentMethod() {
        return $this->assignedPaymentMethod;
    }

    public function getIsShippingAddressRegistrationAddress() {
        return $this->isShippingAddressRegistrationAddress;
    }
    
    public function getStatus() {
        return $this->status;
    } 
    
    public function getFinalSum() {
        return $this->finalSum;
    }      
    
    public function getOrderAddress() {
        return $this->orderAddress;
    }    

}

/* End of file order_model.php */
/* Location: ./application/models/order_model.php */
