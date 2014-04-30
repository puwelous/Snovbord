<!-- content -->
<div class="content_unextended">
    <div class="preview_center">
        <div class="preview_item">
            <div class="preview_item_imgs_wrapper">
                <?php
                    $image_properties = array(
                        'src' => $product->getPhotoUrl(),
                        'alt' => $product->getName(),
                        'class' => 'product_image'
                    );
                    echo img($image_properties);
                ?>
            </div>
        </div>       
    </div>
</div><!-- end of content-->
