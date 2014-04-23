<?php

class Product_screen_representation {

    private $product_id;
    private $product_name;
    private $urls;

    public function __construct($productId, $productName, $urls) {
        $this->product_id = $productId;
        $this->product_name = $productName;
        $this->urls = $urls;
    }

    public function getProductId() {
        return $this->product_id;
    }

    public function getProductName() {
        return $this->product_name;
    }

    public function getUrls() {
        return $this->urls;
    }

}
?>
