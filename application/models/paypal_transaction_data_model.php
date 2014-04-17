<?php

class Paypal_transaction_data_model extends MY_Model {

    public $_table = 'pp_paypal_transaction_data';
    public $primary_key = 'ptd_order_id';
    
    public $orderId;
    public $transactionId;
    public $transactionType;
    public $paymentType;
    public $orderTime;
    public $amt;
    public $currencyCode;
    public $taxAmt;
    public $paymentStatus;
    public $pendingReason;
    public $reasonCode;
    public $ack;

    //public $protected_attributes = array( 'u_id' );

    public function __construct() {
        parent::__construct();
    }

    public function instantiate(
    $orderId, $transactionId, $transactionType, $paymentType, $orderTime, $amt, $currencyCode, $taxAmt, $paymentStatus, $pendingReason, $reasonCode, $ack) {

        $this->orderId = $orderId;
        $this->transactionId = $transactionId;
        $this->transactionType = $transactionType;
        $this->paymentType = $paymentType;
        $this->orderTime = $orderTime;
        $this->amt = $amt;
        $this->currencyCode = $currencyCode;
        $this->taxAmt = $taxAmt;
        $this->paymentStatus = $paymentStatus;
        $this->pendingReason = $pendingReason;
        $this->reasonCode = $reasonCode;
        $this->ack = $ack;
    }

    public function insert_paypal_transaction_data() {

        return $this->paypal_transaction_data_model->insert(
                array(
                    'ptd_order_id' => $this->orderId,
                    'transaction_id' => $this->transactionId,
                    'transaction_type' => $this->transactionType,
                    'payment_type' => $this->paymentType,
                    'order_time' => $this->orderTime,
                    'amt' => $this->amt,
                    'currency_code' => $this->currencyCode,
                    'tax_amt' => $this->taxAmt,
                    'payment_status' => $this->paymentStatus,
                    'pending_reason' => $this->pendingReason,
                    'reason_code' => $this->reasonCode,
                    'ack' => $this->ack
                    
        ));
    }

}

/* End of file company_model.php */
/* Location: ./application/models/company_model.php */
