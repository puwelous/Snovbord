<!-- content -->
<div class="content_unextended">
    <div class="preview_center">
        <div class="preview_item">
            <div class="preview_item_imgs_wrapper">
                <?php
                $representation_urls = $product_screen_representation->getUrls();
                foreach ($representation_urls as $representation_url_item) {
                    $image_properties = array(
                        'src' => $representation_url_item,
                        'alt' => 'Product photo',
                        'class' => 'product_image'
                    );
                    echo img($image_properties);
                }
                ?>
            </div>
        </div>       
    </div>
</div><!-- end of content-->
