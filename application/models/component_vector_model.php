<?php

/**
 * Model class representing vector representation of a component.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Component_vector_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */    
    protected $_table = 'sb_component_vector_representation';
    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */    
    protected $primary_key = 'cmpnnt_vctr_rprsnttn_id';
    /**
     *
     * @var int $id
     *  ID of a component vector model
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
     * @var int $component
     *  ID of referenced component
     */    
    private $component;
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
    public $protected_attributes = array('cmpnnt_vctr_rprsnttn_id');
    
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
     * @param int $component
     *  ID of component which this object refers to
     * @param int $pointOfView
     *  ID of point of view related to this vector representation
     */
    public function instantiate(
    $svgDefinition, $component, $pointOfView) {

        $this->svgDefinition = $svgDefinition;
        $this->component = $component;
        $this->pointOfView = $pointOfView;
    }

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */     
    public function save() {
        return $this->component_vector_model->insert(
                        array(
                            'cmpnnt_vctr_rprsnttn_svg_definition' => $this->svgDefinition,
                            'cmpnnt_vctr_rprsnttn_component_id' => ( $this->component instanceof Component_model ? $this->component->getId() : $this->component),
                            'cmpnnt_vctr_rprsnttn_point_of_view_id' => ( $this->pointOfView instanceof Point_of_view_model ? $this->pointOfView->getId() : $this->pointOfView)
                ));
    }



    /**
     * Selects all component vector models from database according to passed component ID and point of view ID
     * 
     * @param int $component
     *  ID of referenced component
     * @param int $pov
     *  ID of referenced point of view
     * @return null|\Component_vector_model
     *  Either NULL if there are no component vector or array of all component vector found
     */
    public function get_component_vectors_by_component_and_point_of_view($component, $pov) {

        $where = "(cmpnnt_vctr_rprsnttn_component_id=" . $this->db->escape($component) . " AND cmpnnt_vctr_rprsnttn_point_of_view_id=" . $this->db->escape($pov) . ")";
        $this->db->where($where);

        $query = $this->db->get($this->_table);

        log_message('debug', print_r($this->db->last_query(), TRUE));

        if ($query->num_rows() <= 0) {
            return NULL;
        }

        $result_array = array();

        foreach ($query->result() as $component_vector_instance_std_obj) {
            $Component_vector_model_inst = new Component_vector_model();
            $Component_vector_model_inst->instantiate(
                    $component_vector_instance_std_obj->cmpnnt_vctr_rprsnttn_svg_definition, $component_vector_instance_std_obj->cmpnnt_vctr_rprsnttn_component_id, $component_vector_instance_std_obj->cmpnnt_vctr_rprsnttn_point_of_view_id
            );
            $Component_vector_model_inst->setId($component_vector_instance_std_obj->cmpnnt_vctr_rprsnttn_id);
            $result_array[] = $Component_vector_model_inst;
        }

        return $result_array;
    }

    /** setters **/
    
    /**
     * Setter for ID
     * @param int $newId
     *  New ID to be set
     */
    protected function setId($newId) {
        $this->id = $newId;
    }

    /** getters **/
    
    /**
     * Getter for ID
     * @return int
     *  ID of component vector representation
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Getter for SVG definition
     * @return string
     *  SVG definition of vector representation
     */
    public function getSvgDefinition() {
        return $this->svgDefinition;
    }

}

/* End of file component_vector_model.php */
/* Location: ./application/models/component_vector_model.php */
