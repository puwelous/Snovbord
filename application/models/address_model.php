<?php

class Address_model extends MY_Model {

    protected $_table = 'sb_address';
    protected $primary_key = 'addrs_id';
    private $id;
    private $street;
    private $city;
    private $zip;
    private $country;
    public $protected_attributes = array('addrs_id');

    public function __construct() {
        parent::__construct();
    }

    public function instantiate(
    $street, $city, $zip, $country) {

        $this->street = $street;
        $this->city = $city;
        $this->zip = $zip;
        $this->country = $country;
    }

    public function save() {
        return $this->address_model->insert(
                        array(
                            'addrs_street' => $this->street,
                            'addrs_city' => $this->city,
                            'addrs_zip' => $this->zip,
                            'addrs_country' => $this->country
                ));
    }

    public function get_address_by_id($addressId) {

        $result = $this->address_model->get($addressId);

        if (!$result) {
            return NULL;
        } else {
            $loaded_address = new Address_model();
            $loaded_address->instantiate(
                    $result->addrs_street, $result->addrs_city, $result->addrs_zip, $result->addrs_country 
                    );

            $loaded_address->setId($result->addrs_id);

            return $loaded_address;
        }
    }

    /* getters */

    public function getStreet() {
        return $this->street;
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

    public function getAddressId() {
        log_message('error', 'Delete me! address_model, 74.row, where am I used???');
        return $this->id;
    }
    
    public function getId() {
        return $this->id;
    }    

    /* setters */
    public function setId( $newId ){
        $this->id = $newId;
    }
}

/* End of file address_model.php */
/* Location: ./application/models/address_model.php */
