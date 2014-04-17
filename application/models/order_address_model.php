<?php

class Order_address_model extends MY_Model {

    public $_table = 'pp_order_address';
    public $primary_key = 'oa_id';
    public $name;
    public $address;
    public $city;
    public $zip;
    public $country;
    public $phone_number;
    public $email_address;
    public $protected_attributes = array('oa_id');

    /* basic constructor */

    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    public function instantiate($name, $address, $city, $zip, $country, $phone_number,$email_address ) {

        $this->name = $name;
        $this->address = $address;
        $this->city = $city;
        $this->zip = $zip;
        $this->country = $country;
        $this->phone_number = $phone_number;
        $this->email_address = $email_address;
    }

    /*     * * database operations ** */

    /*
     * create
     */

    public function insert_order_address() {
        return $this->order_address_model->insert(
                        array(
                            'oa_name' => $this->name,
                            'oa_address' => $this->address,
                            'oa_city' => $this->city,
                            'oa_zip' => $this->zip,
                            'oa_country' => $this->country,
                            'oa_phone_number' => $this->phone_number,
                            'oa_email_address' => $this->email_address
                ));
    }

//    /*     * ********* setters *********** */
//
//    public function setSum($newSum) {
//        $this->sum = $newSum;
//    }
//
//    public function setStatus($newStatus) {
//        $this->status = $newStatus;
//    }
//
//    public function setOrder($newOrder) {
//        $this->order = $newOrder;
//    }
//
//    public function setOrderingPerson($newOrderingPerson) {
//        $this->ordering_person = $newOrderingPerson;
//    }
//
//    /*     * ********* getters *********** */
//
//    public function getSum() {
//        return $this->sum;
//    }
//
//    public function getStatus() {
//        return $this->status;
//    }
//
//    public function getOrder() {
//        return $this->order;
//    }
//
//    public function getOrderingPerson() {
//        return $this->ordering_person;
//    }

}

/* End of file cart_model.php */
/* Location: ./application/models/cart_model.php */
