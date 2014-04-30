<!-- content -->
<div id="content">
    <div class="content_wrapper">
        <!-- ******************* shopping cart section ******************* -->
        <div class="container">
            <!-- Title -->
            <h1>
                new product administration
                <?php echo anchor('c_admin/products_admin', '<-go back', array('class' => 'text_light smaller pp_dark_gray inunderlined red_on_hover inunderlined upper_cased')); ?>
            </h1>
            <div class="blue_line">
            </div>
            <div class="half_container">

                <?php echo validation_errors(); ?>
                <?php if (isset($error)) echo $error; ?>
                <?php if (isset($successful)) echo $successful; ?>

                <?php echo form_open_multipart('c_admin/new_product_admin_add'); ?>

                <h2 class="black">Product data</h2>
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

                <div style="width:100%; display:block">
                    <h4 class="black" >available size</h4>
                    <?php
                    $options = array(
                        'small' => 'small',
                        'medium' => 'medium',
                        'large' => 'large',
                        'xlarge' => 'xlarge'
                    );
                    $selected_sizes = array('small', 'medium');
                    echo form_multiselect('npf_available_sizes[]', $options, $selected_sizes, 'id="npf_available_sizes" style="width:100%;"');
                    ?>
                </div>
                <div style="width:100%; display:block;">
                    <h4 class="black" >available sex</h4>
                    <?php
                    $options = array(
                        'male' => 'male',
                        'female' => 'female',
                        'unisex' => 'unisex'
                    );
                    $selected_sexes = array('male');
                    echo form_dropdown('npf_product_sexes', $options, $selected_sexes, 'style="width:100%;"');
                    ?>
                </div>
                <div style="width:100%; display:block;">
                    <h4 class="black" >price&nbsp;(&euro;, calculated)</h4>
                    <?php
                    $data = array(
                        'name' => 'npf_product_price',
                        'id' => 'npf_product_price',
                        'type' => 'number',
                        'step' => 'any',
                        'value' => set_value('npf_product_price', '100.00'),
                        'min' => '0',
                        'size' => '8',
                        'readonly' => '',
                        'style' => 'width:100%'
                    );
                    echo form_input($data);
                    ?>
                </div>
                <!--<div class="line pp_dark_gray"></div>-->

                <h4 class="black" >description</h4>
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
            </div>
            <div class="half_container">
                <h2 class="black">Basic product data</h2>
                <h4 class="black" >price&nbsp;(&euro;, calculated)</h4>
                <?php
                $data = array(
                    'name' => 'npf_basic_product_price',
                    'id' => 'npf_basic_product_price',
                    'type' => 'number',
                    'step' => 'any',
                    'value' => set_value('npf_basic_product_price', '100.00'),
                    'min' => '0',
                    'size' => '8',
                    'style' => 'width:100%',
                    'onkeyup' => "checkAndAppend()"
                );
                echo form_input($data);
                ?>

                <h3 class="black" >Basic view (FRONT) representation</h3>

                <h4 class="black" >Single raster representation:</h4>
                <input type="file" name="userfile" size="20" />

                <h4 class="black" >Possible vector representations:</h4>
                <div id="vector_representations">
                    <input type="text" name="npf_vector_0" class="full_width">
                    <input type="text" name="npf_vector_1" class="full_width">
                    <input type="text" name="npf_vector_2" class="full_width">
                    <input type="text" name="npf_vector_3" class="full_width">
                    <input type="text" name="npf_vector_4" class="full_width">
                    <input type="text" name="npf_vector_5" class="full_width">
                    <input type="text" name="npf_vector_6" class="full_width">
                    <input type="text" name="npf_vector_7" class="full_width">
                    <input type="text" name="npf_vector_8" class="full_width">
                    <input type="text" name="npf_vector_9" class="full_width">
                </div>

                <!--last line-->
                <div class="line pp_dark_gray"></div>

                <!--input button-->
                <div>
                    <input type="submit" id="save" class="pp_button_active" value="Create product" />
                </div>
                </form> <!-- end of form-->
            </div>
        </div>
        <script>
            function checkAndAppend()
            {
                //var actualValue = document.getElementById("npf_basic_product_price").value;
                document.getElementById("npf_product_price").value = document.getElementById("npf_basic_product_price").value;
            }            
        </script>
    </div><!-- end of content-->