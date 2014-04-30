<!-- content -->
<div id="content">
    <div class="content_wrapper">
        <!-- ******************* shopping cart section ******************* -->
        <div class="container">
            <!-- Title -->
            <h1>
                contact administration
                <?php echo anchor('c_admin/index', '<-go back', array('class' => 'text_light smaller pp_dark_gray red_on_hover inunderlined upper_cased')); ?>
            </h1>
            <div class="blue_line">
            </div>                    
            <?php
            $attributes = array('name' => 'pp_form_name');
            echo form_open("c_admin/update_about_us", $attributes);
            ?>
            <div class="text_fields_wrapper">

                <div class="text_field_wrapper left">
                    <ul>
                        <li>
                            <label for="cmpnf_provider_firstname" class="required">first name</label>
                            <input type="text" id="cmpnf_provider_firstname" name="cmpnf_provider_firstname" placeholder="First name" value="<?php if( isset($isFirstTimeRendered) ){ echo $company->cmpn_provider_firstname;} else { echo set_value('cmpnf_nick'); } ?>" size="32" maxlength="32" />
                        </li>
                        <li>
                            <label for="cmpnf_provider_lastname" class="required">last name</label>
                            <input type="text" id="cmpnf_provider_lastname" name="cmpnf_provider_lastname" placeholder="Last name" value="<?php if( isset($isFirstTimeRendered) ){ echo $company->cmpn_provider_lastname;} else { echo set_value('cmpnf_provider_lastname'); } ?>" size="32" maxlength="32" />
                        </li>
                        <li>
                            <label for="cmpnf_provider_street" class="required">street name</label>
                            <input type="text" id="cmpnf_provider_street" name="cmpnf_provider_street" placeholder="Street" value="<?php if( isset($isFirstTimeRendered) ){ echo $company->cmpn_provider_street;} else { echo set_value('cmpnf_provider_street'); } ?>" size="32" maxlength="32" />
                        </li>
                        <li>
                            <label for="cmpnf_provider_street_number" class="required">street number</label>
                            <input type="number" id="cmpnf_provider_street_number" name="cmpnf_provider_street_number" placeholder="007" value="<?php if( isset($isFirstTimeRendered) ){ echo $company->cmpn_provider_street_number;} else { echo set_value('cmpnf_provider_street_number'); } ?>" size="32" maxlength="64" />
                        </li>                             
                    </ul>
                </div>
                <div class="text_field_wrapper right">
                    <ul>
                        <li>
                            <label for="cmpnf_provider_city" class="required">city</label>
                            <input type="text" id="cmpnf_provider_city" name="cmpnf_provider_city" placeholder="City" value="<?php if( isset($isFirstTimeRendered) ){ echo $company->cmpn_provider_city;} else { echo set_value('cmpnf_provider_city'); } ?>" size="32" maxlength="32" />
                        </li>                          
                        <li>
                            <label for="cmpnf_provider_country" class="required">country</label>
                            <input type="text" id="cmpnf_provider_country" name="cmpnf_provider_country" placeholder="Country" value="<?php if( isset($isFirstTimeRendered) ){ echo $company->cmpn_provider_country;} else { echo set_value('cmpnf_provider_country'); } ?>" size="32" maxlength="64" />
                        </li>
                        <li>
                            <label for="cmpnf_provider_email" class="required">email</label>
                            <input type="email" id="cmpnf_provider_email" name="cmpnf_provider_email" placeholder="Email" value="<?php if( isset($isFirstTimeRendered) ){ echo $company->cmpn_provider_email;} else { echo set_value('cmpnf_provider_email'); } ?>" size="32" maxlength="64"/>
                        </li>
                        <li>
                            <label for="cmpnf_provider_phone_number" class="required">phone number</label>
                            <input type="text" id="cmpnf_provider_phone_number" name="cmpnf_provider_phone_number" placeholder="Phone number" value="<?php if( isset($isFirstTimeRendered) ){ echo $company->cmpn_provider_phone_number;} else { echo set_value('cmpnf_provider_phone_number'); } ?>" size="32" maxlength="32" />
                        </li>                                
                    </ul>                            
                </div>

                <div style="clear:both;"></div>
                <button class="pp_button_active" type="submit" name="submit">Save changes</button>
                <?php echo form_close(); ?>
            </div>

        </div>        
    </div>


</div><!-- end of content-->
