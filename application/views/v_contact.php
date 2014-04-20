        <!-- content -->
        <div id="content">
            <div class="content_wrapper">
                <!-- ******************* shopping cart section ******************* -->
                <div class="container">


                    <!-- Title -->
                    <!--<div class="title upper_cased black">-->
                    <h1>
                        Info
                    </h1>
                    <!--</div>-->
                    <div class="blue_line">
                    </div>                    
                    <!--<form name="pp_form_name" action="#" method="POST">-->
                    <div class="half_container">
                        <!--<div class="text_medium upper_cased bold">-->
                        <h2>
                            Object
                        </h2>
                        <!--</div>-->
                        <div class="text_light">
                            <?php echo $company->cmpn_rules ?>
                        </div>

                        <!--<div class="text_medium upper_cased bold">-->
                        <br />
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
