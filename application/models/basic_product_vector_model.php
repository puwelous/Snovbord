<?php

/**
 * Model class representing vector representation of basic product.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Basic_product_vector_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */    
    protected $_table = 'sb_basic_prod_vector_representation';
    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */    
    protected $primary_key = 'bsc_prdct_vctr_rprsnttn_id';

    /**
     *
     * @var int $id
     *  ID of a basic product vector model
     */
    private $id;
    /**
     *
     * @var string $svgDefinition
     * SVG definition of this vector representation
     */
    private $svgDefinition;
    /**
     *
     * @var int $basicProduct
     *  ID of referenced basic product
     */
    private $basicProduct;
    /**
     *
     * @var int $pointOfView
     *  ID of referenced point of view
     */
    private $pointOfView;

    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */    
    public $protected_attributes = array('bsc_prdct_vctr_rprsnttn_id');

    /**
     * Basic constructor calling parent CRUD abstraction layer contructor
     */    
    public function __construct() {
        parent::__construct();
    }

    /**
     * Constructor-like method for instantiating object of the class.
     * 
     * @param string $svgDefinition
     *  SVG definition of basic product vector
     * @param int $basicProduct
     *  ID of basic product which this object refers to
     * @param int $pointOfView
     *  ID of point of view related to this vector representation
     */
    public function instantiate(
    $svgDefinition, $basicProduct, $pointOfView) {

        $this->svgDefinition = $svgDefinition;
        $this->basicProduct = $basicProduct;
        $this->pointOfView = $pointOfView;
    }

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */     
    public function save() {
        return $this->basic_product_vector_model->insert(
                        array(
                            'bsc_prdct_vctr_rprsnttn_svg_definition' => $this->svgDefinition,
                            'bsc_prdct_vctr_rprsnttn_basic_product_id' => ( $this->basicProduct instanceof Basic_product_model ? $this->basicProduct->getId() : $this->basicProduct),
                            'bsc_prdct_vctr_rprsnttn_point_of_view_id' => ( $this->pointOfView instanceof Point_of_view_model ? $this->pointOfView->getId() : $this->pointOfView)
                ));
    }
    
    /**
     * Getter for ID
     * @return int
     *  Basic product vector model's ID
     */
    public function getId(){
        return $this->id;
    }
    
    /**
     * Setter for ID
     * @param type $newId
     *  New ID to be set
     */
    protected function setId($newId) {
        $this->id = $newId;
    }

}

/* End of file basic_product_vector_model.php */
/* Location: ./application/models/basic_product_vector_model.php */
