<div class="gallery_gradient_wrapper">
    <div class="gallery_gradient right">
    </div>
    <div class="gallery_gradient left">
    </div>
</div>

<!-- content -->
<div id="content">
    <div class="content_wrapper">


        <!-- ******************* final preview section ******************* -->
        <div class="container">
            <!-- Title -->
            <h1>
                4. payment for order
            </h1>
            <div class="red_line">
            </div>

            <div class="text_fields_wrapper">
                <div class="text_field_wrapper left">
                    <h2>
                        items
                    </h2>
                    <div class="final_items_list">
                        <?php
                        for ($i = 0; $i < count($ordered_products); ++$i):
                            ?>
                            <div class="final_item">
                                <span class="text_light smaller upper_cased bold"><?php echo $ordered_products[$i]->pd_product_name; ?></span>
                                <span class="text_light smaller upper_cased">by:<span class="text_light upper_cased bold"><?php echo $ordered_products[$i]->u_nick; ?></span></span>
                                <span class="text_light smaller upper_cased bold"><?php echo $ordered_products[$i]->psfp_name; ?></span>
                                <span class="text_light smaller"><?php echo $ordered_products[$i]->op_amount; ?>&nbsp;pc.</span>
                                <span class="text_light smaller upper_cased">price<span class="text_light lower_cased">/ks:</span><span class="text_light lower_cased bold"><?php echo $ordered_products[$i]->pd_price; ?>&euro; dph</span></span>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <!--<div class="text_medium upper_cased bold">shipping address</div>-->
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
                        shipping method
                    </h2>
                    <div>
                        <div id="final_shipping_method" class="text_light upper_cased"><?php echo $shipping_method->sm_name; ?>(+<?php echo $shipping_method->sm_price; ?>&euro;)</div>
                    </div>
                </div>
                <div class="text_field_wrapper right">
                    <h2>
                        payment method
                    </h2>
                    <div>
                        <div id="final_payment_method" class="text_light upper_cased"><?php echo $payment_method->pm_name; ?>(+<?php echo $payment_method->pm_cost; ?>&euro;)</div>
                    </div>
                    <?php if ($payment_method->pm_name == 'paypal'): ?>
                        <h2>
                            pay with paypal
                        </h2>
                        <div>
                            <?php
                            $attributes = array('name' => 'pp_checkout_form');
                            echo form_open("c_paypal/express_checkout", $attributes);
                            ?>
                            <span class="text_light upper_cased">paypal standard payment</span>
                            <input type = "radio"
                                   class="css-checkbox"
                                   name = "selected_payment_type"
                                   id = "paypal_standard"
                                   value = "paypal"
                                   checked = "checked" />
                            <label for="paypal_standard" class="css-label">&nbsp;</label>
                            <div style="clear:both;"></div>
<!--                            section for paypal credit card payments, unsupported now-->
<!--                            <span class="text_light upper_cased">visa direct payment</span>
                            <input type = "radio"
                                   class="css-checkbox"
                                   name = "selected_payment_type"
                                   id = "credit_card_visa"
                                   value = "Visa"/>
                            <label for="credit_card_visa" class="css-label">&nbsp;</label>
                            <div style="clear:both;"></div>
                            <span class="text_light upper_cased">mastercard direct payment</span>
                            <input type = "radio"
                                   class="css-checkbox"
                                   name = "selected_payment_type"
                                   id = "credit_card_mastercard"
                                   value = "MasterCard"/>
                            <label for="credit_card_mastercard" class="css-label">&nbsp;</label>
                            <div style="clear:both;"></div>

                            credit card data
                            <label for="pf_card_number" class="required">credit card number</label>
                            <input type="text" id="pf_card_number" name="pf_card_number" value="" size="32" />
                            <div style="clear:both;"></div>
                            
                            <label for="pf_card_exp_date" class="required">expiry date</label>
                            <input type="text" id="pf_card_exp_date" name="pf_card_exp_date" value="" size="8" />
                            <div style="clear:both;"></div> 
                            
                            <label for="pf_card_cvv2" class="required">cvv2</label>
                            <input type="text" id="pf_card_cvv2" name="pf_card_cvv2" value="" size="8" />
                            <div style="clear:both;"></div>             -->

                            <input type='image' name='submit' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif' border='0' align='top' alt='Check out with PayPal'/>
                            <?php form_close(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div style="clear:both;"></div>
            <div class="bottom_wrapper">
                <div class="bottom_wrapper left">
                    <div class="left_pp_button_wrapper">
                        <!--<button class="pp_button_passive fl_left" type="submit" name="submit">BACK</button>-->
                        <?php echo anchor('c_shopping_cart/index', 'BACK', array('class' => 'pp_button_passive fl_left')); ?>
                        <!--<a href="" class="pp_button_passive fl_left">BACK</a>-->
                    </div>
                </div>
                <div class="bottom_wrapper right">
                    <div class="fl_right">
                        <div class="text_medium upper_cased bold">total&nbsp;&nbsp;&nbsp;<span class="pp_red"><?php echo $total; ?>&nbsp;&euro;</span></div>
                    </div>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        </form>
    </div>
</div>




