<?php

class Category_model extends MY_Model {

    public $_table = 'sb_category';
    public $primary_key = 'ctgr_id';
    private $id;
    private $name;
    private $description;
    public $protected_attributes = array('ctgr_id');

    /* basic constructor */

    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    public function instantiate($name, $description) {

        $this->name = $name;
        $this->description = $description;
    }

    /*     * * database operations ** */

    public function save() {

        return $this->category_model->insert(
                        array(
                            'ctgr_name' => $this->name,
                            'ctgr_description' => $this->description
                ));
    }

    public function get_category_by_id( $categoryId ){
        $result = $this->category_model->get( $categoryId );
        
        if ( !$result ) {
            return NULL;
        } else {
            $loaded_category = new Category_model();
            $loaded_category->instantiate($result->ctgr_name, $result->ctgr_description);
            $loaded_category->setId( $result->ctgr_id );

            return $loaded_category;
        }
    }
    
    public function get_all_categories() {        

        $categories = array();
        
        $this->db->order_by("ctgr_name", "asc");
        $result_raw = $this->category_model->as_object()->get_all();
        
        foreach ($result_raw as $category_raw_instance) {
            $category_instance = new Category_model();
            $category_instance->instantiate($category_raw_instance->ctgr_name, $category_raw_instance->ctgr_description);
            $category_instance->setId( $category_raw_instance->ctgr_id );
            
            $categories[] = $category_instance;
        }
        
        return $categories;
    }
    
    public function remove(){
        return $this->category_model->delete( $this->id );
    }

    /*     * ********* setters *********** */

    public function setId($newId) {
        $this->id = $newId;
    }

    public function setName($newName) {
        $this->name = $newName;
    }

    public function setDescription($newDesc) {
        $this->description = $newDesc;
    }

    /*     * ********* getters *********** */

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

}

/* End of file category_model.php */
/* Location: ./application/models/category_model.php */
