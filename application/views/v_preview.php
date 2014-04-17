<script src="<?php echo base_url(); ?>assets/javascript/jquery-1.6.js" text='text/javascript'></script>
<script src="<?php echo base_url(); ?>assets/javascript/jquery.jqzoom-core.js" text='text/javascript'></script>

<script type="text/javascript">
    $(document).ready(function(){
        var options = {  
            zoomType: 'standard',  
            lens:true,  
            preloadImages: true,  
            alwaysOn:false,  
            zoomWidth: 290,  
            zoomHeight: 500,  
            xOffset: 170,  
            yOffset: 0,  
            position:'left'  
            //...MORE OPTIONS  
        };  
        $('.MYCLASS').jqzoom(options); 
        if (document.addEventListener) {
            // IE9, Chrome, Safari, Opera
            document.addEventListener("mousewheel", MouseWheelHandler, false);
            // Firefox
            document.addEventListener("DOMMouseScroll", MouseWheelHandler, false);
        }
        // IE 6/7/8
        else document.attachEvent("onmousewheel", MouseWheelHandler);

        var defaultAnimationStep = 100;
        //var animationStep = 100;

        function MouseWheelHandler(e) {

            // cross-browser wheel delta
            var e = window.event || e; // old IE support
            //var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));
            //alert('e.wheelDelta = ' + e.wheelDelta + '-e.detail = ' + (-e.detail));
            var delta = (e.wheelDelta || -e.detail);

            //$(".gallery_row_wrapper").style.width = Math.max(50, Math.min(800, myimage.width + (30 * delta))) + "px";
            //                    var x = $(".gallery_row_wrapper").offset().left;
            //                    x = x + delta;
            //                    $(".gallery_row_wrapper").css({left:x});
                    
            // check actual position of a row wrapper, in case of attempt to move left
            // further than gallery_wrapper' most left position, move it to the beginning
            if( $(".gallery_row_wrapper").offset().left + delta > 0 ){
                //alert( $(".gallery_row_wrapper").offset().left + ' ' + delta);
                //return false;
                delta = -$(".gallery_row_wrapper").offset().left ;
            }else if( $(".gallery_row_wrapper").width() + $(".gallery_row_wrapper").offset().left + delta + 2 <  $( window ).width() ){
                //delta = - 1 * ($(".gallery_row_wrapper").width() + $(".gallery_row_wrapper").offset().left - $( window ).width()) ;
                //alert( $(".gallery_row_wrapper").width() + ", " + $(".gallery_row_wrapper").offset().left);
                return false;
            }
                   
            if( $('.gallery_row_wrapper').is(':animated') ) {
                // animation is already running, ingore mouse wheel event
                //animationStep -= 50;
            }else{
                // animate wrapper
                $(".gallery_row_wrapper").animate({
                    left: ("+=" + delta)
                }, defaultAnimationStep);
                //animationStep = defaultAnimationStep;
            }
                    
            return false;
        }
    });
</script>

<!-- content -->
<div class="content_unextended">

    <div class="preview_left">

        <div class="line pp_red"></div>

        <?php
        $attributes = array('name' => 'pp_add_product_to_cart_form');
        echo form_open("c_preview/add_to_cart/" . $previewed_product->pd_id, $attributes);
        echo form_hidden('product_id', $previewed_product->pd_id);
        ?>
        
        <h2><?php echo $previewed_product->pd_product_name; ?></h2>
        <div class="line pp_dark_gray"></div>

        <!--<div class="text_medium upper_cased pp_dark_gray">design by</div>-->
        <h4 class="pp_dark_gray">design by</h4>
        <!--<div id="pr_l_creator" class="text_medium upper_cased black">kajsiman</div>-->
        <h4 class="black"><?php echo $previewed_product_creator->u_nick; ?></h4>
        <div class="line pp_dark_gray"></div>

        <!--<div class="text_medium upper_cased pp_dark_gray">type</div>-->
        <h4 class="pp_dark_gray">type</h4>
        <h5 class="lower_cased"><?php echo $previewed_product->pd_type; ?></h5>
        <div class="line pp_dark_gray"></div>
        <div class="pr_l_sex_icon"></div>
        <h4><?php echo $previewed_product->pd_sex; ?></h4>
        <h4>
            <?php
            echo form_dropdown('pdf_product_sizes', $previewed_product_size_options, reset($previewed_product_size_options));
            ?>
        </h4>
        <div class="line"></div>

        <h1><?php echo $previewed_product->pd_price; ?>&nbsp;&euro;</h1>
        
        <button id="add_to_cart_button" type="submit">ADD TO CART</button>
        <?php echo form_close(); ?>
    </div>

    <div class="preview_center">
        <?php
        $anchor_url = base_url($previewed_product->pd_photo_url);
        $image_properties = array(
            'src' => $previewed_product->pd_photo_url,
            'alt' => $previewed_product->pd_product_name,
            'width' => '500',
            'height' => '436',
            'class' => 'MYCLASS',
            'title' => $previewed_product->pd_product_name
        );
        $img_data = img($image_properties);
        //echo img($image_properties);
        echo anchor($anchor_url, $img_data, array('class' => 'MYCLASS', 'title' => $previewed_product->pd_product_name));
        ?>        
    </div> 

    <div class="preview_right">


    </div>

</div><!-- end of content-->
