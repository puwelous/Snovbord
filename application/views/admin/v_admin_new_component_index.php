<!-- content -->
<div id="content">
    <div class="content_wrapper">
        <!-- ******************* shopping cart section ******************* -->
        <div class="container">
            <!-- Title -->
            <h1>
                new component administration
                <?php echo anchor('c_admin/components_admin', '<-go back', array('class' => 'text_light smaller pp_dark_gray inunderlined red_on_hover inunderlined upper_cased')); ?>
            </h1>
            <div class="blue_line">
            </div>
            <div class="half_container">

                <?php echo validation_errors(); ?>
                <?php if (isset($error)) echo $error; ?>
                <?php if (isset($successful)) echo $successful; ?>

                <?php echo form_open_multipart('c_admin/new_component_admin_add'); ?>

                <h2 class="black">Component data</h2>
                <div class="line pp_dark_gray"></div>

                <h4 class="black">component name:</h4>
                <?php
                $data = array(
                    'name' => 'ncf_component_name',
                    'id' => 'ncf_component_name',
                    'value' => set_value('ncf_component_name', 'Zip KaI-ER'),
                    'maxlength' => '32',
                    'size' => '32',
                    'style' => 'width:100%'
                );
                echo form_input($data);
                ?>

                <div style="width:100%; display:block;">
                    <h4 class="black" >price&nbsp;(&euro;)</h4>
                    <?php
                    $data = array(
                        'name' => 'ncf_component_price',
                        'id' => 'ncf_component_price',
                        'type' => 'number',
                        'step' => 'any',
                        'value' => set_value('ncf_component_price', '10.00'),
                        'min' => '0',
                        'size' => '8',
                        'style' => 'width:100%'
                    );
                    echo form_input($data);
                    ?>
                </div>  

                <div>
                    <h4 class="black" style="display: inline;">is stable</h4>
                    <?php
                    $data = array(
                        'name' => 'ncf_component_is_stable',
                        'id' => 'ncf_component_is_stable',
                        'value' => 'TRUE',
                        'checked' => TRUE
                    );

                    echo form_checkbox($data);
                    ?>
                </div>                 

                <h4 class="black" >design by (not editable)</h4>
                <?php
                $data = array(
                    'name' => 'ncf_component_creator',
                    'id' => 'ncf_component_creator',
                    'value' => $actual_user_nick,
                    'maxlength' => '64',
                    'size' => '32',
                    'readonly' => '',
                    'style' => 'width:100%'
                );
                echo form_input($data);
                ?>

                <div style="width:100%; display:block;">
                    <h4 class="black" >choose category</h4>
                    <?php
                    echo form_dropdown('ncf_categories', $categories_dropdown, reset($categories_dropdown), 'style="width:100%;"');
                    ?>
                </div>


            </div>
            <div class="half_container">
                <h2 class="black">Graphic component data</h2>
                <div class="line pp_dark_gray"></div>
                <h3 class="black" >Basic view (FRONT) representation</h3>

                <h4 class="black" >Single raster representation:</h4>
                <input type="file" name="userfile" size="20" />

                <h4 class="black" >Possible vector representations:</h4>
                <div id="vector_representations">
                    <input type="text" name="ncf_vector_0" class="full_width">
                    <input type="text" name="ncf_vector_1" class="full_width">
                    <input type="text" name="ncf_vector_2" class="full_width">
                    <input type="text" name="ncf_vector_3" class="full_width">
                    <input type="text" name="ncf_vector_4" class="full_width">
                    <input type="text" name="ncf_vector_5" class="full_width">
                    <input type="text" name="ncf_vector_6" class="full_width">
                    <input type="text" name="ncf_vector_7" class="full_width">
                    <input type="text" name="ncf_vector_8" class="full_width">
                    <input type="text" name="ncf_vector_9" class="full_width">
                </div>

                <!--input for point of view-->
                <!--                <h4 class="black" >Enter new point of view or choose existing one:</h4>
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
                ?>                -->

                <!--last line-->
                <div class="line pp_dark_gray"></div>

                <!--input button-->
                <div>
                    <input type="submit" id="save" class="pp_button_active" value="Create component" />
                </div>
                </form> <!-- end of form-->
            </div>
        </div>
    </div><!-- end of content-->