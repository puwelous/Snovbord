<?php

/**
 * Model class representing component.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Component_model extends MY_Model {

    const COMPONENT_STATUS_PROPOSED         = 'PROPOSED';
    const COMPONENT_STATUS_DECLINED_UNSEEN  = 'DECLINED_UNSEEN';
    const COMPONENT_STATUS_DECLINED_SEEN    = 'DECLINED_SEEN';
    const COMPONENT_STATUS_ACCEPTED         = 'ACCEPTED';
    
    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */    
    public $_table = 'sb_component';
    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */    
    public $primary_key = 'cmpnt_id';
    private $id;
    private $name;
    private $price;
    private $acceptanceStatus;
    private $isStable;
    private $creator;
    private $category;
    
    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */    
    public $protected_attributes = array('cmpnt_id');

    /**
     * Basic constructor calling parent CRUD abstraction layer contructor
     */
    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    public function instantiate($name, $price, $acceptanceStatus, $isStable, $creator, $category) {

        $this->name = $name;
        $this->price = $price;
        $this->acceptanceStatus = $acceptanceStatus;
        $this->isStable = $isStable;
        $this->creator = $creator;
        $this->category = $category;
    }

    /*     * * database operations ** */

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */     
    public function save() {
         return $this->component_model->insert(
                        array(
                            'cmpnt_name' => $this->name,
                            'cmpnt_price' => $this->price,
                            'cmpnt_acceptance_status' => $this->acceptanceStatus,
                            'cmpnt_is_stable' => $this->isStable,
                            'cmpnt_creator_id' => $this->creator,
                            'cmpnt_category_id' => $this->category
                ));
    }
    
    /**
     * Updates this object and propagates to a database. Database update operation
     * @return object
     *  NULL or object as a result of update (ID)
     */     
    public function update_component() {
        return $this->component_model->update(
                        $this->getId(), array(
                            'cmpnt_name' => $this->name,
                            'cmpnt_price' => $this->price,
                            'cmpnt_acceptance_status' => $this->acceptanceStatus,
                            'cmpnt_is_stable' => $this->isStable,
                            'cmpnt_creator_id' => $this->creator,
                            'cmpnt_category_id' => $this->category
                ));
    }     

    public function get_component_by_id($componentId) {
        $result = $this->component_model->get($componentId);

        if (!$result) {
            return NULL;
        } else {
            $loaded_component = new Component_model();
            $loaded_component->instantiate(
                    $result->cmpnt_name, $result->cmpnt_price, $result->cmpnt_acceptance_status, $result->cmpnt_is_stable, $result->cmpnt_creator_id, $result->cmpnt_category_id
            );
            $loaded_component->setId($result->cmpnt_id);

            return $loaded_component;
        }
    }

    public function get_all_components() {

        $components = array();

        //$this->db->order_by("ctgr_name", "asc");
        $this->db->order_by("cmpnt_category_id", "asc"); 
        $result_raw = $this->component_model->as_object()->get_all();

        foreach ($result_raw as $result) {
            $component_instance = new Component_model();
            $component_instance->instantiate(
                $result->cmpnt_name, $result->cmpnt_price, $result->cmpnt_acceptance_status, $result->cmpnt_is_stable, $result->cmpnt_creator_id, $result->cmpnt_category_id                    
            );
            $component_instance->setId($result->cmpnt_id);

            $components[] = $component_instance;
        }

        return $components;
    }
    
    public function get_components_by_category( $categoryId ) {

        $components = array();

        $result_raw = $this->component_model->get_many_by('cmpnt_category_id', $categoryId);

        foreach ($result_raw as $result) {
            $component_instance = new Component_model();
            $component_instance->instantiate(
                $result->cmpnt_name, $result->cmpnt_price, $result->cmpnt_acceptance_status, $result->cmpnt_is_stable, $result->cmpnt_creator_id, $result->cmpnt_category_id                    
            );
            $component_instance->setId($result->cmpnt_id);

            $components[] = $component_instance;
        }

        return $components;
    }  
    
    public function get_proposed_components(){
        return $this->get_components_by_status(Component_model::COMPONENT_STATUS_PROPOSED);
    }
    
    public function get_accepted_components(){
        return $this->get_components_by_status(Component_model::COMPONENT_STATUS_ACCEPTED);
    }    
    
    private function get_components_by_status( $status ) {

        $components = array();

        $result_raw = $this->component_model->get_many_by('cmpnt_acceptance_status', $status);

        foreach ($result_raw as $result) {
            $component_instance = new Component_model();
            $component_instance->instantiate(
                $result->cmpnt_name, $result->cmpnt_price, $result->cmpnt_acceptance_status, $result->cmpnt_is_stable, $result->cmpnt_creator_id, $result->cmpnt_category_id                    
            );
            $component_instance->setId($result->cmpnt_id);

            $components[] = $component_instance;
        }

        return $components;
    }     

    public function remove() {
        return $this->component_model->delete($this->id);
    }

    /*     * ********* setters *********** */

    public function setId($newId) {
        $this->id = $newId;
    }

    public function setName($newName) {
        $this->name = $newName;
    }

    public function setPrice($newPrice) {
        $this->price = $newPrice;
    }

    public function setAcceptanceStatus($newAcceptanceStatus) {
        $this->acceptanceStatus = $newAcceptanceStatus;
    }

    public function setIsStable($newIsStable) {
        $this->isStable = $newIsStable;
    }

    public function setCreator($newCreator) {
        $this->creator = $newCreator;
    }

    public function setCategory($newCategory) {
        $this->category = $newCategory;
    }

    /*     * ********* getters *********** */

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getAcceptanceStatus() {
        return $this->acceptanceStatus;
    }

    public function getIsStable() {
        return $this->isStable;
    }

    public function getCreator() {
        return $this->creator;
    }

    public function getCategory() {
        return $this->category;
    }

}

/* End of file component_model.php */
/* Location: ./application/models/component_model.php */
