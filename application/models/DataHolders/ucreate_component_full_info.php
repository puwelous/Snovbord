<?php

/*
 * Dataholder class representing ucreate component
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Ucreate_component_full_info {

    /**
     *
     * @var object $componentObject
     *  Component instance
     */
    private $componentObject; // Component_model object

    /**
     *
     * @var array $availableColours
     *  Array of component colour
     */
    private $availableColours; // Component_colour_model multiple object
    /**
     *
     * @var array $vectorRepresentations
     *  Array of vector representations
     */
    private $vectorRepresentations; // Component_vector_model multiple OBJECTS!
    /**
     *
     * @var array $rasterRepresentation
     *  Single raster representation
     */
    private $rasterRepresentation; // Component_raster_model single object

    /**
     * Constructor.
     * 
     * @param type $componentObject
     *  Component instance
     * @param type $availableColours
     * Array of component colour
     * @param type $vectorObjects
     * Array of vector representations
     * @param type $rasterObject
     * Single raster representation
     */

    public function __construct($componentObject, $availableColours, $vectorObjects, $rasterObject) {

        $this->componentObject = $componentObject;
        $this->availableColours = $availableColours;
        $this->vectorRepresentations = $vectorObjects;
        $this->rasterRepresentation = $rasterObject;
    }

    /**
     * Getter for component object
     * @return object 
     *  Component instance
     */
    public function getComponent() {
        return $this->componentObject;
    }

    /**
     * Getter for available colour
     * @return array
     *  Array of available component colour
     */
    public function getAvailableColours() {
        return $this->availableColours;
    }

    /**
     * Getter for vector
     * @return array
     *  Array of vector component representations
     */
    public function getVectors() {
        return $this->vectorRepresentations;
    }

    /**
     * Getter for raster
     * @return object
     *  Single raster component object
     */
    public function getRaster() {
        return $this->rasterRepresentation;
    }

}

/* End of file ucreate_component_full_info.php */
/* Location: ./application/models/ucreate_component_full_info.php */
