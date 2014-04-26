<!-- content -->
<div id="content">
    <div class="content_wrapper">
        <!-- ******************* shopping cart section ******************* -->
        <div class="container">
            <!-- Title -->
            <h1>
                object administration
                <?php echo anchor('c_admin/index', '<-go back', array('class' => 'text_light smaller pp_dark_gray red_on_hover inunderlined upper_cased')); ?>                 
            </h1>
            <div class="blue_line">
            </div>                    
            <div class="half_container">
                <section id="editable" contenteditable="true"><?php echo $company_rules ?></section>
                
                
                <br />
                <div>
                    <input type="button" id="save" onclick="saveChanges()" class="pp_button_active" value="Save changes" />
                </div>
                <script>
                    var editable = document.getElementById('editable');

                    function saveChanges(){
                        
                        new_rules = (editable.textContent===undefined) ? editable.innerText : editable.textContent;
                        
                        var rules_change_data = {
                            new_rules : new_rules,
                            ajax : '4'
                        };                        
                        
                        $.ajax({
                            url: "<?php echo site_url('c_admin/update_rules'); ?>",
                            type: 'POST',
                            async : false,
                            data: rules_change_data,
                            beforeSend: function() {
                                $('#loading_gif').show();
                            },
                            success: function(success) {
                            alert(success);
                                if( success == -1){
                                    alert('Cannot find company in DB.');
                                } else if( success == 0){
                                    alert('Rules to be changed are the same as current rules.');
                                }else if (success == 1){
                                    alert('New rules saved succesfully!');
                                    window.location.href = "<?php echo site_url('c_admin/rules'); ?>";
                                }else{
                                    alert('Response not recognized!');
                                };                            
                            }
                        });                    
                    };
                </script>                  
            </div>

        </div>        
    </div>


</div><!-- end of content-->
