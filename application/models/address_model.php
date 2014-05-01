<?php

class Address_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */
    protected $_table = 'sb_address';

    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */
    protected $primary_key = 'addrs_id';

    /**
     *
     * @var int $id
     *  ID of address model
     */
    private $id;

    /**
     *
     * @var string $street
     *  Address model street
     */
    private $street;

    /**
     *
     * @var string $city
     *  Address model city
     */
    private $city;

    /**
     *
     * @var string $zip
     *  Address model ZIP
     */
    private $zip;

    /**
     *
     * @var string $country
     *  Address model country
     */
    private $country;

    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */
    public $protected_attributes = array('addrs_id');

    public function __construct() {
        parent::__construct();
    }

    /**
     *  Constructor-like method for instantiating object of the class.
     * 
     * @param string $street
     *  Address' model street
     * @param string $city
     *  Address' model city
     * @param string $zip
     *  Address' model ZIP
     * @param string $country
     *  Address' model country
     */
    public function instantiate(
    $street, $city, $zip, $country) {

        $this->street = $street;
        $this->city = $city;
        $this->zip = $zip;
        $this->country = $country;
    }

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */
    public function save() {
        return $this->address_model->insert(
                        array(
                            'addrs_street' => $this->street,
                            'addrs_city' => $this->city,
                            'addrs_zip' => $this->zip,
                            'addrs_country' => $this->country
                ));
    }

    /**
     * Updates this object and propagates to a database. Database update operation
     * @return object
     *  NULL or object as a result of update (ID)
     */
    public function update_address() {
        return $this->address_model->update(
                        $this->getId(), array(
                    'addrs_street' => $this->street,
                    'addrs_city' => $this->city,
                    'addrs_zip' => $this->zip,
                    'addrs_country' => $this->country
                ));
    }

    /**
     * Selects single address from database according to the specified ID
     * @param int $addressId
     *  ID of address model
     * @return null|Address_model
     *  Either NULL if such a address does not exist or single address model object
     */
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

    /**
     * Getter for address model ID
     * @return int
     *  Address model ID
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Getter for address model street
     * @return string
     *  Address model street
     */
    public function getStreet() {
        return $this->street;
    }

    /**
     * Getter for address model city
     * @return string
     *  Address model city
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Getter for address model ZIP
     * @return string
     *  Address model ZIP
     */
    public function getZip() {
        return $this->zip;
    }

    /**
     * Getter for address model country
     * @return string
     *  Address model country
     */
    public function getCountry() {
        return $this->country;
    }

    /* setters */

    /**
     * Setter for address model ID
     * @param int $newId
     *  New address model ID
     */
    public function setId($newId) {
        $this->id = $newId;
    }

    /**
     * Setter for address model street
     * @param string $newStreet
     *  New street to be set
     */
    public function setStreet($newStreet) {
        $this->street = $newStreet;
    }

    /**
     * Setter for address model city
     * @param string $newCity
     *  New city to be set
     */
    public function setCity($newCity) {
        $this->city = $newCity;
    }

    /**
     * Setter for address model ZIP
     * @param string $newZip
     *  New ZIP to be set
     */
    public function setZip($newZip) {
        $this->zip = $newZip;
    }

    /**
     * Setter for address model country
     * @param string $newCountry
     *  New country to be set
     */
    public function setCountry($newCountry) {
        $this->country = $newCountry;
    }

}

/* End of file address_model.php */
/* Location: ./application/models/address_model.php */
