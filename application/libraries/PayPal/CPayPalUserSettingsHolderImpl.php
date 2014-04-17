<?php

/**
 * Class representing PayPal user settings in order to reach PayPal API method calls.
 * User settings are as follows:
 * - user name
 * - user password
 * - user signature
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class CPayPalUserSettingsHolderImpl implements ICPayPalUserSettingsHolder {

    /**
     * @var string $paypal_user_name
     *  PayPal user name according to the settings.
     */
    private $paypal_user_name;
    
    /**
     * @var string $paypal_user_password
     *  PayPal user password according to the settings.
     */    
    private $paypal_user_password;
    
    /**
     * @var string $paypal_user_signature
     *  PayPal user signature according to the settings.
     */        
    private $paypal_user_signature;

    /**
     * Getter for PayPal user name.
     */    
    public function __construct(
    $paypalUserName, $payPalUserPassword, $payPalUserSignature
    ) {
        $this->paypal_user_name = $paypalUserName;
        $this->paypal_user_password = $payPalUserPassword;
        $this->paypal_user_signature = $payPalUserSignature;
    }

    /**
     * Getter for PayPal user password.
     */    
    public function get_paypal_user_name() {
        return $this->paypal_user_name;
    }

    /**
     * Getter for PayPal user password.
     */    
    public function get_paypal_user_password() {
        return $this->paypal_user_password;
    }

    /**
     * Getter for PayPal user signature.
     */  
    public function get_paypal_user_signature() {
        return $this->paypal_user_signature;
    }
}

?>
