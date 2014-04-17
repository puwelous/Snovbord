<?php

/**
 * Interface declaring 3 basic getter methods for retrieving basic PayPal user settings in order to reach PayPal API:
 * - user name
 * - user password
 * - user signature
 * 
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
interface ICPayPalUserSettingsHolder {

    /**
     * Getter for PayPal user name.
     */
    public function get_paypal_user_name();

    /**
     * Getter for PayPal user password.
     */
    public function get_paypal_user_password();

    /**
     * Getter for PayPal user signature.
     */    
    public function get_paypal_user_signature();
}
?>
