<?php

/**
 * Model class representing raster representation of a basic prodcut.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Basic_product_raster_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */
    protected $_table = 'sb_basic_prod_raster_representation';

    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */
    protected $primary_key = 'bs_prdct_rstr_rep_id';

    /**
     *
     * @var int $id
     *  Basic product raster ID
     */
    private $id;

    /**
     *
     * @var string $photoUrl
     *  URL to photo
     */
    private $photoUrl;

    /**
     *
     * @var int $basicProduct
     *  Referenced basic product
     */
    private $basicProduct;

    /**
     *
     * @var int $pointOfView
     *  Referenced point of view
     */
    private $pointOfView;

    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */
    public $protected_attributes = array('bs_prdct_rstr_rep_id');

    /**
     * Basic constructor calling parent CRUD abstraction layer contructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Constructor-like method for instantiating object of the class.
     * 
     * @param string $photoUrl
     *  Photo URL
     * @param int $basicProduct
     *  ID of basic product
     * @param int $pointOfView
     *  ID of point of view
     */
    public function instantiate(
    $photoUrl, $basicProduct, $pointOfView) {

        $this->photoUrl = $photoUrl;
        $this->basicProduct = $basicProduct;
        $this->pointOfView = $pointOfView;
    }

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */
    public function save() {
        return $this->basic_product_raster_model->insert(
                        array(
                            'bs_prdct_rstr_rep_photo_url' => $this->photoUrl,
                            'bs_prdct_rstr_rep_basic_product_id' => ( $this->basicProduct instanceof Basic_product_model ? $this->basicProduct->getId() : $this->basicProduct),
                            'bs_prdct_rstr_rep_basic_product_point_of_view_id' => ( $this->pointOfView instanceof Point_of_view_model ? $this->pointOfView->getId() : $this->pointOfView)
                ));
    }

    /**
     * Selects sing basuc product raster by basic product and point of view
     * @param int $basicProductId
     *  ID of basic product
     * @param int $povId
     *  ID of point of view
     * @return null|Basic_product_raster_model
     * Either NULL if there is no such an entry or single basic product raster model instance
     */
    public function get_single_basic_product_raster_by_basic_product_id_and_pov_id($basicProductId, $povId) {


        $basicProductId = $this->db->escape($basicProductId);
        $povId = $this->db->escape($povId);

        $query = $this->db->query('SELECT bprr.* FROM sb_basic_prod_raster_representation bprr WHERE bprr.bs_prdct_rstr_rep_basic_product_point_of_view_id = ' . $povId . ' AND bprr.bs_prdct_rstr_rep_basic_product_id = ' . $basicProductId . ' LIMIT 1;');

        if ($query->num_rows() <= 0) {
            return NULL;
        }

        $raw_basic_product_raster_item = $query->row();

        $basic_product_raster = new Basic_product_raster_model();
        $basic_product_raster->instantiate(
                $raw_basic_product_raster_item->bs_prdct_rstr_rep_photo_url, $raw_basic_product_raster_item->bs_prdct_rstr_rep_basic_product_id, $raw_basic_product_raster_item->bs_prdct_rstr_rep_basic_product_point_of_view_id
        );
        $basic_product_raster->setId($raw_basic_product_raster_item->bs_prdct_rstr_rep_id);

        return $basic_product_raster;
    }

    /**
     * Getter for basic product raster model ID
     * @return int
     *  ID of basic product raster model
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Getter for basic product raster model photo URL
     * @return string
     *  Photo URL of basic product raster model
     */
    public function getPhotoUrl() {
        return $this->photoUrl;
    }

    /**
     * Setter for basic product raster model ID
     * @param int $newId
     *  New basic product raster model ID to be set
     */
    protected function setId($newId) {
        $this->id = $newId;
    }

}

/* End of file basic_product_raster_model.php */
/* Location: ./application/models/basic_product_raster_model.php */
