<?php

class Order_address_model extends MY_Model {

    public $_table = 'sb_order_address';
    public $primary_key = 'oa_id';
    private $id;
    private $name;
    private $address;
    private $city;
    private $zip;
    private $country;
    private $phone_number;
    private $email_address;
    public $protected_attributes = array('oa_id');

    /* basic constructor */

    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    public function instantiate($name, $address, $city, $zip, $country, $phone_number, $email_address) {

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

    public function save() {
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

    public function get_order_address_by_id($orderAddressId) {

        $result = $this->order_address_model->get($orderAddressId);

        if (!$result) {
            return NULL;
        } else {
            $loaded_order_address = new Order_address_model();
            $loaded_order_address->instantiate(
                    $result->oa_name, $result->oa_address, $result->oa_city, $result->oa_zip, $result->oa_country, $result->oa_phone_number, $result->oa_email_address
            );

            $loaded_order_address->setId($result->oa_id);

            return $loaded_order_address;
        }
    }

    /*     * ********* setters *********** */
    public function setId( $newId){
        $this->id = $newId;
    }
    /*     * ********* getters *********** */
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getCity() {
        return $this->city;
    }

    public function getZip() {
        return $this->zip;
    }

    public function getCountry() {
        return $this->country;
    }
    public function getPhoneNumber() {
        return $this->phone_number;
    }

    public function getEmailAddress() {
        return $this->email_address;
    }
}

/* End of file order_address_model.php */
/* Location: ./application/models/order_address_model.php */
