<?php

/**
 * Model class representing order address.
 * Used only if user requires different shipping address than his/her registration one.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Order_address_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */
    public $_table = 'sb_order_address';

    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */
    public $primary_key = 'oa_id';

    /**
     *
     * @var int $id
     *  Payment method ID
     */
    private $id;

    /**
     *
     * @var string $name
     *  Payment method name
     */
    private $name;

    /**
     *
     * @var string $address
     *  Address
     */
    private $address;

    /**
     *
     * @var string $city
     *  City
     */
    private $city;

    /**
     *
     * @var string $zip
     *  ZIP
     */
    private $zip;

    /**
     *
     * @var string $country
     *  Country
     */
    private $country;

    /**
     *
     * @var string $phone_number
     *  Phone number
     */
    private $phone_number;

    /**
     *
     * @var string $email_address
     *  Email address
     */
    private $email_address;

    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */
    public $protected_attributes = array('oa_id');

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
     * @param string $name
     *  Name
     * @param string $address
     *  Address
     * @param string $city
     *  City
     * @param string $zip
     *  ZIP
     * @param string $country
     *  Country
     * @param string $phone_number
     *  Phone number
     * @param string $email_address
     *  Email address
     */
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

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
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

    /**
     * Selects single order address from database by it's ID
     * @param int $orderAddressId
     *  ID of order address
     * @return null|\Order_address_model
     *  Either null if such a order address does not exist or single order address object
     */
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

    /**
     * Setter for ID
     * @param type $newId
     *  New if for order address
     */
    public function setId($newId) {
        $this->id = $newId;
    }

    /*     * ********* getters *********** */

    /**
     * Getter for ID
     * @return int
     *  ID of order address
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Getter for name
     * @return string
     *  Name of order address
     */    
    public function getName() {
        return $this->name;
    }

    /**
     * Getter for address
     * @return string
     *  Address of order address
     */     
    public function getAddress() {
        return $this->address;
    }

    /**
     * Getter for city
     * @return string
     *  City of order address
     */     
    public function getCity() {
        return $this->city;
    }

    /**
     * Getter for ZIP
     * @return string
     *  ZIP of order address
     */     
    public function getZip() {
        return $this->zip;
    }

    /**
     * Getter for country
     * @return string
     *  Country of order address
     */     
    public function getCountry() {
        return $this->country;
    }

    /**
     * Getter for phone number
     * @return string
     *  Phone number of order address
     */     
    public function getPhoneNumber() {
        return $this->phone_number;
    }

    /**
     * Getter for email address
     * @return string
     *  Email address of order address
     */     
    public function getEmailAddress() {
        return $this->email_address;
    }

}

/* End of file order_address_model.php */
/* Location: ./application/models/order_address_model.php */
