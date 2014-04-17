<?php

class Contact_video_model extends MY_Model {

    public $_table = 'pp_contact_video';
    public $primary_key = 'cv_id';

    public $url;
    
    public $protected_attributes = array('cv_id');

    
    /* basic constructor */
    public function __construct() {
        parent::__construct();
    }

    /* instance "constructor" */

    public function instantiate($url) {

        $this->url = $url;
    }

    /*     * * database operations ** */
    
    public function insert_contact_video(Contact_video_model $contact_video_instance) {

        $this->contact_video_model->insert(
                array(
                    'cv_url' => $contact_video_instance->url
        ));
    }

    /*     * ********* setters *********** */

    public function setUrl($newUrl) {
        $this->url = $newUrl;
    }

    /*     * ********* getters *********** */

    public function getUrl() {
        return $this->url;
    }
}

/* End of file contact_video_model.php */
/* Location: ./application/models/contact_video_model.php */
