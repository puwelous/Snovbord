<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//log_message('debug', APPPATH . 'libraries/Pinterest/COEmbedImpl.php');
//
//if (file_exists(APPPATH . 'libraries/Pinterest/OEmbed/COEmbedImpl.php')) {
//    log_message('debug', "The file filename exists");
//} else {
//    log_message('debug', "The file filename NOT exists");
//}
//require_once( APPPATH . '/libraries/Pinterest/OEmbed/COEmbedImpl.php');
//
// include helping library
require_once( APPPATH . '/libraries/Pinterest/OEmbed/COEmbedBasicErrorResponseGeneratorImpl.php');
require_once( APPPATH . '/libraries/Pinterest/OEmbed/OEmbedResponseMessage.php');

require_once( APPPATH . '/libraries/Pinterest/CPinterestImpl.php');



class C_services extends MY_Controller {

    const ALLOWED_URL_SCHEMA = 'http://puwel.sk/products/';
    const URI_SEGMENT_TO_PRODUCTS_PREVIEW = '/preview/show/';
    const CACHE_AGE = 21600;
    const PP_PROVIDER_NAME = 'PowPorn';
    const PP_STORE_NAME = 'PowPornStore';
    const PP_CATEGORY = 'products';

    public function __construct() {
        parent::__construct();
    }

    // URL scheme - accepted url in our case is:http://www.puwel.sk/products/*
    // and API endpoint:http://www.puwel.sk/services/eombed/
    public function oembed() {

        // load OEmbed library
        $oembedParams = array(COEmbedImpl::ACCEPTED_URL_SCHEME_KEY_VALUE => self::ALLOWED_URL_SCHEMA);
        
        $this->load->library('Pinterest/OEmbed/COEmbedBasicErrorResponseGeneratorImpl');
        $this->load->library('Pinterest/CPinterestImpl', $oembedParams);

        $input_format = $this->input->get('format');
        log_message('debug', 'format:"' . print_r($input_format, true) . '"');
   
        if ( $input_format === '' || empty( $input_format )){
            // set explicitly
            $content_type = 'application/json';
        }else if( $input_format == 'json' ){
            $content_type = 'application/json';
        }else{
            //TODO: XML not supported yet!
            //$content_type = 'application/json';
            $content_type = 'text/xml';
        }
        
        // setting content type
        $this->output->set_content_type($content_type);

        // parsing URL scheme from GET parameters
        $input_url = $this->input->get('url');

        log_message('debug', 'URL as parameter from input is:"' . $input_url . '"');

        try {
            // checking input
            if (!$this->cpinterestimpl->check_url_from_get($input_url)) {
                log_message('debug', 'URL from GET has been NOT accepted.');
                $responseErrorMessage = $this->coembedbasicerrorresponsegeneratorimpl->generate_404_not_found(self::ALLOWED_URL_SCHEMA);
                $this->output->set_output(json_encode($responseErrorMessage->getData()));
                return;
            }
        } catch (AcceptedUrlNotDefinedException $e) {        // Skipped
            log_message('error', 'AcceptedUrlNotDefinedException caught!');
            log_message('error', $e);
            $responseErrorMessage = $this->coembedbasicerrorresponsegeneratorimpl->generate_500_server_issues(self::ALLOWED_URL_SCHEMA);
            $this->output->set_output(json_encode($responseErrorMessage->getData()));
            return;
        }


        try {
            $url_without_expected_part = $this->cpinterestimpl->remove_expected_part($input_url);
        } catch (AcceptedUrlNotDefinedException $ee) {        // Skipped
            log_message('error', 'AcceptedUrlNotDefinedException caught!');
            log_message('error', $ee);
            $responseErrorMessage = $this->coembedbasicerrorresponsegeneratorimpl->generate_500_server_issues(self::ALLOWED_URL_SCHEMA);
            $this->output->set_output(json_encode($responseErrorMessage->getData()));
            return;
        }

        log_message('debug', 'Trimmed url:' . print_r($url_without_expected_part, TRUE));

        $slash_position = strpos($url_without_expected_part, "/");


        if ($slash_position === false) {
            log_message('debug', 'No slash at the rest of the trimmed URL. Expected ID');
        } else {
            log_message('debug', 'Slash found at position:' . $slash_position);

            $url_without_expected_part = substr($url_without_expected_part, 0, $slash_position);
            log_message('debug', 'Parsed part before slash:' . $url_without_expected_part);
        }

        if (!is_numeric($url_without_expected_part)) {
            log_message('debug', 'Expected numeric product ID but not-numeric value found!');
            $responseErrorMessage = $this->coembedbasicerrorresponsegeneratorimpl->generate_404_not_found(self::ALLOWED_URL_SCHEMA);
            $this->output->set_output(json_encode($responseErrorMessage->getData()));
            return;
        }

        $product_id = $url_without_expected_part;

        log_message('info', 'Loading data for product with ID:' . $product_id);

        // fetching the data
        $product_def_inst = $this->product_definition_model->get($product_id);

        if (!isset($product_def_inst) || empty($product_def_inst)) {
            $responseErrorMessage = $this->coembedbasicerrorresponsegeneratorimpl->generate_404_not_found(self::ALLOWED_URL_SCHEMA);
            $this->output->set_output(json_encode($responseErrorMessage->getData()));
            return;
        }

        // setting up data through the helper
        try {
            $responseObject = $this->_prepare_data($product_def_inst);
        } catch (CInvalidRequiredOEmbedKeyException $noeke) {
            log_message('error', 'Data preparation failed:' . $noeke);
            $responseErrorMessage = $this->coembedbasicerrorresponsegeneratorimpl->generate_500_server_issues(self::ALLOWED_URL_SCHEMA);
            $this->output->set_output(json_encode($responseErrorMessage->getData()));
            return;
        } catch(CInvalidRequiredPinKeyException $irpke){
            log_message('error', 'Data preparation failed:' . $irpke);
            $responseErrorMessage = $this->coembedbasicerrorresponsegeneratorimpl->generate_500_server_issues(self::ALLOWED_URL_SCHEMA);
            $this->output->set_output(json_encode($responseErrorMessage->getData()));
            return;
        } catch(CPinterestException $pe ){
            log_message('error', 'General Pinterest wrapper exception caught:' . $pe);
            $responseErrorMessage = $this->coembedbasicerrorresponsegeneratorimpl->generate_500_server_issues(self::ALLOWED_URL_SCHEMA);
            $this->output->set_output(json_encode($responseErrorMessage->getData()));
            return;
        } catch(OEmbedException $oee ){
            log_message('error', 'General oEmbed wrapper exception caught:' . $oee);
            $responseErrorMessage = $this->coembedbasicerrorresponsegeneratorimpl->generate_500_server_issues(self::ALLOWED_URL_SCHEMA);
            $this->output->set_output(json_encode($responseErrorMessage->getData()));
            return;            
        }

        // preparing the data to be encoded
        $encoded_response = json_encode($responseObject->getData());

        // sending the response
        $this->output->set_output($encoded_response);
    }

    private function _prepare_data($product_definition) {

        $responseMessage = new OEmbedResponseMessage(array());
        
        // adding obligatory (required) keys and values
        try {
            $responseMessage = $this->cpinterestimpl->add_oembed_required_response_items(
                    $responseMessage, array(
                ICOEmbedRequiredResponseKeyConstants::OERC_TYPE => ICOEmbedTypeValueConstants::COETVC_LINK, // required
                ICOEmbedRequiredResponseKeyConstants::OERC_VERSION => ICOEmbedVersionValueConstants::COEVVC_VERSION // required
                    ));
        } catch (CInvalidRequiredOEmbedKeyException $noeke) {
            // throw because we cannot follow the API rules
            throw $noeke;
        }

        // adding optional (defined as optional in OEmbed specification) keys and values        
        try {
            $responseMessage = $this->cpinterestimpl->add_oembed_optional_response_items(
                    $responseMessage, array(
                ICOEmbedOptionalResponseKeyConstants::OERC_PROVIDER_NAME => self::PP_PROVIDER_NAME, // optional
                ICOEmbedOptionalResponseKeyConstants::OERC_PROVIDER_URL => site_url(), // optional
                ICOEmbedOptionalResponseKeyConstants::OERC_CACHE_AGE => self::CACHE_AGE, // optional
                ICOEmbedOptionalResponseKeyConstants::OERC_TITLE => "In The Powporn Store: " . $product_definition->pd_product_name, // optional
                ICOEmbedOptionalResponseKeyConstants::OERC_AUTHOR_NAME => self::PP_STORE_NAME, // optional 
                ICOEmbedOptionalResponseKeyConstants::OERC_AUTHOR_URL => site_url("welcome"), // optional
                ICOEmbedOptionalResponseKeyConstants::OERC_THUMBNAIL_URL => base_url($product_definition->pd_photo_url), // optional
                ICOEmbedOptionalResponseKeyConstants::OERC_THUMBNAIL_WIDTH => 195, // optional
                ICOEmbedOptionalResponseKeyConstants::OERC_THUMBNAIL_HEIGHT => "*" // optional
                    ));
        } catch (CInvalidOptionalOEmbedKeyException $noeke) {
            // do NOT throw because we can follow the API rules
            log_message('error', 'Exception caught setting optional keys and values for OEmbed:' . $noeke);
        }

        // adding pinterest required keys and values
        try {
            $responseMessage = $this->cpinterestimpl->add_pinterest_required_response_items(
                    $responseMessage, array(
                ICPinRequiredResponseKeyConstants::PRRKC_URL => site_url(self::URI_SEGMENT_TO_PRODUCTS_PREVIEW . $product_definition->pd_id),
                ICPinRequiredResponseKeyConstants::PRRKC_TITLE => "In The Powporn Store: " . $product_definition->pd_product_name, // optional                    
                ICPinRequiredResponseKeyConstants::PRRKC_PRICE => $product_definition->pd_price,
                ICPinRequiredResponseKeyConstants::PRRKC_CURRENCY_CODE => "EUR"
                    ));
        } catch (CInvalidRequiredPinKeyException $irpke) {
            log_message('error', 'Exception caught setting optional keys and values for OEmbed:' . $noeke);
            // throw because we cannot follow the API rules
            throw $irpke;
        }

        if ($product_definition->pd_sex == 'female') {
            $product_for_gender = ICPinGenderValueConstants::PGVC_FEMALE;
        } else if ($product_definition->pd_sex == 'male') {
            $product_for_gender = ICPinGenderValueConstants::PGVC_MALE;
        } else {
            $product_for_gender = ICPinGenderValueConstants::PGVC_UNISEX;
        }


        // adding own keys and values  
        try{
        $this->cpinterestimpl->add_pinterest_optional_response_items(
                $responseMessage, array(
            ICPinOptionalResponseKeyConstants::PORKC_DESCRIPTION => $product_definition->pd_type,
            ICPinOptionalResponseKeyConstants::PORKC_PRODUCT_ID => $product_definition->pd_id,
            ICPinOptionalResponseKeyConstants::PORKC_AVAILABILITY => ICPinAvailabilityValueConstants::PAVC_PREORDER,
            ICPinOptionalResponseKeyConstants::PORKC_STANDARD_PRICE => $product_definition->pd_price,
            ICPinOptionalResponseKeyConstants::PORKC_GENDER => $product_for_gender,
            ICPinOptionalResponseKeyConstants::PORKC_IMAGES => base_url($product_definition->pd_photo_url)
                )
        );
        } catch (CInvalidOptionalPinKeyException $noeke) {
            // do NOT throw because we can follow the API rules            
            log_message('error', 'Exception caught setting optional keys and values for OEmbed:' . $noeke);
        }
        
        // 
        $responseMessage = $this->cpinterestimpl->add_pinterest_any_response_items(
                $responseMessage, array(
            //"quantity" => 1,
            "category" => self::PP_CATEGORY
                //"tags" => array("powporn", "hoodie"),
                //"materials" => "cotton"
                ));

       // $responseObject = new OEmbedResponseMessage($rsp);
        
        return $responseMessage;
    }

}

/* End of file c_services.php */
/* Location: ./application/controllers/c_services.php */