
<script src="<?php echo base_url(); ?>assets/javascript/finalproducts.js" text='text/javascript'></script>


<div class="gallery_gradient_wrapper">
    <div class="gallery_gradient right">
    </div>
    <div class="gallery_gradient left">
    </div>                 
</div> 

<!-- content -->
<div id="content">


    <!-- Title -->
    <div id="gallery_title" class="title upper_cased pp_light_gray">
        already made models
    </div>            

    <!-- products gallery -->
    <!-- image wrapper -->
    <div id="gallery_wrapper">

        <div  class="gallery_row_wrapper">
            <?php
            if (count($products_list) % 2 == 0) {
                //even;
                $count_per_row = ( count($products_list) / 2);
            } else {
                //odd
                $count_per_row = ( count($products_list) / 2);
            }
            for ($i = 0; $i < $count_per_row; ++$i):
                ?>
                <div class="gallery_item">
                    <a href="images/klematis_big.htm">
                        <?php
                        $image_properties = array(
                            'src' => $products_list[$i]->pd_photo_url,
                            'alt' => $products_list[$i]->pd_product_name
                        );
                        echo img($image_properties);
                        ?>
                    </a>
                    <div class="gallery_item_desc">
                        <div class="gallery_item_name text_light bold upper_cased"><?php echo $products_list[$i]->pd_product_name; ?></div>
                        <?php echo anchor('preview/show/' . $products_list[$i]->pd_id, 'preview', array('class' => 'gallery_item_option_items text_light smaller upper_cased pp_red')); ?>                        
                        <?php echo anchor('ucreate/' . $products_list[$i]->pd_id, 'edit', array('class' => 'gallery_item_option_items text_light smaller upper_cased pp_red')); ?>
                        <!--<span class="gallery_item_option_items text_light smaller upper_cased pp_red">preview</span>-->
                        <!--<span class="gallery_item_option_items text_light smaller upper_cased pp_red">order</span>-->                           
                    </div>
                </div>

            <?php endfor; ?>  
        </div>
        <div  class="gallery_row_wrapper">
            <?php
            if (count($products_list) % 2 == 0) {
                //even;
                $count_per_row = ( count($products_list) / 2);
            } else {
                //odd
                $count_per_row = ( count($products_list) / 2) + 1;
            }
            for ($i = $count_per_row; $i < count($products_list); ++$i):
                ?>
                <div class="gallery_item">
                    <a href="images/klematis_big.htm">
                        <?php
                        $image_properties = array(
                            'src' => $products_list[$i]->pd_photo_url,
                            'alt' => $products_list[$i]->pd_product_name
                        );
                        echo img($image_properties);
                        ?>
                    </a>
                    <div class="gallery_item_desc">
                        <div class="gallery_item_name text_light bold upper_cased"><?php echo $products_list[$i]->pd_product_name; ?></div>
                        <!--<span class="gallery_item_option_items text_light smaller upper_cased pp_red">edit</span>-->
                         <?php echo anchor('preview/show/' . $products_list[$i]->pd_id, 'preview', array('class' => 'gallery_item_option_items text_light smaller upper_cased pp_red')); ?>                        
                        <?php echo anchor('ucreate/' . $products_list[$i]->pd_id, 'edit', array('class' => 'gallery_item_option_items text_light smaller upper_cased pp_red')); ?>
                        <!--<span class="gallery_item_option_items text_light smaller upper_cased pp_red">preview</span>-->
                        <!--<span class="gallery_item_option_items text_light smaller upper_cased pp_red">order</span>-->                            
                    </div>
                </div>

            <?php endfor; ?>            
        </div>

    </div>
</div><!-- end of content-->

