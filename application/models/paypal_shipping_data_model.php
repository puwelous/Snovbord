<?php

/**
 * Model class representing PayPal shipping data.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Paypal_shipping_data_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */
    public $_table = 'sb_paypal_shipping_data';
    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */    
    public $primary_key = 'psd_order_id';
    
    /**
     *
     * @var int $orderId
     *  Order ID
     */
    protected $orderId;
    
    /**
     *
     * @var string $email
     * Email of PayPal payment
     */
    protected $email;
    
    /**
     *
     * @var int $payerId
     *  ID of a payer
     */
    protected $payerId;
    
    /**
     *
     * @var string $payerStatus
     *  Status of the payer
     */
    protected $payerStatus;
    /**
     *
     * @var string $firstName
     *  First name of a payer
     */
    protected $firstName;
    /**
     *
     * @var string $lastName
     *  Last name of a payer
     */    
    protected $lastName;

    /**
     *
     * @var string $countryCode
     *  Code of a country
     */
    protected $countryCode;
    
    /**
     *
     * @var string $shipToName
     *  Shipping name of the payer
     */
    protected $shipToName;
    
    /**
     *
     * @var string $shipToStreet
     *  Shipping street of the payer
     */
    protected $shipToStreet;
    
    /**
     *
     * @var string $shipToCity
     *  City where to ship the delivery
     */
    protected $shipToCity;
    
    /**
     *
     * @var string $shipToState
     *  State where to ship the delivery
     */    
    protected $shipToState;
    
    /**
     *
     * @var string $shipToCountryCode
     *  Country code of payment delivery
     */
    protected $shipToCountryCode;
    /**
     *
     * @var string $shipToZip
     *  ZIP of shipping address
     */
    protected $shipToZip;
    /**
     *
     * @var string $addressStatus
     *  Address status of a payer
     */
    protected $addressStatus;

    /**
     * Basic constructor calling parent CRUD abstraction layer contructor
     */    
    public function __construct() {
        parent::__construct();
    }

    /**
     * Constructor-like method for instantiating object of the class.
     * 
     * @param int $orderId
     *  Id of the order
     * @param string $email
     *  Email entered during order creation for PayPal payment
     * @param int $payerId
     *  Id of the payer
     * @param string $payerStatus
     *  Status of the payer
     * @param string $firstName
     *  Payer's first name
     * @param string $lastName
     *  Payer's last name
     * @param string $countryCode
     *  Country of of the payer's addres
     * @param string $shipToName
     *  Receiver name
     * @param string $shipToStreet
     *  Shipping street of performed payment
     * @param string $shipToCity
     *  Shipping city of performed payment
     * @param string $shipToState
     *  Shipping state of performed payment
     * @param string $shipToCountryCode
     *  Shipping country code of performed payment
     * @param string $shipToZip
     *  Shipping ZIP of performed payment
     * @param string $addressStatus
     *  Address status of performed payment
     */
    public function instantiate(
    $orderId, $email, $payerId, $payerStatus, $firstName, $lastName, $countryCode, $shipToName, $shipToStreet, $shipToCity, $shipToState, $shipToCountryCode, $shipToZip, $addressStatus) {

        $this->orderId = $orderId;
        $this->email = $email;
        $this->payerId = $payerId;
        $this->payerStatus = $payerStatus;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->countryCode = $countryCode;
        $this->shipToName = $shipToName;
        $this->shipToStreet = $shipToStreet;
        $this->shipToCity = $shipToCity;
        $this->shipToState = $shipToState;
        $this->shipToCountryCode = $shipToCountryCode;
        $this->shipToZip = $shipToZip;
        $this->addressStatus = $addressStatus;
    }

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */
    public function insert_paypal_shipping_data() {

        return $this->paypal_shipping_data_model->insert(
                array(
                    'psd_order_id' => $this->orderId,
                    'email' => $this->email,
                    'payer_id' => $this->payerId,
                    'payer_status' => $this->payerStatus,
                    'first_name' => $this->firstName,
                    'last_name' => $this->lastName,
                    'country_code' => $this->countryCode,
                    'ship_to_name' => $this->shipToName,
                    'ship_to_street' => $this->shipToStreet,
                    'ship_to_city' => $this->shipToCity,
                    'ship_to_state' => $this->shipToState,
                    'ship_to_country_code' => $this->shipToCountryCode,
                    'ship_to_zip' => $this->shipToZip,
                    'address_status' => $this->addressStatus
        ));
    }

}

/* End of file paypal_shipping_data_model.php */
/* Location: ./application/models/paypal_shipping_data_model.php */
