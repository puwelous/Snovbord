<!-- content -->
<div id="content">
    <div class="content_wrapper">
        <!-- ******************* shopping cart section ******************* -->
        <div class="container">
            <!-- Title -->
            <h1>
                My orders
                <?php echo anchor('c_customer/index', '<-go back', array('class' => 'text_light smaller pp_dark_gray inunderlined red_on_hover inunderlined upper_cased')); ?>                 
            </h1>
            <div class="blue_line">
            </div>
            <!--<div class="half_container">-->
                <?php echo $table_data; ?>
            <!--</div>-->        
        </div>
    </div><!-- end of content-->