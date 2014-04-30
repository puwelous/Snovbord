<!-- content -->
<div id="content">
    <div class="content_wrapper">
        <!-- ******************* shopping cart section ******************* -->
        <div class="container">
            <!-- Title -->
            <h1>
                Product detail
                <?php echo anchor('c_graphic/components_graphic', '<-go back', array('class' => 'text_light smaller pp_dark_gray inunderlined red_on_hover inunderlined upper_cased')); ?>                 
            </h1>
            <div class="blue_line">
            </div>
            <?php
            $image_properties = array(
                'src' => $component_raster_model->getPhotoUrl(),
                'alt' => 'Component photo',
                'class' => 'product_image'
            );
            echo img($image_properties);
            ?>        
        </div>
    </div><!-- end of content-->