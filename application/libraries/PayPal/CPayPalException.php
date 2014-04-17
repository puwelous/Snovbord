<?php

require_once('CPayPalException.php');

/**
 * Generalized exception used in any special case when PayPal rules 
 * are violated or any other problem occurs.
 * 
 * @author Pavol Daňo
 * @version 1.0 
 * @file
 */
class CInvalidPayPalArgumentException extends CPayPalException {

}

?>
