<?php

/**
 * Dataholder class representing ordered product including full information
 * 
 * @author Pavol Daňo
 * @version 1.0
 * @file
 */
class Ordered_product_full_info {
    
    /**
     *
     * @var int $orderedProductId
     *  Ordered product ID
     */
    private $orderedProductId;
    /**
     *
     * @var int $orderedProductCount
     *  Ordered product count
     */
    private $orderedProductCount;
    /**
     *
     * @var string $possibleSizeForProductName
     *  Possible size name
     */
    private $possibleSizeForProductName;
    /**
     *
     * @var int $productId
     *  Product ID
     */
    private $productId;
    /**
     *
     * @var string $productName
     *  Product name
     */
    private $productName;
    /**
     *
     * @var double $productPrice
     * Product price
     */
    private $productPrice;
    /**
     *
     * @var string $creatorNick
     *  Creator nick
     */
    private $creatorNick;
    
    /**
     *
     * @var object $product_screen_representation
     *  Product screen representation
     */
    private $product_screen_representation;
 
    /**
     * Constructor.
     * 
     * @param int $orderedProductId
     *  Ordered product ID
     * @param int $orderedProductCount
     *  Ordered product count
     * @param string $possibleSizeForProductName
     *  Possible size name
     * @param int $productId
     *  Product ID
     * @param string $productName
     *  Product name
     * @param double $productPrice
     *  Product price
     * @param string $creatorNick
     *  Creator nick
     * @param object $productScreenRepresentation
     *  Product screen representation
     */
    public function __construct( $orderedProductId, $orderedProductCount, $possibleSizeForProductName, $productId, $productName, $productPrice, $creatorNick, $productScreenRepresentation ) {

        $this->orderedProductId = $orderedProductId;
        $this->orderedProductCount = $orderedProductCount;
        $this->possibleSizeForProductName = $possibleSizeForProductName;
        $this->productId = $productId;
        $this->productName = $productName;
        $this->productPrice = $productPrice;
        $this->creatorNick = $creatorNick;
        $this->product_screen_representation = $productScreenRepresentation;
    }

 
    /*     * ********* getters *********** */

    /**
     * Getter for ordered product ID
     * @return int
     * ID of ordered product
     */
    public function getOrderedProductId(){
        return $this->orderedProductId;
    }
    
    /**
     * Getter for ordered product count
     * @return int
     *  Count of ordered products
     */
    public function getOrderedProductCount(){
        return $this->orderedProductCount;
    }    
    
    /**
     * Getter for possible size name for product
     * @return String
     *  Name of possible size for product
     */
    public function getPossibleSizeForProductName(){
        return $this->possibleSizeForProductName;
    }    
    
    /**
     * Getter for product ID
     * @return int
     *  ID of the product
     */
    public function getProductId(){
        return $this->productId;
    }

    /**
     * Getter for product name
     * @return string
     *  Name of the product
     */
    public function getProductName(){
        return $this->productName;
    }
    
    /**
     * Getter for product price
     * @return double
     *  Price of the product
     */
    public function getProductPrice(){
        return $this->productPrice;
    }
    
    /**
     * Getter for creator nick
     * @return string
     *  Nick of the product creator
     */
    public function getCreatorNick(){
        return $this->creatorNick;
    } 
    
    /**
     * Getter for product screen representations
     * @return object
     *  Product screen representation
     */
    public function getProductScreenRepresentation(){
        return $this->product_screen_representation;
    }
    /*     * ********* setters *********** */

    /**
     * Setter for product screen representations
     * @param Product_screen_representation $newProductScreenRepresentation
     *  New product screen representations
     */
    public function setProductScreenRepresentation(Product_screen_representation $newProductScreenRepresentation ){
        $this->product_screen_representation = $newProductScreenRepresentation;
    }
}

/* End of file ordered_product_full_info.php */
/* Location: ./application/models/ordered_product_full_info.php */
