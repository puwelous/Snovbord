<?php

/**
 * Model class representing component's category.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Category_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */
    public $_table = 'sb_category';

    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */
    public $primary_key = 'ctgr_id';

    /**
     *
     * @var int $id
     *  Category ID
     */
    private $id;

    /**
     *
     * @var string $name
     *  Category name
     */
    private $name;

    /**
     *
     * @var string $description
     *  Category description
     */
    private $description;

    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */
    public $protected_attributes = array('ctgr_id');

    /**
     * Basic constructor calling parent CRUD abstraction layer contructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Constructor-like method for instantiating object of the class.
     * 
     * @param string $name
     *  Category name
     * @param string $description
     *  Category description
     */
    public function instantiate($name, $description) {

        $this->name = $name;
        $this->description = $description;
    }

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */
    public function save() {

        return $this->category_model->insert(
                        array(
                            'ctgr_name' => $this->name,
                            'ctgr_description' => $this->description
                ));
    }

    /**
     * Selects catogry according to it's ID passed as argument
     * @param int $categoryId
     *  ID of a category
     * @return null|Category_model
     *  Either NULL if such a category does not exist or single category model instance
     */
    public function get_category_by_id($categoryId) {
        $result = $this->category_model->get($categoryId);

        if (!$result) {
            return NULL;
        } else {
            $loaded_category = new Category_model();
            $loaded_category->instantiate($result->ctgr_name, $result->ctgr_description);
            $loaded_category->setId($result->ctgr_id);

            return $loaded_category;
        }
    }

    /**
     * Selects all categories from database
     * @return null|Category_model
     *  Either NULL if there are no categories or array including all category model instances
     */
    public function get_all_categories() {

        $categories = array();

        $this->db->order_by("ctgr_name", "asc");
        $result_raw = $this->category_model->as_object()->get_all();
        if (!$result_raw) {
            return NULL;
        }

        foreach ($result_raw as $category_raw_instance) {
            $category_instance = new Category_model();
            $category_instance->instantiate($category_raw_instance->ctgr_name, $category_raw_instance->ctgr_description);
            $category_instance->setId($category_raw_instance->ctgr_id);

            $categories[] = $category_instance;
        }

        return $categories;
    }

    /**
     * Removes this category
     * @return int
     *  Result of category removal. Usually ID of deleted category or negative value if fails
     */
    public function remove() {
        return $this->category_model->delete($this->id);
    }

    /*     * ********* setters *********** */

    /**
     * Setter for category ID
     * @param int $newId
     *  New category ID
     */
    public function setId($newId) {
        $this->id = $newId;
    }

    /**
     * Setter for category name
     * @param string $newName
     *  New category name
     */
    public function setName($newName) {
        $this->name = $newName;
    }

    /**
     * Setter for category description
     * @param string $newDesc
     *  New category description
     */
    public function setDescription($newDesc) {
        $this->description = $newDesc;
    }

    /*     * ********* getters *********** */
    /**
     * Getter for category ID
     * @return int
     *  Category ID
     */
    public function getId() {
        return $this->id;
    }
    /**
     * Getter for category name
     * @return string
     *  Category name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Getter for category description
     * @return string
     *  Category description
     */
    public function getDescription() {
        return $this->description;
    }

}

/* End of file category_model.php */
/* Location: ./application/models/category_model.php */
