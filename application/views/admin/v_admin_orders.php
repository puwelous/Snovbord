<!-- content -->
<div id="content">
    <div class="content_wrapper">
        <!-- ******************* shopping cart section ******************* -->
        <div class="container">
            <!-- Title -->
            <h1>
                admin interface
                <?php echo anchor('c_admin/index', '<-go back', array('class' => 'text_light smaller pp_dark_gray red_on_hover inunderlined upper_cased')); ?>                
            </h1>
            <div class="blue_line">
            </div>                    
            <div class="half_container">
                <h2>
                    <?php echo anchor('c_admin/order_admin/open', 'open orders', array('class' => 'text_light smaller black red_on_hover inunderlined upper_cased')); ?>
                </h2>
                <h2>
                    <?php echo anchor('c_admin/order_admin/paid', 'paid orders', array('class' => 'text_light smaller black red_on_hover inunderlined upper_cased')); ?>
                </h2>
                <h2>
                    <?php echo anchor('c_admin/order_admin/shipping', 'shipping orders', array('class' => 'text_light smaller black red_on_hover inunderlined upper_cased')); ?>
                </h2>                  
            </div>
        </div>
    </div>

</div><!-- end of content-->
