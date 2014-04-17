        <!-- content -->
        <div id="content">

            <div class="content_wrapper">

                <div class="container">
                    <div class="title upper_cased black">
                        Password reset
                    </div>
                    <div id="error_output_section" class="text_medium capitalized_first_only">
                    </div>

                    <?php echo validation_errors('<p class="error" style="display:inline">'); ?>
                    <?php
                    $attributes = array('name' => 'pp_reset_password_form');
                    echo form_open("c_user/password_reset", $attributes);
                    ?>
                    <div class="text_fields_wrapper">

                        <div class="text_field_wrapper left">
                            <ul>
                                <li>
                                    <label for="rpf_email_address" class="required">email address or nick</label>
                                    <input type="text" id="rpf_email_address_or_nick" name="rpf_email_address_or_nick" placeholder="johny or john_doe@example.com" value="<?php echo set_value('rpf_email_address_or_nick'); ?>" size="32" maxlength="64" />
                                </li>                               
                            </ul>
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="bottom_wrapper">
                        <div class="bottom_wrapper left">
                            <button class="pp_button_active" type="submit" name="submit">RESET PASSWORD</button>
                        </div>
                        <div class="bottom_wrapper right">
                            <!--<input type="button" value="SIGN UP"/>-->
                            <div class="right_pp_button_wrapper">
                                <!--<button class="pp_button_active" type="submit" name="submit">SIGN UP</button>-->
                            </div>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                    <!--                    </form>-->
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div><!-- end of content-->