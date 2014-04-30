<?php

/**
 * Model class representing raster representation of a component.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Component_raster_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */    
    protected $_table = 'sb_component_raster_representation';
    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */    
    protected $primary_key = 'cmpnnt_rstr_rprsnttn_id';
    
    /**
     * 
     * @var int $id
     *  ID of component raster
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
     * @var int $component
     *  Referenced component
     */
    private $component;
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
    public $protected_attributes = array('cmpnnt_rstr_rprsnttn_id');

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
     * @param int $component
     *  ID of component
     * @param int $pointOfView
     *  ID of point of view
     */
    public function instantiate(
    $photoUrl, $component, $pointOfView) {

        $this->photoUrl = $photoUrl;
        $this->component = $component;
        $this->pointOfView = $pointOfView;
    }

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */     
    public function save() {
        return $this->component_raster_model->insert(
                        array(
                            'cmpnnt_rstr_rprsnttn_photo_url' => $this->photoUrl,
                            'cmpnnt_rstr_rprsnttn_component_id' => ( $this->component instanceof Component_model ? $this->component->getId() : $this->component),
                            'cmpnnt_rstr_rprsnttn_point_of_view_id' => ( $this->pointOfView instanceof Point_of_view_model ? $this->pointOfView->getId() : $this->pointOfView)
                ));
    }
    
    
    /**
     * Updates this object and propagates to a database. Database update operation
     * @return object
     *  NULL or object as a result of update (ID)
     */     
    public function update_raster_model() {
        return $this->component_raster_model->update(
                $this->id,
                        array(
                            'cmpnnt_rstr_rprsnttn_photo_url' => $this->photoUrl,
                            'cmpnnt_rstr_rprsnttn_component_id' => ( $this->component instanceof Component_model ? $this->component->getId() : $this->component),
                            'cmpnnt_rstr_rprsnttn_point_of_view_id' => ( $this->pointOfView instanceof Point_of_view_model ? $this->pointOfView->getId() : $this->pointOfView)
                ));
    }    

    /**
     * Selects single component_raster by component and point of view 
     * @param int $component
     *  Component ID
     * @param int $pov
     *  Point of view ID
     * @return null|\Component_raster_model
     *  Either NULL if such a raster representation does not exist or selected single component raster representation
     */
    public function get_component_single_raster_by_component_and_point_of_view($component, $pov) {
        

        $where = "(cmpnnt_rstr_rprsnttn_component_id=" . $this->db->escape($component) . " AND cmpnnt_rstr_rprsnttn_point_of_view_id=" . $this->db->escape($pov) . ")" ;
        $this->db->where($where);
        $this->db->limit(1);

        $query = $this->db->get($this->_table);

        if( $query->num_rows() <= 0 ){
            return NULL;
        }
        
        $result = $query->row();
        
        $component_raster_model_inst = new Component_raster_model();
        $component_raster_model_inst->instantiate(
                $result->cmpnnt_rstr_rprsnttn_photo_url, $result->cmpnnt_rstr_rprsnttn_component_id, $result->cmpnnt_rstr_rprsnttn_point_of_view_id
        );
        $component_raster_model_inst->setId($result->cmpnnt_rstr_rprsnttn_id);        

        return $component_raster_model_inst;
    }

    /** getters **/
    /**
     * Getter for ID
     * @return int
     *  Component raster model's ID
     */
    public function getId() {
        return $this->id;
    }
    /**
     * Getter for photo URL
     * @return string
     *  Component raster model's photo URL
     */
    public function getPhotoUrl() {
        return $this->photoUrl;
    }
    /**
     * Getter for component reference
     * @return int
     *  Component raster model's component reference
     */
    public function getComponent() {
        return $this->component;
    }
    /**
     * Getter for point of view
     * @return int
     *  Component raster model's point of view
     */
    public function getPointOfView() {
        return $this->pointOfView;
    }
    
    /** setters **/
    /**
     * Setter for photo URL
     * @param string $newPhotoUrl
     *  New photo URL to be set
     */
    public function setPhotoUrl( $newPhotoUrl ) {
        $this->photoUrl = $newPhotoUrl;
    }   
    /**
     * Setter for ID
     * @param int $newId
     *  New ID to be set
     */
    protected function setId($newId) {
        $this->id = $newId;
    }

}

/* End of file component_raster.php */
/* Location: ./application/models/component_raster.php */
