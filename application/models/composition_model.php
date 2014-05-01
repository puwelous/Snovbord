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

    /**
     *
     * @var int $id
     *  Composition ID 
     */
    private $id;

    /**
     *
     * @var int  $component
     * ID of referenced component
     */
    private $component;

    /**
     *
     * @var int  $product
     * ID of referenced product
     */
    private $product;

    /**
     *
     * @var int  $colour
     * ID of referenced component colour
     */
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

    /**
     * Constructor-like method for instantiating object of the class.
     * 
     * @param int $component
     *  Composition's component
     * @param int $product
     *  Composition's product
     * @param int $colour
     *  Composition's colour (if any)
     */
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

    /**
     * Selects the composition by it's ID
     * @param int $compositionId
     *  ID of composition
     * @return null|Composition_model
     *  Either NULL if such a composition does not exist or composition model instance
     */
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

    /**
     * Selects all composition that belong to single product by it's ID
     * @param int $productId
     *  ID of a product
     * @return null|Composition_model
     *  Either NULL if there are no compositions for this product or array of Composition model instances
     */
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

    /*     * ********* setters *********** */

    /**
     * Setter for new ID
     * @param int $newId
     *  New ID of composition
     */
    public function setId($newId) {
        $this->id = $newId;
    }
    /**
     * Setter for new component
     * @param int $newComponent
     *  New component ID of composition
     */
    public function setComponent($newComponent) {
        $this->component = $newComponent;
    }
    /**
     * Setter for new product ID
     * @param int $newProduct
     *  New prodcut ID of composition
     */
    public function setProduct($newProduct) {
        $this->product = $newProduct;
    }
    /**
     * Setter for new colour ID
     * @param int $newColour
     *  New colour ID of composition
     */
    public function setColour($newColour) {
        $this->colour = $newColour;
    }

    /*     * ********* getters *********** */
    /**
     * Getter for ID
     * @return int
     *  ID of a composition
     */
    public function getId() {
        return $this->id;
    }
    /**
     * Getter for referenced component
     * @return int
     *  ID of a referenced component
     */
    public function getComponent() {
        return $this->component;
    }
    /**
     * Getter for referenced product
     * @return int
     *  ID of a referenced product
     */
    public function getProduct() {
        return $this->product;
    }
    /**
     * Getter for referenced colour
     * @return int
     *  ID of a referenced colour
     */
    public function getColour() {
        return $this->colour;
    }

}

/* End of file composition_model.php */
/* Location: ./application/models/composition_model.php */
