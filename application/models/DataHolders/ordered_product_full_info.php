<?php

class Ordered_product_full_info {
    
    private $orderedProductId;
    private $orderedProductCount;
    private $possibleSizeForProductName;
    private $productId;
    private $productName;
    private $productPrice;
    private $creatorNick;
    
    private $product_screen_representation;
 
    /* basic constructor */

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

    public function getOrderedProductId(){
        return $this->orderedProductId;
    }
    
    public function getOrderedProductCount(){
        return $this->orderedProductCount;
    }    
    
    public function getPossibleSizeForProductName(){
        return $this->possibleSizeForProductName;
    }    
    
    public function getProductId(){
        return $this->productId;
    }

    public function getProductName(){
        return $this->productName;
    }
    
    public function getProductPrice(){
        return $this->productPrice;
    }
    
    public function getCreatorNick(){
        return $this->creatorNick;
    } 
    
    public function getProductScreenRepresentation(){
        return $this->product_screen_representation;
    }
    /*     * ********* setters *********** */

    public function setProductScreenRepresentation(Product_screen_representation $newProductScreenRepresentation ){
        $this->product_screen_representation = $newProductScreenRepresentation;
    }
}

/* End of file product_model.php */
/* Location: ./application/models/product_model.php */
