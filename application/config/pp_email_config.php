<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Settings for email sending purposes.
 */
$config['protocol']         = 'smtp';
$config['smtp_host']        = 'smtp.puwel.sk';
$config['smtp_user']        = 'powporn@puwel.sk';
$config['smtp_pass']        = 'kajsy123';
$config['smtp_port']        = '25';
$config['charset']          = 'utf-8';
$config['crlf']             = '\r\n';
$config['newline']          = '\r\n';
$config['wordwrap']         = TRUE;

/*
 * Defined settings not directly necessary for email sending. Basicly flexibile.
 */
$config['powporn_sending_email_sender']                 = 'PowPorn';

$config['powporn_sending_email_reg_subject']            = 'Thanks for a registration!';
$config['powporn_sending_email_pass_reset_subject']     = 'Password reset';

/* End of file pp_email_config.php */
/* Location: ./application/config/pp_email_config.php */
