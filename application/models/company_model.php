<?php

/**
 * Model class representing company.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Company_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */
    public $_table = 'sb_company';

    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */
    public $primary_key = 'cmpn_id';
    
    /**
     *
     * @var string $provider_firstname
     *  Provider's first name
     */
    public $provider_firstname;
    /**
     *
     * @var string $provider_lastname
     *  Provider's last name 
     */
    public $provider_lastname;
    /**
     *
     * @var string $provider_street
     *  Provider's street
     */
    public $provider_street;
    /**
     *
     * @var string $provider_street_number
     *  Number of a street
     */
    public $provider_street_number;
    /**
     *
     * @var string $provider_city
     *  Provider's city
     */
    public $provider_city;
    /**
     *
     * @var string $provider_country
     *  Provider's country
     */
    public $provider_country;
    /**
     *
     * @var string $provider_email
     *  Email of a provider
     */
    public $provider_email;
    /**
     *
     * @var string $provider_phone_number
     *  Provider's phone number
     */
    public $provider_phone_number;
    /**
     *
     * @var string $provider_rules
     *  Rules that provider defines
     */
    public $provider_rules;

    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */
    public $protected_attributes = array('cmpn_id');

    /**
     * Basic constructor calling parent CRUD abstraction layer contructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 
     * @param string $providerFirstName
     *  Provider's first name
     * @param string $providerLastName
     *  Provider's last name
     * @param string $providerStreet
     *  Provider's street
     * @param string $providerStreetNumber
     *  Provider's street number
     * @param string $providerCity
     *  Provider's city
     * @param string $providerCountry
     *  Provider's country
     * @param string $providerEmail
     *  Provider's email
     * @param string $providerPhoneNumber
     *  Provider's phone number
     * @param string $providerRules
     *  Provider's rules
     */
    public function instantiate($providerFirstName, $providerLastName, $providerStreet, $providerStreetNumber, $providerCity, $providerCountry, $providerEmail, $providerPhoneNumber, $providerRules) {

        $this->provider_firstname = $providerFirstName;
        $this->provider_lastname = $providerLastName;
        $this->provider_street = $providerStreet;
        $this->provider_street_number = $providerStreetNumber;
        $this->provider_city = $providerCity;
        $this->provider_country = $providerCountry;
        $this->provider_email = $providerEmail;
        $this->provider_phone_number = $providerPhoneNumber;
        $this->provider_rules = $providerRules;
    }

    /*     * * database operations ** */

    /**
     * Updates this object and propagates to a database. Database update operation
     * @return object
     *  NULL or object as a result of update (ID)
     */
    public function update_company() {

        $formerCompany = $this->get_company();

        $editedColumns = array();

        if ($formerCompany->cmpn_provider_firstname != $this->provider_firstname) {
            $editedColumns['cmpn_provider_firstname'] = $this->provider_firstname;
        }
        if ($formerCompany->cmpn_provider_lastname != $this->provider_lastname) {
            $editedColumns['cmpn_provider_lastname'] = $this->provider_lastname;
        }
        if ($formerCompany->cmpn_provider_street != $this->provider_street) {
            $editedColumns['cmpn_provider_street'] = $this->provider_street;
        }
        if ($formerCompany->cmpn_provider_street_number != $this->provider_street_number) {
            $editedColumns['cmpn_provider_street_number'] = $this->provider_street_number;
        }
        if ($formerCompany->cmpn_provider_city != $this->provider_city) {
            $editedColumns['cmpn_provider_city'] = $this->provider_city;
        }
        if ($formerCompany->cmpn_provider_country != $this->provider_country) {
            $editedColumns['cmpn_provider_country'] = $this->provider_country;
        }
        if ($formerCompany->cmpn_provider_email != $this->provider_email) {
            $editedColumns['cmpn_provider_email'] = $this->provider_email;
        }
        if ($formerCompany->cmpn_provider_phone_number != $this->provider_phone_number) {
            $editedColumns['cmpn_provider_phone_number'] = $this->provider_phone_number;
        }
        if ($formerCompany->cmpn_rules != $this->provider_rules) {
            $editedColumns['cmpn_rules'] = $this->provider_rules;
        }

        return $this->company_model->update(
                        $formerCompany->cmpn_id, $editedColumns);
    }

    /**
     * Copies passed company attributes into this object
     * 
     * @param Company_model $companyToCompare
     *  Company to take attributes from
     */
    public function update_company_by_company(Company_model $companyToCompare) {

        $editedColumns = array();

        if ($companyToCompare->provider_firstname != $this->provider_firstname) {
            $editedColumns['cmpn_provider_firstname'] = $companyToCompare->provider_firstname;
        }
        if ($companyToCompare->provider_lastname != $this->provider_lastname) {
            $editedColumns['cmpn_provider_lastname'] = $companyToCompare->provider_lastname;
        }
        if ($companyToCompare->provider_street != $this->provider_street) {
            $editedColumns['cmpn_provider_street'] = $companyToCompare->provider_street;
        }
        if ($companyToCompare->provider_street_number != $this->provider_street_number) {
            $editedColumns['cmpn_provider_street_number'] = $companyToCompare->provider_street_number;
        }
        if ($companyToCompare->provider_city != $this->provider_city) {
            $editedColumns['cmpn_provider_city'] = $companyToCompare->provider_city;
        }
        if ($companyToCompare->provider_country != $this->provider_country) {
            $editedColumns['cmpn_provider_country'] = $companyToCompare->provider_country;
        }
        if ($companyToCompare->provider_email != $this->provider_email) {
            $editedColumns['cmpn_provider_email'] = $companyToCompare->provider_email;
        }
        if ($companyToCompare->provider_phone_number != $this->provider_phone_number) {
            $editedColumns['cmpn_provider_phone_number'] = $companyToCompare->provider_phone_number;
        }
        if ($companyToCompare->provider_rules != $this->provider_rules) {
            $editedColumns['cmpn_rules'] = $companyToCompare->provider_rules;
        }

        $this->company_model->update(
                $this->primary_key, $editedColumns);
    }

    /**
     * Retrieves the single company from the database.
     * 
     * @return array Company representation
     */
    public function get_company() {

        $this->db->limit(1);
        $query = $this->db->get($this->_table);

        if ($query->num_rows() > 0) {
            $row = $query->row();

            return $row;
        } else {
            return NULL;
        }
    }

    /**
     * Retrieves the single company from the database.
     * 
     * @return object Company representation
     */    
    public function get_company_as_object() {

        $this->db->limit(1);
        $query = $this->db->get($this->_table);

        if ($query->num_rows() > 0) {
            $row = $query->row();

            $loadedCompany = new Company_model();
            $loadedCompany->instantiate(
                    $row->cmpn_provider_firstname, $row->cmpn_provider_lastname, $row->cmpn_provider_street, $row->cmpn_provider_street_number, $row->cmpn_provider_city, $row->cmpn_provider_country, $row->cmpn_provider_email, $row->cmpn_provider_phone_number, $row->cmpn_rules);

            $loadedCompany->primary_key = $row->cmpn_id;

            return $loadedCompany;
        } else {
            return NULL;
        }
    }

}

/* End of file company_model.php */
/* Location: ./application/models/company_model.php */
