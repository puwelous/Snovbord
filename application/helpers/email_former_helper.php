<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


if ( !function_exists('create_registration_email_body_accor_user') ) {

    function create_registration_email_body_accor_user(User_model $user) {
        
        $message_body  = 'Dear ' . $user->nick . '!' . "\r\n";
        $message_body .= 'Thank you for Your registration!' . "\r\n";
        $message_body .= 'Here are your registration details:' . "\r\n";
        $message_body .= '-----------------------------------' . "\r\n";
        $message_body .= 'Nick: ' . $user->nick . "\r\n";
        $message_body .= 'Firstname: ' . $user->firstname . "\r\n";
        $message_body .= 'Lastname: ' . $user->lastname . "\r\n";
        $message_body .= 'Email: ' . $user->emailAddress . "\r\n";
//        $message_body .= 'Password: ' . $user->password . "\r\n";
        $message_body .= 'Gender: ' . ($user->gender == 0 ? 'Male' : 'Female') . "\r\n";
        if( is_null($user->deliveryAddress) || empty($user->deliveryAddress) ) {
            $message_body .= 'Delivery address not specified.' . "\r\n";
        } else{
            $message_body .= 'Delivery address: ' . $user->deliveryAddress . "\r\n";
        }
        $message_body .= 'Address: ' . $user->address . "\r\n";
        $message_body .= 'City: ' . $user->city . "\r\n";
        $message_body .= 'Zip: ' . $user->zip . "\r\n";
        $message_body .= 'Country: ' . $user->country . "\r\n";
        $message_body .= '-----------------------------------' . "\r\n";

        $message_body .= "\r\n" . 'If you forget your password You can regenerate it anytime.' . "\r\n";
        
        $message_body .= "\r\n" . 'Regards,' . "\r\n" . 'Your PowPorn team';
        
        return $message_body;
    }

}

if ( !function_exists('create_password_reset_email_body') ) {

    function create_password_reset_email_body( $user, $password) {
        
        $message_body  = 'Dear ' . $user->u_nick . '!' . "\r\n";
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