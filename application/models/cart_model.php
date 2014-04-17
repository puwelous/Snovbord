<?php

class Cart_model extends MY_Model {

    public $_table = 'pp_cart';
    public $primary_key = 'c_id';
    public $sum;
    public $status;
    public $order;
    public $ordering_person;
    public $protected_attributes = array('c_id');
    public $has_many = array('ordered_product' => array('model' => 'ordered_product', 'primary_key' => 'c_id'));

    /* basic constructor */

    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    public function instantiate($sum, $status, $order, $ordering_person) {

        $this->sum = $sum;
        $this->status = $status;
        $this->order = $order;
        $this->ordering_person = $ordering_person;
    }

    /*     * * database operations ** */

    /*
     * create
     */

    public function insert_cart() {

        return $this->cart_model->insert(
                        array(
                            'c_sum' => $this->sum,
                            'c_status' => $this->status,
                            'o_id' => $this->order,
                            'u_ordering_person_id' => $this->ordering_person
                ));
    }

//    public function get_by_email_or_nick_and_password($email_or_nick, $password) {
//
//        $this->db->where("u_email_address", $email_or_nick);
//        $this->db->or_where('u_nick', $email_or_nick);
//        $this->db->where("u_password", md5($password));
//        $this->db->limit(1);
//
//        $query = $this->db->get($this->_table);
//
//        //$str = $this->db->last_query();
//        //log_message('debug', print_r($str, TRUE));
//
//        if ($query->num_rows() > 0) {
//            $row = $query->row();
//
//            return $row;
//        } else {
//            return NULL;
//        }
//    }
//    

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
            $row = $this->cart_model->as_object()->get_by(array('u_ordering_person_id' => $owner_id, 'c_status' => 'OPEN'));
        } else {
            $row = $this->cart_model->as_array()->get_by(array('u_ordering_person_id' => $owner_id, 'c_status' => 'OPEN'));
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

    public function setSum($newSum) {
        $this->sum = $newSum;
    }

    public function setStatus($newStatus) {
        $this->status = $newStatus;
    }

    public function setOrder($newOrder) {
        $this->order = $newOrder;
    }

    public function setOrderingPerson($newOrderingPerson) {
        $this->ordering_person = $newOrderingPerson;
    }

    /*     * ********* getters *********** */

    public function getSum() {
        return $this->sum;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getOrder() {
        return $this->order;
    }

    public function getOrderingPerson() {
        return $this->ordering_person;
    }

}

/* End of file cart_model.php */
/* Location: ./application/models/cart_model.php */
