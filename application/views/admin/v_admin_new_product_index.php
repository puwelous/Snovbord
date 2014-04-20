<!-- content -->
<div id="content">
    <div class="content_wrapper">
        <!-- ******************* shopping cart section ******************* -->
        <div class="container">
            <!-- Title -->
            <h1>
                product administration
                <?php echo anchor('c_admin/products_admin', '<-go back', array('class' => 'text_light smaller pp_dark_gray inunderlined red_on_hover inunderlined upper_cased')); ?>
            </h1>
            <div class="blue_line">
            </div>
            <div class="half_container">

                <?php echo validation_errors(); ?>
                <?php if (isset($error)) echo $error; ?>
                <?php if (isset($successful)) echo $successful; ?>

                <?php echo form_open_multipart('c_admin/new_product_admin_add'); ?>

                <h3 class="black">basic data</h3>
                <div class="line pp_dark_gray"></div>

                <h4 class="black">product name:</h4>
                <?php
                $data = array(
                    'name' => 'npf_product_name',
                    'id' => 'npf_product_name',
                    'value' => set_value('npf_product_name', 'Snovbord Hoodie I.'),
                    'maxlength' => '32',
                    'size' => '32',
                    'style' => 'width:100%'
                );
                echo form_input($data);
                ?>
                <!--<div class="line pp_dark_gray"></div>-->

                <h4 class="black" >design by (not editable)</h4>
                <?php
                $data = array(
                    'name' => 'npf_product_creator',
                    'id' => 'npf_product_creator',
                    'value' => $actual_user_nick,
                    'maxlength' => '64',
                    'size' => '32',
                    'readonly' => '',
                    'style' => 'width:100%'
                );
                echo form_input($data);
                ?>
                <!--<div class="line pp_dark_gray"></div>-->

                <div style="width:31%; display:inline-block">
                    <h4 class="black" >available size</h4>
                    <?php
                    $options = array(
                        'small' => 'small',
                        'medium' => 'medium',
                        'large' => 'large',
                        'xlarge' => 'xlarge'
                    );
                    $selected_sizes = array('small', 'medium');
                    echo form_multiselect('npf_available_sizes[]', $options, $selected_sizes, 'id="npf_available_sizes"');
                    ?>
                </div>
                <div style="width:31%; display:inline-block;">
                    <h4 class="black" >available sex</h4>
                    <?php
                    $options = array(
                        'male' => 'male',
                        'female' => 'female',
                        'unisex' => 'unisex'
                    );
                    $selected_sexes = array('male');
                    echo form_dropdown('npf_product_sexes', $options, $selected_sexes);
                    ?>
                </div>
                <div style="width:31%; display:inline-block;">
                    <h4 class="black" >price&nbsp;(&euro;)</h4>
                    <?php
                    $data = array(
                        'name' => 'npf_product_price',
                        'id' => 'npf_product_price',
                        'type' => 'number',
                        'step' => 'any',
                        'value' => set_value('npf_product_price', '123.45'),
                        'min' => '0',
                        'size' => '8',
                        'style' => 'width:100%'
                    );
                    echo form_input($data);
                    ?>
                </div>
                <!--<div class="line pp_dark_gray"></div>-->

                <h4 class="black" >type</h4>
                <?php
                $data = array(
                    'name' => 'npf_product_desc',
                    'id' => 'npf_product_desc',
                    'value' => set_value('npf_product_desc', 'Unbelievably water-proof hoodie made of high-quality material.'),
                    'rows' => '6',
                    'style' => 'max-width:100%; width:100%;'
                );
                echo form_textarea($data);
                ?>
                <!--<div class="line pp_dark_gray"></div>-->

                <input type="file" name="userfile" size="20" />
                <!--<div class="line pp_dark_gray"></div>-->


                <!--input for point of view-->
                <h4 class="black" >Enter new point of view or choose existing one:</h4>
                <input type = "radio"
                       name = "npf_is_point_of_view_present"
                       id = "old_pov"
                       value = "old_pov" 
                       checked = "checked" />
                <label for = "old_pov" >already existing point of view</label>
                
                <?php
                $selected_item = current($with_value_included_array);
                echo form_dropdown('npf_present_povs', $with_value_included_array, $selected_item);
                ?>

                <br />
                
                <input type = "radio"
                       name = "npf_is_point_of_view_present"
                       id = "new_pov"
                       value = "new_pov"
                       />
                <label for = "new_pov" >new point of view</label>


                <?php
                $data = array(
                    'name' => 'npf_point_of_view_name',
                    'id' => 'npf_point_of_view_name',
                    'value' => set_value('npf_point_of_view_name', 'front'),
                    'maxlength' => '64',
                    'size' => '64',
                    'style' => 'width:30%'
                );
                echo form_input($data);
                ?>                

                <!--last line-->
                <div class="line pp_dark_gray"></div>

                <!--input button-->
                <div>
                    <input type="submit" id="save" class="pp_button_active" value="Create product" />
                </div>
                </form> <!-- end of form-->
            </div>
        </div>
    </div><!-- end of content-->