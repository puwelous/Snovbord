<?php

class Address_model extends MY_Model {

    protected $_table = 'sb_address';
    protected $primary_key = 'addrs_id';
    
    private $addressId;
    
    private $street;
    private $city;
    private $zip;
    private $country;

    public $protected_attributes = array( 'addrs_id' );
    
    public function __construct() {
        parent::__construct();
    }

    public function instantiate(
            $street, 
            $city, 
            $zip,
            $country) {    
        
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
    
    public function getAddressId(){
        return $this->addressId;
    }
}

/* End of file address_model.php */
/* Location: ./application/models/address_model.php */
