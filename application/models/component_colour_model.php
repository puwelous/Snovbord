<?php

/**
 * Model class representing component's colour.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Component_colour_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */    
    public $_table = 'sb_component_colour';

    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */
    public $primary_key = 'cmpnt_clr_id';
    
    /**
     *
     * @var int $id
     *  ID of a component colour
     */
    private $id;
    /**
     *
     * @var string $value
     *  HEXA component colour value
     */
    private $value;
    /**
     *
     * @var int $component
     *  ID of referred component
     */
    private $component;

    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */    
    public $protected_attributes = array('cmpnt_clr_id');

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
     * @param string $value
     *  HEXA colour value
     * @param int $component
     *  Component ID
     */
    public function instantiate($value, $component) {

        $this->value = $value;
        $this->component = $component;
    }

    /*     * * database operations ** */

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */     
    public function save() {
         return $this->component_colour_model->insert(
                        array(
                            'cmpnt_clr_value' => $this->value,
                            'cmpnt_clr_component_id' => $this->component
                ));
    }

//    public function get_component_colour_by_id($componentColourId) {
//        $result = $this->component_colour_model->get($componentId);
//
//        if (!$result) {
//            return NULL;
//        } else {
//            $loaded_component = new Component_model();
//            $loaded_component->instantiate(
//                    $result->cmpnt_name, $result->cmpnt_price, $result->cmpnt_acceptance_status, $result->cmpnt_is_stable, $result->cmpnt_creator_id, $result->cmpnt_category_id
//            );
//            $loaded_component->setId($result->cmpnt_id);
//
//            return $loaded_component;
//        }
//    }

//    public function get_all_components() {
//
//        $components = array();
//
//        //$this->db->order_by("ctgr_name", "asc");
//        $result_raw = $this->component_model->as_object()->get_all();
//
//        foreach ($result_raw as $result) {
//            $component_instance = new Component_model();
//            $component_instance->instantiate(
//                $result->cmpnt_name, $result->cmpnt_price, $result->cmpnt_acceptance_status, $result->cmpnt_is_stable, $result->cmpnt_creator_id, $result->cmpnt_category_id                    
//            );
//            $component_instance->setId($result->cmpnt_id);
//
//            $components[] = $component_instance;
//        }
//
//        return $components;
//    }
    
    /**
     * Selects all component colours from database according to passed component ID
     * @param int $component
     *  ID of a component
     * @return null|Component_colour_model
     *  NULL if there are no colours for specified component or array of all component colours
     */
    public function get_component_colours_by_component( $component ) {

        $component = ( $component instanceof Component_model ? $component->getId() : $component);
        
        $colours = array();

        $result_raw = $this->component_colour_model->get_many_by('cmpnt_clr_component_id', $component);

        if( !$result_raw ){
            return NULL;
        }
        
        foreach ($result_raw as $result) {
            $component_colour_instance = new Component_colour_model();
            $component_colour_instance->instantiate(
                $result->cmpnt_clr_value, $result->cmpnt_clr_component_id                    
            );
            $component_colour_instance->setId($result->cmpnt_clr_id);

            $colours[] = $component_colour_instance;
        }

        return $colours;
    }    

    /**
     * Deletes component colour from database
     * @return null|array
     *  NULL if deletion fails or ID of deleted row
     */
    public function remove() {
        return $this->component_colour_model->delete($this->id);
    }

    /*     * ********* setters *********** */

    /**
     * Setter for component colour ID
     * @param int $newId
     *  New ID of a component colour 
     */
    public function setId($newId) {
        $this->id = $newId;
    }

    /**
     * Setter for component colour HEXA value
     * @param string $newValue
     *  New HEXA value of a component colour 
     */    
    public function setValue( $newValue) {
        $this->value = $newValue;
    }

    /**
     * Setter for component colour referred component
     * @param int $newComponent
     *  New component referenced by this component colour
     */     
    public function setComponent($newComponent) {
        $this->component = $newComponent;
    }

    /*     * ********* getters *********** */

    /**
     * Getter for component colour ID
     * @return int
     *  ID of component colour
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Getter for component colour value
     * @return string
     *  HEXA value of component colour
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Getter for referencing component
     * @return int 
     *  ID of referencing component
     */
    public function getComponent() {
        return $this->component;
    }
}

/* End of file component_colour_model.php */
/* Location: ./application/models/component_colour_model.php */
