<?php

/**
 * Dataholder class representing product on the screen
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Product_screen_representation {

    /**
     *
     * @var int $product_id
     *  ID of the product
     */
    private $product_id;
    /**
     *
     * @var string $product_name
     *  Name of the product 
     */
    private $product_name;
    /**
     *
     * @var array $urls
     *  Array of URL addresses with product raster representations
     */
    private $urls;

    /**
     * Constructor.
     * 
     * @param int $productId
     *  ID of the product
     * @param string $productName
     *  Name of the product 
     * @param array $urls
     *  Array of URL addresses with product raster representations
     */
    public function __construct($productId, $productName, $urls) {
        $this->product_id = $productId;
        $this->product_name = $productName;
        $this->urls = $urls;
    }

    /**
     * Getter for product ID
     * @return int
     *  ID of the product
     */
    public function getProductId() {
        return $this->product_id;
    }

    /**
     * Getter for product name
     * @return string
     *  Name of the product
     */
    public function getProductName() {
        return $this->product_name;
    }

    /**
     * Getter for product raster representations
     * @return array
     *  Array of product raster representations
     */
    public function getUrls() {
        return $this->urls;
    }

}
?>
