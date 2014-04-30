<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


if ( !function_exists('create_registration_email_body_accor_user') ) {

    /**
     * Creates a body of a email according to given user as an agument.
     * Retrievs basic information about user and sends it as an email.
     * 
     * 
     * @param User_model $user
     *  User to be described.
     * @retval string
     *  Body of an email.
     */
    function create_registration_email_body_accor_user(User_model $user) {
        
        $message_body  = 'Dear ' . $user->getNick() . '!' . "\r\n";
        $message_body .= 'Thank you for Your registration!' . "\r\n";
        $message_body .= 'Here are your registration details:' . "\r\n";
        $message_body .= '-----------------------------------' . "\r\n";
        $message_body .= 'Nick: ' . $user->getNick() . "\r\n";
        $message_body .= 'Firstname: ' . $user->getFirstName() . "\r\n";
        $message_body .= 'Lastname: ' . $user->getLastName() . "\r\n";
        $message_body .= 'Email: ' . $user->getEmailAddress() . "\r\n";
//        $message_body .= 'Password: ' . $user->password . "\r\n";
        $message_body .= 'Gender: ' . ($user->getGender() == 0 ? 'Male' : 'Female') . "\r\n";
//        if( is_null($user->get) || empty($user->deliveryAddress) ) {
//            $message_body .= 'Delivery address not specified.' . "\r\n";
//        } else{
//            $message_body .= 'Delivery address: ' . $user->deliveryAddress . "\r\n";
//        }
//        $message_body .= 'Address: ' . $user->address . "\r\n";
//        $message_body .= 'City: ' . $user->city . "\r\n";
//        $message_body .= 'Zip: ' . $user->zip . "\r\n";
//        $message_body .= 'Country: ' . $user->country . "\r\n";
        $message_body .= '-----------------------------------' . "\r\n";

        $message_body .= "\r\n" . 'If you forget your password You can regenerate it anytime.' . "\r\n";
        
        $message_body .= "\r\n" . 'Regards,' . "\r\n" . 'Your PowPorn team';
        
        return $message_body;
    }

}

if ( !function_exists('create_password_reset_email_body') ) {

    /**
     * Creates body for password reseting email.
     * 
     * @param User_model $user
     *  User who asked for passowrd reset.
     * @param string $password
     *  Freshly generated password
     * @retval string
     * Reset password email body.
     */
    function create_password_reset_email_body( User_model $user, $password) {
        
        $message_body  = 'Dear ' . $user->getNick() . '!' . "\r\n";
        $message_body .= 'You have asked for a password reset!' . "\r\n";
        $message_body .= '-----------------------------------' . "\r\n";
        $message_body .= 'Here is your new password:'. $password . "\r\n";
        $message_body .= '-----------------------------------' . "\r\n";

        $message_body .= "\r\n" . 'If you forget your password again You can regenerate it anytime.' . "\r\n";
        
        $message_body .= "\r\n" . 'Regards,' . "\r\n" . 'Your PowPorn team';
        
        return $message_body;
    }

}


/* End of file email_former_helper.php */
/* Location: ./application/helpers/email_former_helper.php */