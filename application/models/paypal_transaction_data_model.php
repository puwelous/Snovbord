<?php

/**
 * Model class representing PayPal transaction data.
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Paypal_transaction_data_model extends MY_Model {

    /**
     * @var string $_table
     *  Name of a database table. Used for CRUD abstraction in MY_Model class
     */    
    public $_table = 'sb_paypal_transaction_data';
    /**
     * @var string $primary_key
     *  Primary key in database schema for current table
     */    
    public $primary_key = 'ptd_order_id';
    
    /**
     *
     * @var int $orderId
     *  Order ID
     */
    public $orderId;
    
    /**
     *
     * @var int $transactionId
     *  Transaction ID
     */
    public $transactionId;
    /**
     *
     * @var string $transactionType
     *  Type of a transaction
     */
    public $transactionType;
    /**
     *
     * @var string $paymentType
     *  Type of a payment
     */
    public $paymentType;
    /**
     *
     * @var string $orderTime
     *  Order time of a payment
     */
    public $orderTime;
    /**
     *
     * @var double $amt
     *  Amount of products
     */
    public $amt;
    /**
     *
     * @var string $currencyCode
     * Currency code as three characters long string
     */
    public $currencyCode;
    /**
     *
     * @var double $taxAmt
     * Taxes amount
     */
    public $taxAmt;
    /**
     *
     * @var string $paymentStatus
     *  Status of the payment
     */
    public $paymentStatus;
    /**
     *
     * @var string $pendingReason
     *  Pending reason of a payment
     */
    public $pendingReason;
    /**
     *
     * @var int $reasonCode
     *  Reasong code of a payment
     */
    public $reasonCode;
    /**
     *
     * @var string $ack
     *  Success description of a payment
     */
    public $ack;

    /**
     * Basic constructor calling parent CRUD abstraction layer contructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Constructor-like method for instantiating object of the class.
     * 
     * @param int $orderId
     *  Id of the order
     * @param int $transactionId
     *  Id of the transaction
     * @param string $transactionType
     *  Type of the transaction
     * @param string $paymentType
     *  Type of a payment
     * @param string $orderTime
     *  Time of order creation
     * @param double $amt
     *  Products amount
     * @param string $currencyCode
     *  Currency code. Three characters in a string
     * @param double $taxAmt
     *  Amount of a tax
     * @param string $paymentStatus
     *  Status of a payment
     * @param string $pendingReason
     *  Reasong for pending if any
     * @param int $reasonCode
     *  Reasong code if any
     * @param string $ack
     *  Acknowledgement - success status
     */
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

    /**
     * Inserts this object into a database. Database create operation
     * @return object
     *  NULL or object as a result of insertion
     */    
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

/* End of file paypal_transaction_data_model.php */
/* Location: ./application/models/paypal_transaction_data_model.php */
