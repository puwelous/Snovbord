        <!-- footer -->
        <div id="footer" class="f_inactive">
            <div class="colmask threecol">
                <div class="colmid">
                    <div class="colleft">
                        <div class="col1">
                            <!-- Column 1 start -->
                            <div class="text_wrapper_middle_part inactive">
                                <div class="text_light smaller black">Copyright &copy; 2013 Powporn. All rights reserved.</div>
                            </div>
                            <!-- Column 1 end -->
                        </div>
                        <div class="col2">
                            <!-- Column 2 start -->
                            <ul class="footer_l inactive">
                                <li><a href="./whatisrealpp.html" class="text_light red_on_hover upper_cased">rules</a></li>
                                <li><a href="./ucreate.html" class="text_light red_on_hover upper_cased">payment</a></li>
                                <li><a href="./finalproducts.html" class="text_light red_on_hover upper_cased">shipping services</a></li>
                                <li><a href="./finalproducts.html" class="text_light red_on_hover upper_cased">CLIENT SERVICES</a></li>
                            </ul>
                            <!-- Column 2 end -->
                        </div>
                        <div class="col3">
                            <!-- Column 3 start -->
                            <div id="footer_switcher_wrapper">
                                <div id="footer_switcher" class="inactive"> 
                                </div>
                            </div>
                            <!-- Column 3 end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script type="text/javascript">
            var validator = new FormValidator('pp_form_name', [{
                    name: 'tf_nick',
                    display: '\"Nick\"',    
                    rules: 'required|min_length[4]|max_length[32]'
                }, {
                    name: 'tf_first_name',
                    display: '\"First Name\"',    
                    rules: 'required|min_length[4]|max_length[32]'
                }, {
                    name: 'tf_last_name',
                    display: '\"Last Name\"',    
                    rules: 'required|min_length[4]|max_length[32]'
                }, {
                    name: 'tf_email_address',
                    display: '\"Email Address\"',
                    rules: 'valid_email||max_length[64]'
                }, {
                name: 'tf_password_base',
                display: '\"Password\"',
                rules: 'required|min_length[4]|max_length[32]'
            }, {
            name: 'tf_password_confirm',
            display: '\"Confirm Password\"',
            rules: 'required|matches[tf_password_base]'
        }, {
            name: 'tf_delivery_addres',
            display: '\"Delivery Address\"',
            rules: 'max_length[256]'
        }, {
            name: 'tf_address',
            display: '\"Address\"',
            rules: 'required|max_length[256]'
        }, {
            name: 'tf_city',
            display: '\"City\"',
            rules: 'required|max_length[64]'
        },{
            name: 'tf_zip',
            display: '\"ZIP\"',
            rules: 'required|max_length[16]'
        },{
            name: 'tf_country',
            display: '\"Country\"',
            rules: 'required|max_length[64]'
        }], function(errors, event) {
                
        if (errors.length > 0) {

            var errorString = '';
         
            for (var i = 0, errorLength = errors.length; i < errorLength; i++) {
                errorString += errors[i].message;
            }
        
            var errorOutputDiv = document.getElementById("error_output_section");
            errorOutputDiv.innerHTML = errorString;
            alert(errorString);
            if (evt && evt.preventDefault) {
                evt.preventDefault();
            } else if (event) {
                event.returnValue = false;
            }
        }else{
            //everything ok
            //                    var validUserEmailElement = document.getElementById("user_email");
            //                    var emailAddressElement = document.getElementById("tf_email_address");
            //
            //                    validUserEmailElement.innerHTML =  emailAddressElement.value;
            //                    
            //                    $('.overlay-bg').show();
        }
    });
            
        </script>

    </body>
</html>