<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once( APPPATH . '/models/DataHolders/product_screen_representation.php'); //DELETE?
require_once( APPPATH . '/models/DataHolders/ucreate_component_full_info.php');

class C_ucreate extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($prodId = NULL) {

        if (!$this->authentify()) {
            log_message('debug', 'Attempt to edit product for unlogged user. Redirect!');
            redirect('/c_registration/index', 'refresh');
        }

        $template_data = array();
        $this->set_title($template_data, 'Product preview');
        $this->load_header_templates($template_data);

        if (!isset($prodId) || is_null($prodId) || !is_numeric($prodId)) {
            // get any and redirect
            $product_model = $this->product_model->get_any_single_product();
            redirect('ucreate/' . $product_model->getId());
            return;
        } else {
            $product_model = $this->product_model->get_product($prodId);
        }

        $data['product'] = $product_model;
        $data['product_creator_nick'] = $this->user_model->get_user_by_id($product_model->getCreator())->getNick();


        $basic_pov = $this->point_of_view_model->get_basic_pov();
        $basic_raster_model_object = $this->basic_product_raster_model->get_single_basic_product_raster_by_basic_product_id_and_pov_id(
                $product_model->getBasicProduct(), $basic_pov->getId()
        );

        // all urls
        $urls = array();

        // basic raster
        $urls[] = $basic_raster_model_object->getPhotoUrl();

        $compositions = $this->composition_model->get_compositions_by_product_id($product_model->getId());

        foreach ($compositions as $singleComposition) {
            $component_raster_instance = $this->component_raster_model->get_component_single_raster_by_component_and_point_of_view(
                    $singleComposition->getComponent(), $basic_pov->getId());
            $urls[] = $component_raster_instance->getPhotoUrl();
        }

        $product_screen_representations[] = new Product_screen_representation(
                        $product_model->getId(),
                        $product_model->getName(),
                        $urls
        );

        $data['product_representations'] = $product_screen_representations;

        $all_categories = $this->category_model->get_all_categories();
        $data['categories'] = $all_categories;

        //$data['initComponents'] = $this->component_model->get_components_by_category( $all_categories[3]->getId() );
        $all_components = $this->component_model->get_accepted_components();
        //$data['initComponents'] = $all_components; //DELETE LATER!

        $ucreate_component_full_info_array = array();
        foreach ($all_components as $single_component) {
            $available_colours = $this->component_colour_model->get_component_colours_by_component($single_component->getId());

            $component_vector_representations = $this->component_vector_model->get_component_vectors_by_component_and_point_of_view(
                    $single_component->getId(), $basic_pov->getId()
            );
            $component_raster_representation = $this->component_raster_model->get_component_single_raster_by_component_and_point_of_view(
                    $single_component->getId(), $basic_pov->getId()
            );

            $ucreate_component_full_info_array[] = new Ucreate_component_full_info($single_component, $available_colours, $component_vector_representations, $component_raster_representation);
        }
        //log_message('debug', print_r($ucreate_component_full_info_array,true));
        $data['ucreate_component_full_info_array'] = $ucreate_component_full_info_array;


//        $possible_sizes_array = $this->possible_size_for_product_model->get_all_possible_sizes_by_product($product_model->getId());
//
//        $output_sizes = array();
//
//        foreach ($possible_sizes_array as $psfp_instance) {
//            $output_sizes[$psfp_instance->getId()] = $psfp_instance->getName();
//        }
//
//        //log_message('debug', print_r($data['previewed_product_size_options'], TRUE));
//        $data['previewed_product_size_options'] = $output_sizes;


        $this->load->view('templates/header', $template_data);
        $this->load->view('v_ucreate', $data);
    }

    public function create() {
        //log_message('debug', print_r($_POST, true));

        $productData = $this->input->post('product_data');
        // product id of edited product, not id of created one (comes later)
        $product_id = $productData['product_id'];        
        $product_name = $productData['name'];
        $product_price =  $productData['price'];
        $product_description = $productData['description'];
        $product_sex = $productData['sex'];
        $product_creator_nick = $productData['creator_nick'];
        $pictureData = $this->input->post('picture_data');
        
        // try to save a PNG file
        $upload_path = get_product_upload_path($this->config);

        $file_name = generate_product_file_name( $productData['creator_nick'], $productData['name']);
        $file_name .= '.png';
        
        $full_file_name = $upload_path . $file_name;

	$pictureData = str_replace('data:image/png;base64,', '', $pictureData);
	$pictureData = str_replace(' ', '+', $pictureData);
	$pictureData = base64_decode($pictureData);

	$success = file_put_contents( $full_file_name , $pictureData );
        
        if ( !success ){
            $response['error'] = 'Could not create PNG file on a server side!';
            $response['msg'] = NULL;
            echo json_encode($response);
            return;
        }

        // fetch product creator
        $creator = $this->user_model->get_user_by_nick( $product_creator_nick );
        $users_user_type = $this->user_type_model->get_user_type_by_id( $creator->getUserType() );
        if( strtolower($users_user_type->getUserTypeName()) == 'admin' ){
            $acceptanceStatus = Product_model::PRODUCT_STATUS_ACCEPTED;
        }else{
            $acceptanceStatus = Product_model::PRODUCT_STATUS_PROPOSED;
        }

        // fetch basic product according to edited product id
        $product = $this->product_model->get_product($product_id);

        // fetch basic product id
        $basic_product_id = $product->getBasicProduct();

        $this->db->trans_begin(); {
            $new_product = new Product_model();

            $new_product->instantiate(
                    $product_name, $product_price, $product_description, $product_sex, $acceptanceStatus, $creator->getId(), $basic_product_id, $full_file_name);

            $new_product_id = $new_product->save();
            log_message('debug', 'after save product 2 ');
            if (!$new_product_id) {
                $response['error'] = true;
                $response['msg'] = 'Saving product into database failed!';
                log_message('debug', 'Saving product into database failed!');
                $this->db->trans_rollback();
                unlink( $full_file_name );
                echo json_encode($response);
                return;
            }

            // composition
            $appliedComponentsData = $this->input->post('applied_components_data');
            $appliedComponentsValuePairs = $appliedComponentsData['applied_components_value_pairs'];
            foreach ($appliedComponentsValuePairs as $singleAppliedComponentValuePair) {
                $new_composition_instance = new Composition_model();
                $new_composition_instance->instantiate(
                        $singleAppliedComponentValuePair['component_id'], $new_product_id, (!isset($singleAppliedComponentValuePair['component_colour_id']) ||
                        is_null($singleAppliedComponentValuePair['component_colour_id']) ||
                        strlen($singleAppliedComponentValuePair['component_colour_id']) <= 0 ? NULL : $singleAppliedComponentValuePair['component_colour_id'])
                );
                if (!($new_composition_instance->save())) {
                    $response['error'] = true;
                    $response['msg'] = 'Saving composition into database failed!';
                    log_message('debug', 'Saving composition into database failed!');
                    $this->db->trans_rollback();
                    unlink( $full_file_name );
                    echo json_encode($response);
                    return;
                }
            }
        }
        if ($this->db->trans_status() === FALSE) {
            log_message('debug', 'Transaction status is FALSE! Rolling the transaction back!');
            $this->db->trans_rollback();
            $response['error'] = true;
            $response['msg'] = 'Transaction failed!';
            unlink( $full_file_name );
            echo json_encode($response);
            return;
        } else {
            log_message('debug', '... commiting transaction ...!');
            $this->db->trans_commit();
            $response['error'] = false;
            $response['msg'] = 'Call successful!';
            echo json_encode($response);
            return;
        }
    }
}

/* End of file c_ucreate.php */
/* Location: ./application/controllers/c_ucreate.php */