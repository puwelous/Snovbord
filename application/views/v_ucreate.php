<!-- content -->
<script>
    $(document).ready(function(){

        $(".component_item").click( function(){
            
            var cmp_identity = $(this).data("identity").toString();
             
            var cmp_is_allowed_multiple = ( $(this).data("multiple").toString() === 'true' );
            
            if ( !cmp_is_allowed_multiple ){
   
                var already_existing_obj = $('#ucreate_center img[data-id="'+cmp_identity+'"]');
                //alert(obj.html());
                if( already_existing_obj.length ){
                    alert('Cannot add this object again!');
                    return;
                }
            }
            
            var cmp_src = $(this).data("src");
           
            
            //alert(img_src);

            var img = $('<img class="ucreate_image">'); //Equivalent: $(document.createElement('img'))
            
            img.attr('src', cmp_src);
            img.attr('data-id', cmp_identity);
            
            
            img.appendTo('#ucreate_center');
        });
    });
</script>

<div class="content_unextended">

    <div class="preview_left">

        <div class="line pp_blue"></div>

        <?php
//        $attributes = array('name' => 'pp_add_product_to_cart_form');
//        echo form_open("c_preview/add_to_cart/" . $previewed_product->pd_id, $attributes);
//        echo form_hidden('product_id', $previewed_product->pd_id);
        ?>

        <h2>
            <section contenteditable="true"><?php echo $previewed_product->pd_product_name; ?></section>
        </h2>
        <div class="line pp_dark_gray"></div>

        <!--<div class="text_medium upper_cased pp_dark_gray">design by</div>-->
        <h4 class="pp_dark_gray">design by</h4>
        <!--<div id="pr_l_creator" class="text_medium upper_cased black">kajsiman</div>-->
        <h4 class="black"><?php echo $previewed_product_creator->u_nick; ?></h4>
        <div class="line pp_dark_gray"></div>

        <!--<div class="text_medium upper_cased pp_dark_gray">type</div>-->
        <h4 class="pp_dark_gray">type</h4>
        <h5 class="lower_cased">
            <section contenteditable="true"><?php echo $previewed_product->pd_type; ?></section>
        </h5>
        <div class="line pp_dark_gray"></div>
        <div class="pr_l_sex_icon"></div>
        <h4><?php echo $previewed_product->pd_sex; ?></h4>
        <div class="line"></div>

        <h1><?php echo $previewed_product->pd_price; ?>&nbsp;&euro;</h1>

        <button id="add_to_cart_button" type="submit">ADD TO CART</button>
        <?php //echo form_close(); ?>
    </div>

    <div id="ucreate_center">
        <?php
        $anchor_url = base_url($previewed_product->pd_photo_url);
        $image_properties = array(
            'src' => $previewed_product->pd_photo_url,
            'alt' => $previewed_product->pd_product_name,
//            'width' => '245',
//            'height' => '355',
            'class' => 'ucreate_image',
            'title' => $previewed_product->pd_product_name
        );
        //$img_data = img($image_properties);
        echo img($image_properties);
        ?>        
    </div>

    <div id="ucreate_right">
        <div class="component_item" 
             data-src="<?php echo base_url('./assets/images/products/components/component_test_k_so_snurkami.png'); ?>"
             data-identity="1"
             data-price="10"
             data-multiple="false"
             >
            kapucna
        </div>
        <div class="component_item" 
             data-src="<?php echo base_url('./assets/images/products/components/component_test_ruky.png'); ?>"
             data-identity="2"
             data-price="15"
             data-multiple="false"
             >
            ruky
        </div>
    </div>

</div><!-- end of content-->
