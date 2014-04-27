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
                    <?php echo anchor('c_admin/categories_admin_index', 'categories', array('class' => 'text_light smaller black red_on_hover inunderlined upper_cased')); ?>
                </h2>                 
                <h2>
                    <?php echo anchor('c_admin/new_component_admin_index', 'add component', array('class' => 'text_light smaller black red_on_hover inunderlined upper_cased')); ?>
                </h2> 
                <h2>
                    <?php echo anchor('c_admin/qualify_component_admin', 'qualify components', array('class' => 'text_light smaller black red_on_hover inunderlined upper_cased')); ?>
                </h2>                 
            </div>
        </div>
    </div><!-- end of content-->