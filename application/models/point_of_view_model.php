<?php

/**
 * Model class representing point of view.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Point_of_view_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */    
    protected $_table = 'sb_point_of_view';
    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */    
    protected $primary_key = 'pov_id';
    /**
     *
     * @var int $id
     *  Point of view ID
     */
    private $id;
    /**
     *
     * @var string $name
     *  Point of view name
     */
    private $name;
    /**
     *
     * @var boolean $isBasic
     *  Flag whether point of view is basic or not
     */
    private $isBasic;
    
    /**
     * 
     * @var array $protected_attributes
     *  Array of attributes that are not directly accesed via CRUD abstract model
     */    
    public $protected_attributes = array('pov_id');

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
     *  Name of point of view
     * @param boolean $isBasic
     *  Flag if the point of view is basic or not
     */
    public function instantiate(
    $name, $isBasic) {

        $this->name = $name;
        $this->isBasic = $isBasic;
    }

    /**
     * Inserts this object into a database. Database create operation
     * 
     * @return object
     *  NULL or object as a result of insertion
     */     
    public function save() {
        return $this->point_of_view_model->insert(
                        array(
                            'pov_name' => $this->name,
                            'pov_is_basic' => $this->isBasic
                ));
    }

    /**
     * Selects all discting point of view names from database
     * 
     * @return null|array
     *  Either NULL if there are no point of view(s names) in database or array of strings with distinct point of view names
     */
    public function get_pov_names_distinct() {

        $this->db->select('pov_name');
        $this->db->distinct();

        $query = $this->db->get($this->_table);

        $result = array();

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $stdObj) {
                $result[] = $stdObj->pov_name;
            }
            //var_dump($result);
            return $result;
        } else {
            return NULL;
        }
    }

    /**
     * Selects basic point of view model according to the isBasic flag
     * @return null|Point_of_view_model
     * Either NULL if such a basic point of view does not exist or single basic point of view instance
     */
    public function get_basic_pov(){
        $result = $this->point_of_view_model->get_by('pov_is_basic', TRUE);
        if ( !$result ) {
            return NULL;
        } else {
            $loaded_pov = new Point_of_view_model();
            $loaded_pov->instantiate(
                    $result->pov_name, $result->pov_is_basic
                    );

            $loaded_pov->setId($result->pov_id);

            return $loaded_pov;
        }        
    }

//    public function get_rasters_urls_by_pov($pov, $url_column_alias = NULL) {
//
//        $pov_id = ( $pov instanceof Point_of_view_model ? $pov->getId() : $pov );
//        $url_column_alias = ( $url_column_alias != NULL ? mysql_escape_string($url_column_alias) : 'photo_url' );
//
//        $select_from_basic = 'SELECT bprr.bs_prdct_rstr_rep_photo_url as ' . $url_column_alias . ' FROM sb_basic_prod_raster_representation bprr WHERE bprr.bs_prdct_rstr_rep_basic_product_point_of_view_id =' . $pov_id . ' ';
//        $select_from_comp = 'SELECT crr.cmpnnt_rstr_rprsnttn_photo_url as ' . $url_column_alias . ' FROM sb_component_raster_representation crr WHERE crr.cmpnnt_rstr_rprsnttn_point_of_view_id =' . $pov_id . ' ';
//
//        $query = $this->db->query($select_from_basic . ' UNION ' . $select_from_comp . ';');
//
//        //$query = $this->db->get();
//
//        if ($query->num_rows() <= 0) {
//            return NULL;
//        }
//
//        return $query->result();
//    }
    /**
     * Getter for point of view ID
     * @return int
     *  Point of view ID
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Setter for point of view ID
     * @param int $newId
     *  New point of view ID
     */
    public function setId($newId) {
        $this->id = $newId;
    }
}

/* End of file point_of_view_model.php */
/* Location: ./application/models/point_of_view_model.php */
