<?php

class Order_model extends MY_Model {

    const ORDER_STATUS_OPEN = 'OPEN';
    const ORDER_STATUS_PAID = 'PAID';
    const ORDER_STATUS_SHIPPING = 'SHIPPING';
    
    public $_table = 'sb_order';
    public $primary_key = 'ordr_id';

    private $id;

    private $finalSum;
    private $status;
    private $assignedCart;
    private $assignedShippingMethod;
    private $assignedPaymentMethod;
    private $isShippingAddressRegistrationAddress;
    public $orderAddress;
    
    public $protected_attributes = array('ordr_id');

    /* basic constructor */
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
    public function getId( ){
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
