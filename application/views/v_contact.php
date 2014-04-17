        <!-- content -->
        <div id="content">
            <div class="content_wrapper">
                <!-- ******************* shopping cart section ******************* -->
                <div class="container">


                    <!-- Title -->
                    <!--<div class="title upper_cased black">-->
                    <h1>
                        about us
                    </h1>
                    <!--</div>-->
                    <div class="red_line">
                    </div>                    
                    <!--<form name="pp_form_name" action="#" method="POST">-->
                    <div class="half_container">
                        <!--<div class="text_medium upper_cased bold">-->
                        <h2>
                            rules
                        </h2>
                        <!--</div>-->
                        <div class="text_light">
                            <?php echo $company->cmpn_rules ?>
                        </div>
                        <div id="video_section">
                            <div id="video_section_container" class="full_video_container">
                                <div class="video_item_wrapper">
                                    <div class="video_item_screen"></div>
                                    <div class="video_item_title text_light smaller upper_cased pp_dark_gray">choose</div>
                                </div>
                                <div class="video_item_wrapper">
                                    <div class="video_item_screen"></div>
                                    <div class="video_item_title text_light smaller upper_cased pp_dark_gray">create</div>
                                </div>
                                <div class="video_item_wrapper">
                                    <div class="video_item_screen"></div>
                                    <div class="video_item_title text_light smaller upper_cased pp_dark_gray">create</div>
                                </div>
                                <div class="video_item_wrapper">
                                    <div class="video_item_screen"></div>
                                    <div class="video_item_title text_light smaller upper_cased pp_dark_gray">create</div>
                                </div>
                            </div>
                        </div> 
                        <div style="clear:both;"></div>
                        <!--<div class="text_medium upper_cased bold">-->
                        <h2>
                            contact
                        </h2>
                        <!--</div>-->
                        <div class="address">
                            <div class="text_light upper_cased"><?php echo $company->cmpn_provider_firstname ?>&nbsp;<?php echo $company->cmpn_provider_lastname ?></div>
                            <div class="text_light upper_cased"><?php echo $company->cmpn_provider_street ?>&nbsp;<?php echo $company->cmpn_provider_street_number ?></div>
                            <div class="text_light upper_cased"><?php echo $company->cmpn_provider_city ?></div>
                            <div class="text_light upper_cased"><?php echo $company->cmpn_provider_country ?></div>
                            <div class="text_light upper_cased"><?php echo $company->cmpn_provider_email ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $company->cmpn_provider_phone_number ?></div>
                        </div>                   
                    </div>
                </div>
            </div>

        </div><!-- end of content-->
