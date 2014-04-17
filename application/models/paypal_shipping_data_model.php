<?php

class Paypal_shipping_data_model extends MY_Model {

    public $_table = 'pp_paypal_shipping_data';
    public $primary_key = 'psd_order_id';
    public $orderId;
    public $email;
    public $payerId;
    public $payerStatus;
    public $firstName;
    public $lastName;
    public $countryCode;
    public $shipToName;
    public $shipToStreet;
    public $shipToCity;
    public $shipToState;
    public $shipToCountryCode;
    public $shipToZip;
    public $addressStatus;

    //public $protected_attributes = array( 'u_id' );

    public function __construct() {
        parent::__construct();
    }

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

/* End of file company_model.php */
/* Location: ./application/models/company_model.php */
