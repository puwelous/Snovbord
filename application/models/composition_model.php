<?php

/**
 * Model class representing customized product's composition.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Composition_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */    
    public $_table = 'sb_composition';
    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */    
    public $primary_key = 'cmpstn_id';
    private $id;
    private $component;
    private $product;
    private $colour;
    
    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */    
    public $protected_attributes = array('cmpstn_id');

    /**
     * Basic constructor calling parent CRUD abstraction layer contructor
     */
    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    public function instantiate($component, $product, $colour) {

        $this->component = $component;
        $this->product = $product;
        $this->colour = $colour;
    }

    /*     * * database operations ** */

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */     
    public function save() {

        return $this->composition_model->insert(
                        array(
                            'cmpstn_cmpnnt_id' => $this->component,
                            'cmpstn_prdct_id' => $this->product,
                            'cmpstn_cmpnnt_clr_id' => $this->colour
                ));
    }

    public function get_composition_by_id($compositionId) {
        $result = $this->composition_model->get($compositionId);

        if (!$result) {
            return NULL;
        } else {
            $loaded_composition = new Composition_model();
            $loaded_composition->instantiate($loaded_composition->cmpstn_cmpnnt_id, $loaded_composition->cmpstn_prdct_id, $loaded_composition->cmpstn_cmpnnt_clr_id);
            $loaded_composition->setId($loaded_composition->cmpstn_id);
            return $loaded_composition;
        }
    }

    public function get_compositions_by_product_id($productId) {
        $all_compositions = $this->composition_model->get_many_by('cmpstn_prdct_id', $productId);

        if (!isset($all_compositions) || is_null($all_compositions)) {
            return NULL;
        } else {
            $compositions_instances = array();

            foreach ($all_compositions as $item) {
                $loaded_composition = new Composition_model();
                $loaded_composition->instantiate($item->cmpstn_cmpnnt_id, $item->cmpstn_prdct_id, $item->cmpstn_cmpnnt_clr_id);
                $loaded_composition->setId($item->cmpstn_id);
                $compositions_instances[] = $loaded_composition;
            }

            return $compositions_instances;
        };
    }

//    public function get_all_categories() {        
//
//        $categories = array();
//        
//        $this->db->order_by("ctgr_name", "asc");
//        $result_raw = $this->category_model->as_object()->get_all();
//        
//        foreach ($result_raw as $category_raw_instance) {
//            $category_instance = new Category_model();
//            $category_instance->instantiate($category_raw_instance->ctgr_name, $category_raw_instance->ctgr_description);
//            $category_instance->setId( $category_raw_instance->ctgr_id );
//            
//            $categories[] = $category_instance;
//        }
//        
//        return $categories;
//    }
//    
//    public function remove(){
//        return $this->category_model->delete( $this->id );
//    }

    /*     * ********* setters *********** */

    public function setId($newId) {
        $this->id = $newId;
    }

    public function setComponent($newComponent) {
        $this->component = $newComponent;
    }

    public function setProduct($newProduct) {
        $this->product = $newProduct;
    }

    public function setColour($newColour) {
        $this->colour = $newColour;
    }

    /*     * ********* getters *********** */

    public function getId() {
        return $this->id;
    }

    public function getComponent() {
        return $this->component;
    }

    public function getProduct() {
        return $this->product;
    }

    public function getColour() {
        return $this->colour;
    }

}

/* End of file category_model.php */
/* Location: ./application/models/category_model.php */
