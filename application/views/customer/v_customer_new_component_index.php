<!-- content -->
<script>
    $(document).ready(function(){

        $(".possible_component_colour").click( function(){
            if(  $(this).hasClass( "notselected" ) ){
                var colour = $(this).data("colour").toString();
                var input_element = $("input[data-colour='" + colour + "']");
                
                // set value
                input_element.val(colour)
                
                // visualize changes
                $(this).removeClass('notselected');
                $(this).addClass('selected');

            }else{
                var colour = $(this).data("colour").toString();
                var input_element = $("input[data-colour='" + colour + "']");
                
                // set value
                input_element.val('false')
                
                // visualize changes
                $(this).removeClass('selected');
                $(this).addClass('notselected');
            }        
        });        
    });
    
  
</script>
<!--content-->
<div id="content">
    <div class="content_wrapper">
        <!-- ******************* shopping cart section ******************* -->
        <div class="container">
            <!-- Title -->
            <h1>
                customer's new component
                <?php echo anchor('c_customer/components_customer', '<-go back', array('class' => 'text_light smaller pp_dark_gray inunderlined red_on_hover inunderlined upper_cased')); ?>
            </h1>
            <div class="blue_line">
            </div>
            <div class="half_container">

                <?php echo validation_errors(); ?>
                <?php if (isset($error)) echo $error; ?>
                <?php if (isset($successful)) echo $successful; ?>

                <?php echo form_open_multipart('c_customer/new_component_customer_add'); ?>

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
                <h4 class="black" >choose possible vector colours(if any)</h4>
                <div class="component_colour_possibilities">
                    <div class="possible_component_colour notselected" data-colour ="ffff80" style="background-color: #ffff80"></div>
                    <div class="possible_component_colour notselected" data-colour ="ffff00" style="background-color: #ffff00"></div>
                    <div class="possible_component_colour notselected" data-colour ="ff8000" style="background-color: #ff8000"></div>
                    <div class="possible_component_colour notselected" data-colour ="80ff80" style="background-color: #80ff80"></div>
                    <div class="possible_component_colour notselected" data-colour ="50f050" style="background-color: #50f050"></div>
                    <div class="possible_component_colour notselected" data-colour ="56c232" style="background-color: #56c232"></div>                      
                    
                    <div class="possible_component_colour notselected" data-colour ="80ffff" style="background-color: #80ffff"></div>
                    <div class="possible_component_colour notselected" data-colour ="009be6" style="background-color: #009be6"></div>
                    
                    <div class="possible_component_colour notselected" data-colour ="0000a0" style="background-color: #0000a0"></div>
                    <div class="possible_component_colour notselected" data-colour ="56c232" style="background-color: #ff0000"></div>
                    <div class="possible_component_colour notselected" data-colour ="ff00ff" style="background-color: #ff00ff"></div>
                    <div class="possible_component_colour notselected" data-colour ="c66300" style="background-color: #c66300"></div>                    
                </div>
                <input type="hidden" name="colour_0" data-colour="ffff80" value="false" />
                <input type="hidden" name="colour_1" data-colour="ffff00" value="false" />
                <input type="hidden" name="colour_2" data-colour="ff8000" value="false" />
                <input type="hidden" name="colour_3" data-colour="80ff80" value="false" />
                <input type="hidden" name="colour_4" data-colour="50f050" value="false" />
                <input type="hidden" name="colour_5" data-colour="56c232" value="false" />
                <input type="hidden" name="colour_6" data-colour="80ffff" value="false" />
                <input type="hidden" name="colour_7" data-colour="009be6" value="false" />
                <input type="hidden" name="colour_8" data-colour="0000a0" value="false" />
                <input type="hidden" name="colour_9" data-colour="56c232" value="false" />
                <input type="hidden" name="colour_10" data-colour="ff00ff" value="false" />
                <input type="hidden" name="colour_11" data-colour="c66300" value="false" />
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