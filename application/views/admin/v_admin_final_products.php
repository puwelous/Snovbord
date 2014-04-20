<!-- content -->
<div id="content">
    <div class="content_wrapper">
        <!-- ******************* shopping cart section ******************* -->
        <div class="container">
            <!-- Title -->
            <h1>
                product administration
                <?php echo anchor('c_admin/index', '<-go back', array('class' => 'text_light smaller pp_dark_gray inunderlined red_on_hover inunderlined upper_cased')); ?>                 
            </h1>
            <div class="blue_line">
            </div>                    
            <div class="half_container">

                <?php echo validation_errors(); ?>
                <?php if (isset($error)) echo $error; ?>
                <?php if (isset($successful)) echo $successful; ?>

                <?php echo form_open_multipart('c_admin/add_products'); ?>

                <h4 class="black">product name</h4>
                <?php
                $data = array(
                    'name' => 'pdf_product_name',
                    'id' => 'pdf_product_name',
                    'value' => set_value('pdf_product_name', 'PP K2 Bambi'),
                    'maxlength' => '32',
                    'size' => '32',
                    'style' => 'width:100%'
                );
                echo form_input($data);
                ?>
                <div class="line pp_dark_gray"></div>

                <h4 class="black" >design by (not editable)</h4>
                <?php
                $data = array(
                    'name' => 'pdf_product_creator',
                    'id' => 'pdf_product_creator',
                    'value' => $actual_user_nick,
                    'maxlength' => '64',
                    'size' => '32',
                    'readonly' => '',
                    'style' => 'width:100%'
                );
                echo form_input($data);
                ?>
                <div class="line pp_dark_gray"></div>                

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
                    echo form_multiselect('pdf_available_sizes[]', $options, $selected_sizes, 'id="pdf_available_sizes"');
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
                    echo form_dropdown('pdf_product_sexes', $options, $selected_sexes);
                    ?>
                </div>
                <div style="width:31%; display:inline-block;">
                    <h4 class="black" >price&nbsp;(&euro;)</h4>
                    <?php
                    $data = array(
                        'name' => 'pdf_product_price',
                        'id' => 'pdf_product_price',
                        'type' => 'number',
                        'step' => 'any',
                        'value' => set_value('pdf_product_price', '123.45'),
                        'min' => '0',
                        'size' => '8',
                        'style' => 'width:100%'
                    );
                    echo form_input($data);
                    ?>
                </div>                
                <div class="line pp_dark_gray"></div>


                <!--<div class="line pp_dark_gray"></div>-->


                <h4 class="black" >type</h4>
                <?php
                $data = array(
                    'name' => 'pdf_product_type',
                    'id' => 'pdf_product_type',
                    'value' => set_value('pdf_product_type', 'Unbelievably water-proof hoodie made of high-quality material.'),
                    'rows' => '6',
                    'style' => 'max-width:100%; width:100%;'
                );
                echo form_textarea($data);
                ?>
                <div class="line pp_dark_gray"></div>  

                <input type="file" name="userfile" size="20" />
                <div class="line pp_dark_gray"></div>   

                <div>
                    <input type="submit" id="save" class="pp_button_active" value="Create product" />                
                </div>
                </form>
            </div>        
        </div>
    </div><!-- end of content-->