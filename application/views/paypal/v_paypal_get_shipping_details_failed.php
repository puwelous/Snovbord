<!-- content -->
<div id="content">

    <div class="content_wrapper">
        <div class="container">
            <div class="title black">
                Your PayPal Express Checkout seems to failed! Check error message if included below.
            </div>
            <div class="text_light black">
                <?php
                if (isset($error_message)) {
                    echo $error_message;
                }
                ?>
            </div>                    
        </div>
    </div>
</div><!-- end of content-->