<?php

class Cart_model extends MY_Model {

    const CART_STATUS_INITIALIZED = 'INITIALIZED';
    const CART_STATUS_OPEN = 'OPEN';
    
    public $_table = 'sb_cart';
    public $primary_key = 'crt_id';
    
    protected $id;
    
    protected $sum;
    protected $status;
    protected $assignedOrder;
    protected $ordering_person;
    
    public $protected_attributes = array('crt_id');
    public $has_many = array('ordered_product' => array('model' => 'ordered_product', 'primary_key' => 'crt_id'));

    /* basic constructor */

    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    public function instantiate($sum, $status, $order, $ordering_person) {

        $this->sum = $sum;
        $this->status = $status;
        $this->assignedOrder = $order;
        $this->ordering_person = ($ordering_person instanceof User_model ? $ordering_person->getId() : $ordering_person );
    }

    /*     * * database operations ** */

    /*
     * create
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
    
    public function update_cart(){
        return $this->cart_model->update( 
                $this->getId() , 
                array(
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

    public function get_cart_by_owner_id($owner_id, $asObject = TRUE) {
        return $this->is_present_by('u_ordering_person_id', $owner_id, $asObject);
    }

    public function get_open_cart_by_owner_id($owner_id, $asObject = TRUE) {
        if ($asObject) {
            $row = $this->cart_model->as_object()->get_by(array('crt_ordering_person_id' => $owner_id, 'crt_status' => 'OPEN'));
        } else {
            $row = $this->cart_model->as_array()->get_by(array('crt_ordering_person_id' => $owner_id, 'crt_status' => 'OPEN'));
        }
        return $row;
    }

//    public function get_open_cart_including_ordered_prods_by_owner_id($owner_id, $asObject = TRUE) {
//        if ($asObject) {
//            $row = $this->cart_model->as_object()->with('ordered_product')->get_by(array('u_ordering_person_id' => $owner_id, 'c_status' => 'OPEN'));
//        } else {
//            $row = $this->cart_model->as_array()->with('ordered_product')->get_by(array('u_ordering_person_id' => $owner_id, 'c_status' => 'OPEN'));
//        }
//
//        return $row;
//    }

    /*     * ********* setters *********** */
    public function setId( $newId ){
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

    public function getId(){
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
