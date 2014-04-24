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
        <?php
        $attributes = array('name' => 'pp_create_order', 'style' => 'height:100%;');
        echo form_open("c_order/create_order", $attributes);

        // shopping cart ID
        $dataCartId = array(
            'type' => 'hidden',
            'name' => 'cart_id',
            'value' => $shopping_cart_id
        );
        echo form_input($dataCartId);

        // payment method
        $dataPayMethId = array(
            'type' => 'hidden',
            'name' => 'payment_method_id',
            'value' => $payment_method->getId()
        );
        echo form_input($dataPayMethId);

        // shipping method
        $dataShipMethId = array(
            'type' => 'hidden',
            'name' => 'shipping_method_id',
            'value' => $shipping_method->getId()
        );
        echo form_input($dataShipMethId);

        // total sum
        $dataTotalSum = array(
            'type' => 'hidden',
            'name' => 'total_sum',
            'value' => $total
        );
        echo form_input($dataTotalSum);
        ?>            

        <!-- ******************* final order preview section ******************* -->
        <div class="container">
            <!-- Title -->
            <h1>
                3. order preview
            </h1>
            <div class="blue_line">
            </div>

            <div class="text_fields_wrapper">

                <!--<div class="text_field_wrapper left">-->
                    <h2>
                        items
                    </h2>
                    <div class="final_items_list">
                        <?php
                        for ($i = 0; $i < count($ordered_products_full_info); ++$i):
                            ?>
                            <div class="final_item">
                                <span class="text_light smaller upper_cased bold"><?php echo $ordered_products_full_info[$i]->getProductName(); ?></span>
                                <span class="text_light smaller upper_cased">by:<span class="text_light upper_cased bold"><?php echo $ordered_products_full_info[$i]->getCreatorNick(); ?></span></span>
                                <span class="text_light smaller upper_cased bold"><?php echo $ordered_products_full_info[$i]->getPossibleSizeForProductName(); ?></span>
                                <span class="text_light smaller"><?php echo $ordered_products_full_info[$i]->getOrderedProductCount(); ?>&nbsp;pc.</span>
                                <span class="text_light smaller upper_cased">price<span class="text_light lower_cased">/ks:</span><span class="text_light lower_cased bold"><?php echo $ordered_products_full_info[$i]->getProductPrice(); ?>&euro; dph</span></span>                                
                            </div>
                        <?php endfor; ?>
                    </div>
                    <h2>
                        shipping address
                    </h2>
                    <div class="address">
                        <div class="text_light upper_cased">
                            <?php echo $order_address['oa_first_name']; ?>
                        </div>
                        <div class="text_light upper_cased">
                            <?php echo $order_address['oa_last_name']; ?>
                        </div>                        
                        <div class="text_light upper_cased">
                            <?php echo $order_address['oa_address']; ?>
                        </div>
                        <div class="text_light upper_cased">
                            <?php echo $order_address['oa_zip']; ?>&nbsp;<?php echo $order_address['oa_city']; ?>
                        </div>
                        <div class="text_light upper_cased">
                            <?php echo $order_address['oa_country']; ?>
                        </div>
                    </div>
                    <h2>
                        email address
                    </h2>     
                    <div class="final_email_address">
                        <div class="text_light upper_cased">
                            <?php echo $order_address['oa_email_address']; ?>
                        </div> 
                    </div>
                    <h2>
                        payment method
                    </h2>                            
                    <div>
                        <div id="final_payment_method" class="text_light upper_cased"><?php echo $payment_method->getName(); ?>&nbsp;(+<?php echo $payment_method->getCost(); ?>&euro;)</div>
                    </div>
                    <h2>
                        shipping method
                    </h2>                            
                    <div>
                        <div id="final_shipping_method" class="text_light upper_cased"><?php echo $shipping_method->getName(); ?>&nbsp;(+<?php echo $shipping_method->getCost(); ?>&euro;)</div>
                    </div>                        
                <!--</div>-->
<!--                <div class="text_field_wrapper right">                         
                </div>-->
            </div>

            <div style="clear:both;"></div>
            <div class="bottom_wrapper">
                <div class="bottom_wrapper left">
                    <div class="left_pp_button_wrapper">
                        <?php echo anchor('c_shopping_cart/index', 'BACK', array('class' => 'pp_button_passive fl_left')); ?>
                    </div>
                </div>
                <div class="bottom_wrapper right">
                    <div class="fl_right">
                        <div class="text_medium upper_cased bold">total&nbsp;&nbsp;&nbsp;<span class="pp_blue"><?php echo $total; ?>&nbsp;&euro;</span></div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="right_pp_button_wrapper">
                        <button id="buy_button" class="pp_button_active" type="submit" name="submit">ORDER PRODUCT(S)</button>
                    </div>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>      
        <?php
        echo form_close();
        ?>
    </div>

</div><!-- end of content-->
<!--<script src="./js/jquery.mCustomScrollbar.concat.min.js"></script>-->
