<?php

class Company_model extends MY_Model {

    public $_table = 'pp_company';
    public $primary_key = 'cmpn_id';
    
    public $provider_firstname;
    public $provider_lastname;
    public $provider_street;
    public $provider_street_number;
    public $provider_city;
    public $provider_country;
    public $provider_email;
    public $provider_phone_number;
    public $provider_rules;
    public $protected_attributes = array('cmpn_id');

    public function __construct() {
        parent::__construct();
    }

    public function instantiate($providerFirstName, $providerLastName, $providerStreet, $providerStreetNumber, $providerCity, $providerCountry, $providerEmail, $providerPhoneNumber, $providerRules) {

        $this->provider_firstname = $providerFirstName;
        $this->provider_lastname = $providerLastName;
        $this->provider_street = $providerStreet;
        $this->provider_street_number = $providerStreetNumber;
        $this->provider_city = $providerCity;
        $this->provider_country = $providerCountry;
        $this->provider_email = $providerEmail;
        $this->provider_phone_number = $providerPhoneNumber;
        $this->provider_rules = $providerRules ;
    }

    /*     * * database operations ** */
//
//    public function insert_company(Company_model $company_instance) {
//
//        $this->cart_model->insert(
//                array(
//                    'c_sum' => $cart_instance->sum,
//                    'c_status' => $cart_instance->status,
//                    'o_id' => $cart_instance->order,
//                    'u_ordering_person_id' => $cart_instance->ordering_person
//        ));
//    }
    
    public function update_company(){
        
        $formerCompany = $this->get_company();
        
        $editedColumns = array();

        if( $formerCompany->cmpn_provider_firstname != $this->provider_firstname ){
            $editedColumns['cmpn_provider_firstname'] = $this->provider_firstname;
        }
        if( $formerCompany->cmpn_provider_lastname != $this->provider_lastname ){
            $editedColumns['cmpn_provider_lastname'] = $this->provider_lastname;
        } 
        if( $formerCompany->cmpn_provider_street != $this->provider_street ){
            $editedColumns['cmpn_provider_street'] = $this->provider_street;
        }
        if( $formerCompany->cmpn_provider_street_number != $this->provider_street_number ){
            $editedColumns['cmpn_provider_street_number'] = $this->provider_street_number;
        }  
        if( $formerCompany->cmpn_provider_city != $this->provider_city ){
            $editedColumns['cmpn_provider_city'] = $this->provider_city;
        }
        if( $formerCompany->cmpn_provider_country != $this->provider_country ){
            $editedColumns['cmpn_provider_country'] = $this->provider_country;
        }
        if( $formerCompany->cmpn_provider_email != $this->provider_email ){
            $editedColumns['cmpn_provider_email'] = $this->provider_email;
        } 
        if( $formerCompany->cmpn_provider_phone_number != $this->provider_phone_number ){
            $editedColumns['cmpn_provider_phone_number'] = $this->provider_phone_number;
        }
        if( $formerCompany->cmpn_rules != $this->provider_rules ){
            $editedColumns['cmpn_rules'] = $this->provider_rules;
        }        
        
        $this->company_model->update(
                $formerCompany->cmpn_id,
                $editedColumns);
    }
    
    public function update_company_by_company(Company_model $companyToCompare){
       
        $editedColumns = array();

        if( $companyToCompare->provider_firstname != $this->provider_firstname ){
            $editedColumns['cmpn_provider_firstname'] = $companyToCompare->provider_firstname;
        }
        if( $companyToCompare->provider_lastname != $this->provider_lastname ){
            $editedColumns['cmpn_provider_lastname'] = $companyToCompare->provider_lastname;
        } 
        if( $companyToCompare->provider_street != $this->provider_street ){
            $editedColumns['cmpn_provider_street'] = $companyToCompare->provider_street;
        }
        if( $companyToCompare->provider_street_number != $this->provider_street_number ){
            $editedColumns['cmpn_provider_street_number'] = $companyToCompare->provider_street_number;
        }  
        if( $companyToCompare->provider_city != $this->provider_city ){
            $editedColumns['cmpn_provider_city'] = $companyToCompare->provider_city;
        }
        if( $companyToCompare->provider_country != $this->provider_country ){
            $editedColumns['cmpn_provider_country'] = $companyToCompare->provider_country;
        }
        if( $companyToCompare->provider_email != $this->provider_email ){
            $editedColumns['cmpn_provider_email'] = $companyToCompare->provider_email;
        } 
        if( $companyToCompare->provider_phone_number != $this->provider_phone_number ){
            $editedColumns['cmpn_provider_phone_number'] = $companyToCompare->provider_phone_number;
        }
        if( $companyToCompare->provider_rules != $this->provider_rules ){
            $editedColumns['cmpn_rules'] = $companyToCompare->provider_rules;
        }        
        
        $this->company_model->update(
                $this->primary_key,
                $editedColumns);
    }    

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
    
    public function get_company_as_object() {

        $this->db->limit(1);
        $query = $this->db->get($this->_table);

        if ($query->num_rows() > 0) {
            $row = $query->row();

            $loadedCompany = new Company_model();
            $loadedCompany->instantiate(
                    $row->cmpn_provider_firstname, 
                    $row->cmpn_provider_lastname, 
                    $row->cmpn_provider_street, 
                    $row->cmpn_provider_street_number,
                    $row->cmpn_provider_city,
                    $row->cmpn_provider_country, 
                    $row->cmpn_provider_email, 
                    $row->cmpn_provider_phone_number, 
                    $row->cmpn_rules);
            
            $loadedCompany->primary_key = $row->cmpn_id;
            
            return $loadedCompany;
        } else {
            return NULL;
        }
    }    

}

/* End of file company_model.php */
/* Location: ./application/models/company_model.php */
