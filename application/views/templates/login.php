                <li id="m_login">
                    <?php echo anchor('welcome', 'log in', array('class' => 'text_light smaller  pp_dark_gray red_on_hover upper_cased')); ?>
                    <div id="login_wrapper"><?php echo form_open('user/login'); ?>
                        <div class="login_wrapper_single_row">
                            <span class="text_light smaller bold upper_cased black">name/email</span>
                            <input id="login_nick_or_email" name="login_nick_or_email" type="text" placeholder="your nick or email"/>
                        </div>
                        <div style="clear:both;"></div>
                        <div class="login_wrapper_single_row">
                            <span class="text_light smaller bold upper_cased black">password</span>
                            <input id="login_password" name="login_password" type="password" placeholder="your password"/>
                        </div>
                        <div style="clear:both;"></div>
                        <div class="login_wrapper_single_row">
                            <!--<span class="text_light smaller bold black">forgot your password?</span>-->
                            <?php echo anchor('user/password_reset', 'forgot your password?', array('class' => 'text_light smaller bold black')); ?>
                            <?php echo anchor('registration', 'new registration', array('class' => 'text_light smaller pp_dark_gray bold pp_red upper_cased right')); ?>
                        </div>
                        <div style="clear:both;"></div>

                        <div id="login_result_message">
                        </div>
                        <?php echo form_close(); ?>
                    </div>                    
                </li>
