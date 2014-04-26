<!-- content -->
<div id="content">
    <div class="content_wrapper">
        <!-- ******************* shopping cart section ******************* -->
        <div class="container">
            <!-- Title -->
            <h1>
                profile
                <?php echo anchor('c_customer/index', '<-go back', array('class' => 'text_light smaller pp_dark_gray red_on_hover inunderlined upper_cased')); ?>                 
            </h1>
            <div class="blue_line">
            </div>  
            <h3>Edit only fields you want to change. Enter password only if you want to change the current one.</h3>
            <?php echo validation_errors('<p class="error" style="display:inline">'); ?>
            <?php
            $attributes = array('name' => 'sb_profile_edit');
            echo form_open("c_customer/edit_profile", $attributes);
            ?>            
            <div class="text_fields_wrapper">

                <div class="text_field_wrapper left">
                    <ul>
                        <li>
                            <label for="tf_nick" >nick</label>
                            <input type="text" id="tf_nick" name="tf_nick" placeholder="Nick" value="<?php echo set_value('tf_nick', $user->getNick()); ?>" size="32" maxlength="32" />
                        </li>
                        <li>
                            <label for="tf_first_name" >first name</label>
                            <input type="text" id="tf_first_name" name="tf_first_name" placeholder="First name" value="<?php echo set_value('tf_first_name', $user->getFirstName()); ?>" size="32" maxlength="32" />
                        </li>
                        <li>
                            <label for="tf_last_name" >last name</label>
                            <input type="text" id="tf_last_name" name="tf_last_name" placeholder="Last name" value="<?php echo set_value('tf_last_name',  $user->getLastName()); ?>" size="32" maxlength="32" />
                        </li>
                        <li>
                            <label for="tf_email_address" >email address</label>
                            <input type="email" id="tf_email_address" name="tf_email_address" placeholder="john_doe@example.com" value="<?php echo set_value('tf_email_address', $user->getEmailAddress()); ?>" size="32" maxlength="64" />
                        </li>
                        <li>
                            <label for="tf_password_base" >new password</label>
                            <input type="password" id="tf_password_base" name="tf_password_base" value="" size="32" maxlength="32" />
                        </li>
                        <li>
                            <label for="tf_password_confirm" >confirm new password</label>
                            <input type="password" id="tf_password_confirm" name="tf_password_confirm" value="" size="32" maxlength="32" />
                        </li>                                 
                    </ul>
                </div>
                <div class="text_field_wrapper right">
                    <ul>
                        <li>
                            <label for="tf_gender" >gender</label>
                            <input type = "radio"
                                   class="css-checkbox"
                                   name = "tf_gender"
                                   id = "male"
                                   value = "male" 
                                   <?php if ( $user->getGender() == 0){ echo 'checked = "checked"' ;}?>
                                   />
                            <label for = "male" class="css-label">male</label>
                            <input type = "radio"
                                   class="css-checkbox"
                                   name = "tf_gender"
                                   id = "female"
                                   value = "female"
                                   <?php if ( $user->getGender() == 1){ echo 'checked = "checked"' ;}?> />
                            <label for = "female" class="css-label">female</label>                                    
                        </li>
                        <li>
                            <label for="tf_phone_number" >phone number</label>
                            <input type="tel" id="tf_phone_number" name="tf_phone_number" value="<?php echo set_value('tf_phone_number', $user->getPhoneNumber()); ?>" size="32" maxlength="32" />
                        </li>
                        <li>
                            <label for="tf_street" >street</label>
                            <input type="text" id="tf_street" name="tf_street" value="<?php echo set_value('tf_street',  $address->getStreet()); ?>" size="32" maxlength="128"/>
                        </li>
                        <li>
                            <label for="tf_city" >city</label>
                            <input type="text" id="tf_city" name="tf_city" value="<?php echo set_value('tf_city', $address->getCity()); ?>" size="32" maxlength="32" />
                        </li>
                        <li>
                            <label for="tf_zip" >zip</label>
                            <input type="number" id="tf_zip" name="tf_zip" value="<?php echo set_value('tf_zip', $address->getZip()); ?>" size="32" maxlength="16" />
                        </li>
                        <li>
                            <label for="tf_country" >country</label>
                            <input type="text" id="tf_country" name="tf_country" value="<?php echo set_value('tf_country', $address->getCountry()); ?>" size="32" maxlength="32" />
                        </li>                                 
                    </ul>                            
                </div>

            </div>
            <div style="clear:both;"></div>
            <div class="bottom_wrapper">
                <div class="bottom_wrapper left">
                </div>
                <div class="bottom_wrapper right">
                    <!--<input type="button" value="SIGN UP"/>-->
                    <div class="right_pp_button_wrapper">
                        <button class="pp_button_active" type="submit" name="submit">EDIT PROFILE</button>
                    </div>
                </div>
                <div style="clear:both;"></div>
            </div>
            <?php echo form_close(); ?>
        </div>        
    </div>


</div><!-- end of content-->
