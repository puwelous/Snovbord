<script type="text/javascript">
    $(document).ready(function(){    

        var formerShippingMethodPrice = parseFloat( <?php echo $shipping_methods[0]->getCost(); ?> );
        $("li.hidable").css('visibility', 'hidden');

        $(".add_one_item_wrapper").click( function(){
                    
            var updatedSpanObject = $(this).closest(".product_detail_line").find("span.value_wrapper").find("input");

            // adding item count
            var newItemsCount = parseInt( updatedSpanObject.val() ) + 1;
            updatedSpanObject.val(newItemsCount);
            
            // updating subtotal value
            var valuePerItem = parseFloat($(this).closest(".cart_list_item").find("input[name$=_item_price]").val());
      
            // updating first section
            var actualSubtotal = parseFloat($("#subtotal_first_section_sum").html());

            actualSubtotal += valuePerItem;
            
            $("#subtotal_first_section_sum").html(new Number(actualSubtotal).toFixed(2));
            
            // updating second section
            var actualSubtotalSecondSection = parseFloat( $('#subtotal_second_section_sum').html());
            
            actualSubtotalSecondSection += valuePerItem;
            $("#subtotal_second_section_sum").html(new Number(actualSubtotalSecondSection).toFixed(2));
        });

  
        $(".subtract_one_item_wrapper").click( function(){
                                    
            var updatedSpanObject = $(this).closest(".product_detail_line").find("span.value_wrapper").find("input");

            var newItemsCount = parseInt( updatedSpanObject.val() ) - 1;  
            if( newItemsCount <= 0)
                return;  
            updatedSpanObject.val(newItemsCount);
                    
            // updating subtotal value
            var valuePerItem = parseFloat($(this).closest(".cart_list_item").find("input[name$=_item_price]").val());
            
            var actualSubtotal = parseFloat($("#subtotal_first_section_sum").html());

            actualSubtotal -= valuePerItem;
            
            $("#subtotal_first_section_sum").html(new Number(actualSubtotal).toFixed(2));


            // updating second section
            var actualSubtotalSecondSection = parseFloat( $('#subtotal_second_section_sum').html());
            
            actualSubtotalSecondSection = actualSubtotalSecondSection - valuePerItem;
            $("#subtotal_second_section_sum").html( (new Number(actualSubtotalSecondSection)).toFixed(2) );
        });
        
        $("label[for^=shipping_method_]").click( function(){
                               
            var actualSubtotalSecondSection = parseFloat( $('#subtotal_second_section_sum').html());
            var newSubtotalSecondSection;
                                    
            var selected_shipping_method_price = parseFloat($(this).parent('li').children(".shipping_method_price_wrapper").children(".shipping_method_price").html());
            
            // newSubtotalSecondSection = add new shipping method price, subtract old shipping method price
            newSubtotalSecondSection = actualSubtotalSecondSection + selected_shipping_method_price - formerShippingMethodPrice;
            
            // store for next call
            formerShippingMethodPrice = selected_shipping_method_price;
            
            $("#subtotal_second_section_sum").html(new Number(newSubtotalSecondSection).toFixed(2));
        });   
        
        $("label[for=address_other]").click( function(){
            $("li.hidable").css('visibility', 'visible');
        });
        
        $("label[for=address_same]").click( function(){
            $("li.hidable").css('visibility', 'hidden');
        });
        
        $("label[for^=payment_method_]").click( function(){
            $("#final_payment_method").html($(this).siblings( "input" ).val());
        });        
        
        $("html .pp_button_active:not(.pp_button_active:last)").click( function(){
            $("div.content_wrapper").animate({
                left: ("-=" + 815)
            }, 2000);
        });          
                
        $(".pp_button_passive").click( function(){
            $("div.content_wrapper").animate({
                left: ("+=" + 815)
            }, 2000);
        });                
                
        $(".cart_list").mCustomScrollbar();
    });
</script>

<div class="gallery_gradient_wrapper">
    <div class="gallery_gradient right">
    </div>
    <div class="gallery_gradient left">
    </div>             
</div> 

<!-- content -->
<div id="content">

    <div class="content_wrapper">

        <!-- ******************* shopping cart section ******************* -->
        <?php //echo validation_errors('<p class="error" style="display:inline">'); ?>
        <?php
        $attributes = array('name' => 'pp_shopping_cart_edit_form', 'style' => 'height:100%;');
        echo form_open("c_shopping_cart/show_order_preview", $attributes);

        $dataCartId = array(
            'type' => 'hidden',
            'name' => 'cart_id',
            'value' => $shopping_cart_id
        );
        echo form_input($dataCartId);
        ?>

        <div class="container">

            <!-- Title -->
            <!--<div class="title upper_cased black">-->
            <h1>
                1. shopping cart
            </h1>
            <!--</div>-->
            <div class="blue_line">
            </div>                    


            <div class="cart_list">
                <?php for ($i = 0; $i < count($ordered_products_full_info); ++$i): ?>
                    <div class="cart_list_item">
                        <?php
                        $dataId = array(
                            'type' => 'hidden',
                            'name' => 'ordered_product_' . $ordered_products_full_info[$i]->getOrderedProductId() . '_id',
                            'value' => $ordered_products_full_info[$i]->getOrderedProductId()
                        );
                        echo form_input($dataId);

                        $dataPrice = array(
                            'type' => 'hidden',
                            'name' => 'ordered_product_' . $ordered_products_full_info[$i]->getOrderedProductId() . '_item_price',
                            'value' => $ordered_products_full_info[$i]->getProductPrice()
                        );
                        echo form_input($dataPrice);
                        ?>
                        <div class="product_view_section">
                            <div class="preview_item">
                                <div class="preview_item_imgs_wrapper">
                                    <?php
                                    $representation_urls = $ordered_products_full_info[$i]->getProductScreenRepresentation()->getUrls();
                                    foreach ($representation_urls as $representation_url_item) {
                                        $image_properties = array(
                                            'src' => $representation_url_item,
                                            'alt' => $ordered_products_full_info[$i]->getProductScreenRepresentation()->getProductName(),
                                            'class' => 'product_image'
                                        );
                                        echo img($image_properties);
                                    }
                                    ?>
                                </div>
                            </div> 
                        </div>
                        <div class="product_details_section">
                            <div class="product_detail_line">
                                <span class="text_light upper_cased">name:</span>
                                <span class="text_light upper_cased bold"><?php echo $ordered_products_full_info[$i]->getProductName(); ?></span>
                            </div>
                            <div class="product_detail_line">
                                <span class="text_light upper_cased">author:</span>
                                <span class="text_light upper_cased bold"><?php echo $ordered_products_full_info[$i]->getCreatorNick(); ?></span>
                            </div>
                            <div class="product_detail_line">
                                <span class="text_light upper_cased">size:</span>
                                <span class="text_light upper_cased bold"><?php echo $ordered_products_full_info[$i]->getPossibleSizeForProductName(); ?></span>
                            </div>
                            <div class="product_detail_line">
                                <span class="text_light upper_cased">count:</span>
                                <span class="value_wrapper">
    <!--                                        <span class="text_light upper_cased bold">-->
                                    <?php
                                    $dataProductAmount = array(
                                        'type' => 'number',
                                        'name' => 'ordered_product_' . $ordered_products_full_info[$i]->getOrderedProductId() . '_amount',
                                        'value' => $ordered_products_full_info[$i]->getOrderedProductCount(),
                                        'class' => 'text_light upper_cased bold',
                                        'readonly' => 'readonly'
                                    );
                                    echo form_input($dataProductAmount);
                                    ?>                                    
                                    <!--<input type="number" name="ABCD" value="<?php echo $ordered_products_full_info[$i]->getOrderedProductCount(); ?>" class="text_light upper_cased bold" readonly>-->  
                                    <!--</span>-->
                                </span>
                                <span class="add_one_item_wrapper"><span class="text_light upper_cased">+</span></span>
                                <span class="text_light upper_cased">/</span>
                                <span class="subtract_one_item_wrapper"><span class="text_light upper_cased">-</span></span>
                            </div>
                            <div class="product_detail_line">
                                <!--<span class="text_light upper_cased italic red_on_hover">-->
                                <?php echo anchor('c_shopping_cart/remove_item/' . $ordered_products_full_info[$i]->getOrderedProductId(), 'remove', array('class' => 'text_light upper_cased italic red_on_hover')); ?>
                                <!--</span>-->
                                <span class="text_light italic">/</span>
                                <span class="text_light upper_cased italic red_on_hover">edit</span>
                            </div>                                       
                        </div>
                        <div class="product_price_section">
                            <div class="product_price_line">
                                <span class="text_light upper_cased">price/</span>
                                <span class="text_light">item:</span>
                                <span class="text_light upper_cased bold"><?php echo $ordered_products_full_info[$i]->getProductPrice(); ?>&euro;</span>
                                <span class="text_light bold">dph</span>
                            </div>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                <?php endfor; ?>                  
            </div>

            <div style="clear:both;"></div>
            <div class="bottom_wrapper">
                <div class="bottom_wrapper left">
                    <!--<div class="text_medium upper_cased bold">subtotal&nbsp;&nbsp;&nbsp;500 &euro;</div>-->
                    <h2>initial subtotal&nbsp;<?php echo $shopping_cart_sum; ?>&nbsp;&euro;</h2>
                </div>
                <div class="bottom_wrapper right">
                    <!--<input type="button" value="SIGN UP"/>-->
                    <div class="fl_right">
                        <!--<div class="text_medium upper_cased bold">subtotal&nbsp;&nbsp;&nbsp;-->
                        <h2 style="display:inline-block">subtotal&nbsp;</h2><span id="subtotal_first_section_sum" class="text_medium upper_cased bold pp_blue"><?php echo $shopping_cart_sum; ?></span><span class="text_medium upper_cased bold pp_blue">&nbsp;&euro;</span>
                        <!--</div>-->
                    </div>
                    <div style="clear:both;"></div>
                    <div class="right_pp_button_wrapper">
                        <button class="pp_button_active" type="button" name="submit">NEXT STEP&nbsp;<span class="pp_blue">&gt;</span></button>
                    </div>
                </div>
                <div style="clear:both;"></div>
            </div>


        </div>

        <!-- ******************* shipping and payment section ******************* -->
        <div class="container">
            <!-- Title -->
            <!--<div class="title upper_cased black">-->
            <h1>
                2. shipping & payment
            </h1>
            <!--</div>-->
            <div class="blue_line">
            </div>                    
            <!--<form name="pp_form_name" action="#" method="POST">-->

            <div class="text_fields_wrapper">

                <div class="text_field_wrapper left">
                    <!--<div class="text_medium upper_cased bold">shipping address</div>-->
                    <h2>
                        shipping address
                    </h2>
                    <ul class="shipping_list">
                        <li>
                            <span class="text_light upper_cased">same as the registration address</span>
                            <input type = "radio"
                                   class="css-checkbox"
                                   name = "selected_address_type"
                                   id = "address_same"
                                   value = "same"
                                   checked = "checked" />
                            <label for="address_same" class="css-label">&nbsp;</label>
                            <div style="clear:both;"></div>
                            <span class="text_light upper_cased">other address</span>
                            <input type = "radio"
                                   class="css-checkbox"
                                   name = "selected_address_type"
                                   id = "address_other"
                                   value = "other"/>
                            <label id="other_address_label" for="address_other" class="css-label">&nbsp;</label>
                            <div style="clear:both;"></div>
                        </li>

                        <li class="hidable">
                            <label for="tf_first_name" class="required">first name</label>
                            <input type="text" id="tf_first_name" name="tf_first_name" placeholder="First name" />
                            <div style="clear:both;"></div>
                        </li>
                        <li class="hidable">
                            <label for="tf_last_name" class="required">last name</label>
                            <input type="text" id="tf_last_name" name="tf_last_name" placeholder="Last name" />
                            <div style="clear:both;"></div>
                        </li>                        
                        <li class="hidable">
                            <label for="tf_address" class="required">address</label>
                            <input type="text" id="tf_address" name="tf_address" value="" size="32" />
                            <div style="clear:both;"></div>
                        </li>
                        <li class="hidable">
                            <label for="tf_city" class="required">city</label>
                            <input type="text" id="tf_city" name="tf_city" value="" size="32" />
                            <div style="clear:both;"></div>
                        </li>
                        <li class="hidable">
                            <label for="tf_zip" class="required">zip</label>
                            <input type="number" id="tf_zip" name="tf_zip" />
                            <div style="clear:both;"></div>
                        </li>
                        <li class="hidable">
                            <label for="tf_country" class="required">country</label>
                            <input type="text" id="tf_country" name="tf_country" value="" size="32" />
                            <div style="clear:both;"></div>
                        </li>                                
                        <li class="hidable">
                            <label for="tf_phone_number" class="required">phone number</label>
                            <input type="text" id="tf_phone_number" name="tf_phone_number" placeholder="Phone number" size="32" />
                            <div style="clear:both;"></div>
                        </li>
                        <li class="hidable">
                            <label for="tf_email_address" class="required">email address</label>
                            <input type="email" id="tf_email_address" name="tf_email_address" placeholder="john_doe@example.com" size="32" />
                            <div style="clear:both;"></div>
                        </li>
                        <?php
                        $attributes = array('name' => 'pp_reset_password_form');
                        echo form_open("c_user/password_reset", $attributes);
                        ?>
                        <?php for ($i = 0; $i < count($shipping_methods); ++$i): ?>
                            <li>
                                <span class="text_light upper_cased">
                                    <?php echo $shipping_methods[$i]->getName(); ?>&nbsp;
                                </span>
                                <span class="shipping_method_price_wrapper text_light">
                                    +<span class="shipping_method_price"><?php echo $shipping_methods[$i]->getCost(); ?></span>&nbsp;&euro;
                                </span>
                                <input type = "radio"
                                       class="css-checkbox"
                                       name = "shipping_method"
                                       id = "<?php echo 'shipping_method_' . $shipping_methods[$i]->getId(); ?>"
                                       value = "<?php echo $shipping_methods[$i]->getId(); ?>"
                                       <?php if ($i == 0) echo 'checked ="checked"'; ?>
                                       />
                                <label for="<?php echo 'shipping_method_' . $shipping_methods[$i]->getId(); ?>" class="css-label">&nbsp;</label>
                                <div style="clear:both;"></div> 
                            </li>
                        <?php endfor; ?>
                        <?php echo form_close(); ?>
                    </ul>
                </div>
                <div class="text_field_wrapper right">
                    <!--<div class="text_medium upper_cased bold">payment method</div>-->
                    <h2>
                        payment method
                    </h2>
                    <ul id="shipping_list_section" class="shipping_list">

                        <?php for ($i = 0; $i < count( $payment_methods ); ++$i): ?><li>
                                <span class="text_light upper_cased"><?php echo $payment_methods[$i]->getName(); ?>&nbsp;+<?php echo $payment_methods[$i]->getCost(); ?>&nbsp;&euro;</span>
                                <input type = "radio"
                                       class="css-checkbox"
                                       name = "payment_method"
                                       id = "payment_method_<?php echo $payment_methods[$i]->getId(); ?>"
                                       value = "<?php echo $payment_methods[$i]->getId(); ?>"
                                       <?php if ($i == 0) echo 'checked ="checked"'; ?>
                                       />
                                <label for="payment_method_<?php echo $payment_methods[$i]->getId(); ?>" class="css-label">&nbsp;</label>
                                <div style="clear:both;"></div></li>
                        <?php endfor; ?>                        

                    </ul>                            
                </div>
            </div>

            <div style="clear:both;"></div>
            <div class="bottom_wrapper">
                <div class="bottom_wrapper left">
                    <div class="left_pp_button_wrapper">
                        <button class="pp_button_passive fl_left" type="button" name="submit">BACK</button>
                    </div>
                </div>
                <div class="bottom_wrapper right">
                    <!--<input type="button" value="SIGN UP"/>-->
                    <div class="fl_right">
                        <!--<div class="text_medium upper_cased bold">subtotal&nbsp;&nbsp;&nbsp;<span class="pp_red">520 &euro;</span></div>-->
                        <h2 style="display:inline-block">subtotal</h2>    
                        <span class="text_medium upper_cased bold pp_blue">
                            <span id="subtotal_second_section_sum" class="text_medium upper_cased bold pp_blue"><?php echo $second_section_subtotal; ?></span>&nbsp;&euro;
                        </span>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="right_pp_button_wrapper">
                        <button class="pp_button_active" type="submit" name="submit">NEXT STEP <span class="pp_blue">&gt;</span></button>
                    </div>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div> 
        <?php echo form_close(); ?>
    </div>

</div><!-- end of content-->
<!--<script src="./js/jquery.mCustomScrollbar.concat.min.js"></script>-->
