<!DOCTYPE html>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo ( empty($title) ? 'Snovbord' : $title) ?></title>
        <?php
        // css
        echo link_tag('assets/css/menu.css');
        echo link_tag('assets/css/finalproducts.css');
        echo link_tag('assets/css/socialsidebar.css');
        echo link_tag('assets/css/jquery.mCustomScrollbar.css');
        echo link_tag('assets/css/checkbox.css');
        echo link_tag('assets/css/preview.css');
        echo link_tag('assets/css/jquery.jqzoom.css');
        echo link_tag('assets/css/ucreate.css');
        //js
        ?>

        <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js' type='text/javascript'></script>
        <script src="https://code.jquery.com/jquery-latest.js" type="text/javascript"></script>

        <script src="<?php echo base_url(); ?>assets/javascript/validate.min.js" text='text/javascript'></script>

        
        <script type="text/javascript">
            $(document).ready(function(){
                
                var timeoutNickReference;
                var timeoutEmailReference;
                var isErrorNickPresent = false;
                var isErrorEmailPresent = false;
                
                $('input#tf_nick').keypress(function() {
                    var el = this; // copy of this object for further usage
        
                    if (timeoutNickReference) clearTimeout(timeoutNickReference);
                    timeoutNickReference = setTimeout(function() {
                        doneTypingNick.call(el);
                    }, 3000);
                });
                $('input#tf_email_address').keypress(function() {
                    var el = this; // copy of this object for further usage
        
                    if (timeoutEmailReference) clearTimeout(timeoutEmailReference);
                    timeoutEmailReference = setTimeout(function() {
                        doneTypingEmail.call(el);
                    }, 3000);
                });
                
                $('input#tf_nick').blur(function(){
                    doneTypingNick.call(this);
                });
                $('input#tf_email_address').blur(function(){
                    doneTypingEmail.call(this);
                });                  
                
                function doneTypingNick(){

                    if (!timeoutNickReference){
                        return;
                    }
                    timeoutNickReference = null;
    
                    var reg_temp_data = {
                        login_nick : $('input#tf_nick').val(),
                        ajax : '2'
                    };                    
                    
                    $.ajax({
                        url: "<?php echo site_url('c_user/is_user_present'); ?>",
                        type: 'POST',
                        async : false,
                        data: reg_temp_data,
                        success: function(result) {

                            if( result == 0){
                                isErrorNickPresent = false;
                                $('#error_output_section').html('Nick is allowed to be used.');
                                if( isErrorNickPresent == false && isErrorEmailPresent == false){
                                    $('#error_output_section').removeClass("pp_red");
                                    $('#error_output_section').addClass("pp_green");                                    
                                }
                            }else{
                                isErrorNickPresent = true;
                                $('#error_output_section').html('User ' + reg_temp_data.login_nick + ' already exists.');
                                if( isErrorNickPresent == true || isErrorEmailPresent == true){
                                    $('#error_output_section').removeClass("pp_green");
                                    $('#error_output_section').addClass("pp_red");                                    
                                }
                            }
                        }
                    });                    
                }
                
                function doneTypingEmail(){

                    if (!timeoutEmailReference){
                        return; 
                    }
                    timeoutEmailReference = null;

                    var reg_temp_data = {
                        login_email : $('input#tf_email_address').val(),
                        ajax : '3'
                    };
                    
                    $.ajax({
                        url: "<?php echo site_url('c_user/is_user_present'); ?>",
                        type: 'POST',
                        async : false,
                        data: reg_temp_data,
                        success: function(result) {

                            if( result == 0){
                                isErrorEmailPresent = false;
                                $('#error_output_section').html('Email is allowed to be used.');
                                if( isErrorNickPresent == false && isErrorEmailPresent == false){
                                    $('#error_output_section').removeClass("pp_red");
                                    $('#error_output_section').addClass("pp_green");                                    
                                }                              
                            }else{
                                isErrorEmailPresent = true;
                                $('#error_output_section').html('Email ' + reg_temp_data.login_email + ' already exists.');
                                if( isErrorNickPresent == true || isErrorEmailPresent == true){
                                    $('#error_output_section').removeClass("pp_green");
                                    $('#error_output_section').addClass("pp_red");                                    
                                }                             
                            }                        
                        }
                    });                    
                }
                
                $('#login_result_message').hide();
                
                $('#login_wrapper').bind('keypress', function(e) {
                    if(e.keyCode!=13){
                        // Enter NOT pressed... ignore

                        return;
                    }

                    var form_data = {
                        login_nick_or_email : $('#login_nick_or_email').val(),
                        login_password : $('#login_password').val(),
                        ajax : '1'
                    };
                    
                    $.ajax({
                        url: "<?php echo site_url('c_user/login'); ?>",
                        type: 'POST',
                        async : false,
                        data: form_data,
                        beforeSend: function() {
                            $('#loading_gif').show();
                        },
                        success: function(result) {
                            
                            $('#login_result_message').show();
                            
                            $('#login_result_message').css("display","block");
                            
                            if( result == 0){
                                $('#login_result_message').html('User not found!');
                                $('#loading_gif').hide();
                            }else{
                                $('#login_result_message').html('Login successful.');
                                window.location.href = "<?php echo site_url('welcome/index'); ?>";
                            };                            
                        }
                    });
                    return false;
                });
                
                $('#loading_gif').hide();
            });
        </script>
    </head>
    <body>
        <div id="loading_gif">
            <?php echo img('assets/css/images/loading.gif'); ?>
        </div>

        <!--  menu -->
        <div id="menu_wrapper">

            <ul class="menu_l">
                <li>
                    <?php echo anchor('whatisrealpp', ' ', array('class' => 'upper_cased')); ?>
                </li>
                <li>
                    <?php echo anchor('ucreate', 'u create', array('class' => 'text_light smaller pp_dark_gray red_on_hover upper_cased')); ?>
                </li>
                <li>
                    <?php echo anchor('products', 'products', array('class' => 'text_light smaller pp_dark_gray red_on_hover upper_cased')); ?>
                </li>
            <!--</ul>-->
<!--            <ul class="menu_r">-->
<!--                <li id="m_language">
                    <?php echo anchor('shopping_cart', 'en / sk', array('class' => 'text_light smaller pp_dark_gray red_on_hover upper_cased')); ?>
                </li>-->
<!--                <li id="m_cart">
                    <?php //echo anchor('shopping_cart', 'shopping cart', array('class' => 'text_light smaller pp_dark_gray red_on_hover upper_cased')); ?>
                </li>-->

                <?php
                /* dynamicaly added <LI> element either to log in or log out according to the status of user */
                echo $shopping_cart_template
                ?>                
                
                <?php
                /* dynamicaly added <LI> element either to log in or log out according to the status of user */
                echo $login_or_logout_template
                ?>

                <li id="m_contact">
                    <?php echo anchor('contact', 'info', array('class' => 'text_light smaller pp_dark_gray red_on_hover upper_cased')); ?>
                </li>
                
                <?php
                /* dynamicaly added <LI> element for admin only */
                if ( isset( $customer_template ) ){
                    echo $customer_template;
                }else if( isset($admin_template ) ) {
                    echo $admin_template;
                }
                ?>                
            </ul>
        </div>
        <div class="line pp_blue"></div>