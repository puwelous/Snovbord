<!-- content -->
<script>
    $(document).ready(function(){
        var rasterCanvas = document.getElementById('raster_canvas');
        var vectorCanvas = document.getElementById('svg_canvas');
        var rasterCanvasContext = null;
        var vectorCanvasContext = null;
        
        if( rasterCanvas.getContext && vectorCanvas.getContext){
            rasterCanvasContext = rasterCanvas.getContext('2d');
            vectorCanvasContext = vectorCanvas.getContext('2d');
        }else{
            alert('Your browser does not support HTML5!');
        }

        $(".component_add").click( function(){

            var component = $(this).parent();

            var cmp_identity = component.data("identity").toString();

            var cmp_is_allowed_multiple = ( component.data("multiple").toString() === 'true' );

            if ( !cmp_is_allowed_multiple ){

                var already_existing_obj = $('#ucreate_image_section img[data-id="'+cmp_identity+'"]');
                //alert(obj.html());
                if( already_existing_obj.length ){
                    alert('Component has been already used in composition :)');
                    return;
                }
            }
            
            // add image to ucreate section
            var cmp_src = component.data("src");

            var img = $('<img class="ucreate_image">'); //Equivalent: $(document.createElement('img'))

            img.attr('src', cmp_src);
            img.attr('data-id', cmp_identity);
            img.attr('style', 'z-index: 3');


            img.appendTo('#ucreate_image_section');
            
            // add price to current price
            var cmp_price = parseFloat(component.data("price"));
            var actual_price = parseFloat($("#ucreate_price").html());
            actual_price += cmp_price;
            
            $("#ucreate_price").html(new Number(actual_price).toFixed(2));
            
            
            // append new element to applied components list
            const applied_components_list_item_id = "applied_components_list_item_" + cmp_identity;
            var new_applied_component_item = $('<div id=' + applied_components_list_item_id + ' class="applied_component_item">' + component.data("name") +'</div>');
            new_applied_component_item.attr('data-identity', cmp_identity);
            new_applied_component_item.attr('data-price', cmp_price);
            
            new_applied_component_item.appendTo("#applied_components_list");
             
            $( "#" + applied_components_list_item_id ).on( "click", function() {

                // turn all strokes on SVG paths
                $('#ucreate_vector_section svg path').css("stroke", "none");

                var existing_vector = $('#ucreate_vector_section svg path[data-id="'+$(this).data("identity")+'"]'); 
                if( existing_vector.length ){
                    existing_vector.css( "stroke", "black" );
                    existing_vector.css( "stroke-dasharray", "5,5" );
                }                 

                $('.applied_component_item').css("color","black");
                $(this).css("color","red");
                $('.applied_component_colour_possibilities').hide();

                var existing_colour_possibilities = $('.applied_component_colour_possibilities[data-identity="'+$(this).data("identity")+'"]'); 
                if( existing_colour_possibilities.length ){
                    //                    existing_vector.css( "stroke", "black" );
                    //                    existing_vector.css( "stroke-dasharray", "5,5" );
                    existing_colour_possibilities.show();
                }                 
            });
            
            // check if vector representation exists
            var existing_vector = $('#ucreate_vector_section svg path[data-id="'+$(this).data("identity")+'"]'); 
            if( existing_vector.length ){
                //                existing_vector.css( "stroke", "black" );
                //                existing_vector.css( "stroke-dasharray", "5,5" );
            }
            
            // display remove button
            component.children('.component_remove').show();
            
            // hide self
            $(this).hide();
            //$('#loading_gif').hide();
        }); 
        
        
        $(".component_remove").click( function(){
            //$('#loading_gif').show();

            var component = $(this).parent();

            var cmp_identity = component.data("identity").toString();

            //var cmp_is_allowed_multiple = ( component.data("multiple").toString() === 'true' );

            //            if ( !cmp_is_allowed_multiple ){
            //
            //                var already_existing_obj = $('#ucreate_image_section img[data-id="'+cmp_identity+'"]');
            //                //alert(obj.html());
            //                if( already_existing_obj.length ){
            //                    alert('Component has been already used in composition :)');
            //                    return;
            //                }
            //            }
            
            // add image to ucreate section
            //            var cmp_src = component.data("src");
            //
            //            var img = $('<img class="ucreate_image">'); //Equivalent: $(document.createElement('img'))
            //
            //            img.attr('src', cmp_src);
            //            img.attr('data-id', cmp_identity);
            //
            //
            //            img.appendTo('#ucreate_image_section');
            //            
            //            // add price to current price
            //            var cmp_price = parseFloat(component.data("price"));
            //            var actual_price = parseFloat($("#ucreate_price").html());
            //            actual_price += cmp_price;
            //            
            //            $("#ucreate_price").html(new Number(actual_price).toFixed(2));
            
            
            // append new element to applied components list
            //            const applied_components_list_item_id = "applied_components_list_item_" + cmp_identity;
            //            var new_applied_component_item = $('<div id=' + applied_components_list_item_id + ' class="applied_component_item">' + component.data("name") +'</div>');
            //            new_applied_component_item.attr('data-identity', cmp_identity);
            //            new_applied_component_item.attr('data-price', cmp_price);
            
            //            new_applied_component_item.appendTo("#applied_components_list");
             
            //$( "#" + applied_components_list_item_id ).on( "click", function() {
            // substract price
            var cmp_price_removing = parseFloat(component.data("price"));
            var actual_price = parseFloat($("#ucreate_price").html());
            actual_price -= cmp_price_removing;
            $("#ucreate_price").html(new Number(actual_price).toFixed(2));
                
            // remove img from ucreate center panel
            var already_existing_obj = $('#ucreate_image_section img[data-id="'+cmp_identity+'"]'); 
            if( already_existing_obj.length ){
                already_existing_obj.remove();
            }
                
            // change colour of related vector object to none
            var already_existing_vector = $('#ucreate_vector_section svg path[data-id="'+cmp_identity+'"]'); 
            if( already_existing_vector.length ){
                //already_existing_obj.remove();
                //alert('Exists!');
                already_existing_vector.css( "fill", "none" );
            }                
                
            // remove from applied components list
            //$(this).remove();
            const applied_components_list_item_id = "applied_components_list_item_" + cmp_identity;
            $( "#" + applied_components_list_item_id ).remove();
            //            });
            
            // check if vector representation exists
            //            var existing_vector = $('#ucreate_vector_section svg path[data-id="'+$(this).data("identity")+'"]'); 
            //            if( existing_vector.length ){
            //                //already_existing_obj.remove();
            //                //alert('Exists!');
            //                existing_vector.css( "fill", "red" );
            //            }
            
            // display remove button
            component.children('.component_add').show();
            
            // hide self
            $(this).hide();            
            
            //$('#loading_gif').hide();
        });        
        
        $(".applied_component_colour").click( function(){
            
            // set borders aka active element :P
            $(this).parent().children(".applied_component_colour").removeClass("selected");
            $(this).parent().children(".applied_component_colour").addClass("notselected");
            $(this).removeClass("notselected");
            $(this).addClass("selected");
            
            const colour = $(this).data('colour');
            const cmp_id = $(this).parent().data('identity');
            // check if vector representation exists
            var vector_representation = $('#ucreate_vector_section svg path[data-id="'+cmp_id+'"]'); 
            if( vector_representation.length ){
                vector_representation.css( "fill", colour );
            }
        });         
        
        $("#create_button").click( function(e){
            
            e.preventDefault();
            
            // set borders aka active element :P

            // basic product data
            var product_data = {
                product_id : $('input[name=edited_product_id]').val(),
                name : $("#ucreate_product_name").html(),
                price : parseFloat($("#ucreate_price").html()),
                description : $("#ucreate_description").html(),
                sex: $("#ucreate_sex").html(),
                creator_nick: $("#ucreate_creator_nick").html()
            };
            
            var applied_components_value_pairs = new Array();
            
            //var applied_components = $("#applied_components_list").children();
            $('#applied_components_list').children('div').each(function () {
                //alert($(this).data('identity')); // "this" is the current element in the loop
                //applied_component_ids.push($(this).data('identity'));
                var single_value_pair = {};
                // identity of the component
                single_value_pair['component_id'] = $(this).data('identity');
                
                // colour id of the component
                var component_colours_wrapper = $('.applied_component_colour_possibilities[data-identity="'+single_value_pair['component_id'].toString()+'"]');
                if( component_colours_wrapper.length ){
                    var selected_colour_element = component_colours_wrapper.children('.applied_component_colour.selected');
                    if( selected_colour_element.length ){
                        single_value_pair['component_colour_id'] = selected_colour_element.data('colour_id');
                    }else{
                        single_value_pair['component_colour_id'] = null;
                    }
                }else{
                    single_value_pair['component_colour_id'] = null;
                }
                
                applied_components_value_pairs.push( single_value_pair );
            });

            // applied components data
            var applied_components_data = {
                applied_components_value_pairs : applied_components_value_pairs
            };

            var picture;


            
            // render basic image
            const basic_image_src = $('#basic_image').attr('src');
            var newBasicImage = new Image();
            newBasicImage.src = basic_image_src;
            newBasicImage.onload = function() {
                vectorCanvasContext.drawImage( newBasicImage , 0, 0);
            };
            
            setTimeout(function(){
                // draw svg
                var xml = (new XMLSerializer).serializeToString( document.getElementById("svg_element") ); 
                canvg(vectorCanvas, svgfix(xml), {  ignoreMouse: true, ignoreAnimation: true, ignoreClear: true, ignoreDimensions: true  });            

                // render components rasters
                $('#applied_components_list').children('div').each(function () {
                    //alert($(this).data('identity')); // "this" is the current element in the loop
                    //applied_component_ids.push($(this).data('identity'));
                    var single_value_pair = {};
                    // identity of the component
               
                    const component_id = $(this).data('identity');

                    var applied_component = $('#components').children('.component[data-identity="' + component_id.toString() +'"]');
                
                    const applied_component_img_src = applied_component.data('src');
                
                    var newComponentImage = new Image();
                    newComponentImage.src = applied_component_img_src;
                    newComponentImage.onload = function() {
                        rasterCanvasContext.drawImage( newComponentImage , 0, 0);
                    };                
                });
                
                setTimeout(function(){
                    vectorCanvasContext.drawImage(rasterCanvas,0,0);
                    picture = vectorCanvas.toDataURL();
                    //window.open( picture );  
                
                    var ucreate_data = {
                        product_data: product_data,
                        applied_components_data : applied_components_data,
                        picture_data: picture
                    };
 
                    $.ajax({
                        url: "<?php echo site_url('c_ucreate/create'); ?>",
                        type: 'POST',
                        async : false,
                        dataType : 'json',
                        data: ucreate_data,
                        success: function(response) {
                            //alert(response.msg);
                            $('#loading_gif').hide(750);
                            //TODO
                            window.location.href = "<?php echo site_url('/c_customer/products_customer/'); ?>"; return;                            
                    
                            //                    $('#login_result_message').show();
                            //                            
                            //                    $('#login_result_message').css("display","block");
                            //                            
                            //                    if( result == 0){
                            //                        $('#login_result_message').html('User not found!');
                            //                        $('#loading_gif').hide();
                            //                    }else{
                            //                        $('#login_result_message').html('Login successful.');
                            //                        window.location.href = "<?php echo site_url('/c_customer/products_customer/'); ?>";
                            //                    };                            
                        }, error : function(XMLHttpRequest, textStatus, errorThrown) {
                            //                    alert('error');
                            $('#loading_gif').hide(750);
                            //                    $('#waiting').hide(500);
                            //                    $('#message').removeClass().addClass('error')
                            //                    .text('There was an error.').show(500);
                            //                    $('#demoForm').show(500);
                        }
                    });                
                
                },1000);                

            },1000);
            return false;            
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

        <input type="hidden" name="edited_product_id" value="<?php echo $product->getId() ?>">
            <h2>
                <section id="ucreate_product_name" contenteditable="true"><?php echo $product->getName(); ?></section>
            </h2>
            <div class="line pp_dark_gray"></div>

            <!--<div class="text_medium upper_cased pp_dark_gray">design by</div>-->
            <h4 class="pp_dark_gray">design by</h4>
            <!--<div id="pr_l_creator" class="text_medium upper_cased black">kajsiman</div>-->
            <h4 id="ucreate_creator_nick" class="black"><?php echo $product_creator_nick; ?></h4>
            <div class="line pp_dark_gray"></div>

            <!--<div class="text_medium upper_cased pp_dark_gray">type</div>-->
            <h4 class="pp_dark_gray">description</h4>
            <h5 class="lower_cased">
                <section id="ucreate_description" contenteditable="true"><?php echo $product->getDescription(); ?></section>
            </h5>
            <div class="line pp_dark_gray"></div>
            <div class="pr_l_sex_icon"></div>
            <h4 id="ucreate_sex"><?php echo $product->getSex(); ?></h4>
            <div class="line"></div>

            <h1><span id="ucreate_price"><?php echo $product->getPrice(); ?></span>&nbsp;&euro;</h1>

            <button id="create_button" type="button">CREATE !</button>
            <?php //echo form_close(); ?>
    </div>

    <div id="ucreate_center">
        <!--<canvas id="ucreate_canvas" width="250" height="350" style="position: absolute; z-index: 10000;">Your browser doesn't support canvas.</canvas>-->
        <script>
            /*                var canvas = document.getElementById('ucreate_canvas');
                if(canvas.getContext) {
                    // Initaliase a 2-dimensional drawing context
                    var context = canvas.getContext('2d');
                    //Canvas commands go here
<?php
$index = 0;
$varname = 'imageObject_';
$representation_urls = $product_representations[0]->getUrls();
foreach ($representation_urls as $representation_url_item):
    ?>
    <?php $varname = 'imageObject_' . $index; ?>
                                                                                var <?php echo $varname; ?> = new Image();
    <?php echo $varname; ?>.src = '<?php echo base_url($representation_url_item); ?>';
    <?php echo $varname; ?>.onload = function() {
                                                                                context.drawImage( <?php echo $varname; ?> , 0, 0);
                                                                            };
    <?php $index = $index + 1; ?>
<?php endforeach; ?>                
    }
             */
        </script>  
        <div id="ucreate_vector_section"style="z-index: 2">
            <svg xmlns="http://www.w3.org/2000/svg" id="svg_element" title="test1" version="1.1" width="245" height="355" >
                <?php foreach ($ucreate_component_full_info_array as $singleComponentFullInfo): ?>
                    <?php if ($singleComponentFullInfo->getVectors() != NULL): ?>
                        <?php foreach ($singleComponentFullInfo->getVectors() as $singleComponentVector): ?>
                            <path data-id="<?php echo $singleComponentFullInfo->getComponent()->getId(); ?>" 
                                  fill-rule="evenodd" 
                                  clip-rule="evenodd" 
                                  fill="none" 
                                  d="<?php echo $singleComponentVector->getSvgDefinition() ?>"/>
                              <?php endforeach; ?>
                          <?php endif; ?>
                      <?php endforeach; ?>
            </svg>
        </div>
        <div id="ucreate_image_section" >
            <?php
            $representation_urls = $product_representations[0]->getUrls();
            foreach ($representation_urls as $representation_url_item) {
                $image_properties = array(
                    'id' => 'basic_image',
                    'src' => $representation_url_item,
                    'alt' => $product_representations[0]->getProductName(),
                    'title' => $product_representations[0]->getProductName(),
                    'class' => 'ucreate_image',
                    'style' => 'z-index: 1'
                );
                echo img($image_properties);
            }
            ?>
        </div>
        <div id="background_image_effect" style="position: absolute; width: 240px; height: 350px;">
            <?php
            $image_properties_basic_effect = array(
                'id' => 'basic_image_effect',
                'src' => 'assets/css/images/bottomshadow.png',
                'alt' => 'Image bottom effect',
                'style' => 'z-index: -1; position: absolute; width: 240px; height: 350px;'
            );
            echo img($image_properties_basic_effect);
            ?>
        </div>
    </div>
    <canvas id="svg_canvas" width="250" height="350" style="border: 1px solid black">Your browser doesn't support canvas.</canvas>
    <canvas id="raster_canvas" width="250" height="350" style="border: 1px solid black">Your browser doesn't support canvas.</canvas>            

    <div id="ucreate_right">
        <h3>Categories</h3>
        <div id="categories">
            <?php foreach ($categories as $singleCategory): ?>
                <div class="category"><?php echo $singleCategory->getName(); ?>
                    <span class="tooltip"><?php echo $singleCategory->getDescription(); ?></span>            
                </div>
            <?php endforeach; ?>
        </div>
        <div class="line pp_dark_gray"></div>
        <h3>Components</h3>
        <div id="components">
            <?php foreach ($ucreate_component_full_info_array as $singleComponentFullInfo): ?>
                <div class="component"
                     data-src="<?php echo base_url($singleComponentFullInfo->getRaster()->getPhotoUrl()); ?>"
                     data-identity="<?php echo $singleComponentFullInfo->getComponent()->getId(); ?>"
                     data-price="<?php echo $singleComponentFullInfo->getComponent()->getPrice(); ?>"
                     data-name="<?php echo $singleComponentFullInfo->getComponent()->getName(); ?>"
                     data-multiple="false"
                     >
                    <span
                        class="component_add"
                        data-identity="<?php echo $singleComponentFullInfo->getComponent()->getId(); ?>"
                        >Add</span>
                    <div class="component_name"><?php echo $singleComponentFullInfo->getComponent()->getName(); ?>
                        <span class="tooltip">Price: <?php echo $singleComponentFullInfo->getComponent()->getPrice(); ?>&euro;</span>
                    </div>
                    <span
                        class="component_remove"
                        data-identity="<?php echo $singleComponentFullInfo->getComponent()->getId(); ?>"
                        >Remove</span>

                </div>
                <div style="clear: both"></div>
            <?php endforeach; ?>
        </div>
        <div class="line pp_dark_gray"></div>
        <h3>Applied components</h3>
        <div id="applied_components_list">
        </div>
        <div class="line pp_dark_gray"></div>
        <h3>Colour range</h3>
        <div id="applied_component_colours">
            <?php foreach ($ucreate_component_full_info_array as $singleComponentFullInfo): ?>
                <?php if ($singleComponentFullInfo->getAvailableColours() != NULL): ?>
                    <div class="applied_component_colour_possibilities"  data-identity="<?php echo $singleComponentFullInfo->getComponent()->getId(); ?>">
                        <?php foreach ($singleComponentFullInfo->getAvailableColours() as $singleColour): ?>
                            <div class="applied_component_colour notselected" data-colour_id="<?php echo $singleColour->getId() ?>" data-colour ="<?php echo $singleColour->getValue() ?>" style="background-color: <?php echo '#' . $singleColour->getValue() ?>"></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>        
    </div>

</div><!-- end of content-->
