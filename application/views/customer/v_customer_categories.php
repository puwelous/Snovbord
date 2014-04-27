<!-- content -->
<div id="content">
    <div class="content_wrapper">
        <!-- ******************* shopping cart section ******************* -->
        <div class="container">
            <!-- Title -->
            <h1>
                Categories administration
                <?php echo anchor('c_customer/components_customer', '<-go back', array('class' => 'text_light smaller pp_dark_gray red_on_hover inunderlined upper_cased')); ?>                 
            </h1>
            <div class="blue_line">
            </div>                    
            <div class="half_container">

                <?php echo validation_errors(); ?>
                <?php if (isset($error)) echo $error; ?>
                <?php if (isset($successful)) echo $successful; ?>

                <?php echo form_open('c_customer/add_category'); ?>

                <h3 class="black">Add new category</h3>
                <h4 class="black">New category name:</h4>
                <?php
                $data = array(
                    'name' => 'ncf_name',
                    'id' => 'ncf_name',
                    'value' => set_value('ncf_name', 'Zips'),
                    'maxlength' => '32',
                    'size' => '32',
                    'style' => 'width:100%'
                );
                echo form_input($data);
                ?>
                
                <h4 class="black">New category description</h4>
                <?php
                $data = array(
                    'name' => 'ncf_description',
                    'id' => 'ncf_description',
                    'value' => set_value('ncf_description', 'Standard winter hoodies zip pockets'),
                    'rows' => '6',
                    'style' => 'max-width:100%; width:100%;'
                );
                echo form_textarea($data);
                ?>                
                <div class="line pp_dark_gray"></div>
                <div>
                    <input type="submit" id="save" class="pp_button_active" value="Create category" />                
                </div>
                </form>
            </div>        
        </div>
    </div><!-- end of content-->