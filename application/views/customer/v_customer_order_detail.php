<!-- content -->
<div id="content">
    <div class="content_wrapper">
        <!-- ******************* shopping cart section ******************* -->
        <div class="container">
            <!-- Title -->
            <h1>
                order detail
                <?php echo anchor('c_customer/orders', '<-go back', array('class' => 'text_light smaller pp_dark_gray inunderlined red_on_hover inunderlined upper_cased')); ?>                 
            </h1>
            <div class="blue_line">
            </div>                 
            <h3>Order info:</h3>
            <?php echo $table_data_order; ?>
            <br />
            <h3>User info:</h3>
            <?php echo $table_data_user; ?>
            <br />
            <h3>User's address info:</h3>
            <?php echo $table_data_address; ?>
            <br />             
            <h3>Ordered products info:</h3>
            <?php echo $table_data_ordered_products; ?>

        </div>
    </div><!-- end of content-->