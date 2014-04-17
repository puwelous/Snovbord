<!-- image_section -->
<div id="image_section">
    <?php
    // gives <img src="http://site.com/images/picture.jpg" />
    echo img('assets/images/main_page_shoots/0.JPG');
    echo img('assets/images/main_page_shoots/1.JPG');
    echo img('assets/images/main_page_shoots/2.JPG');
    echo img('assets/images/main_page_shoots/3.JPG');
    ?>
</div>

<script src="<?php echo base_url(); ?>assets/javascript/fancybox/html5lightbox.js" text='text/javascript'></script>

<div id="video_section">
    <div id="video_section_container" class="half_video_container">
        <!--<a href="http://www.youtube.com/embed/YE7VzlLtp-4?rel=0&amp;vq=hd1080" data-group="mygroup" class="html5lightbox" title="How to create">how to create</a>-->
        <!--                <div class="video_item_wrapper">
                            <div class="video_item_screen"></div>
                            <div class="video_item_title text_medium upper_cased pp_dark_gray">what is powporn</div>
                        </div>-->
        <!--<iframe width="127" height="110" src="//www.youtube.com/embed/HNqmU5g3tYc" frameborder="0" allowfullscreen></iframe>-->
        <!--<a href="http://www.youtube.com/embed/YE7VzlLtp-4?rel=0&amp;vq=hd1080" data-group="mygroup" class="html5lightbox" title="What is pp">What is PP</a>-->
<!--        <div class="video_item_wrapper">
            <div class="video_item_screen"></div>
            <div class="video_item_title text_medium upper_cased pp_dark_gray">how to create</div>
        </div>-->

        <!--        <div class="video_item_wrapper">
                    <div class="video_item_screen"></div>
                    <div class="video_item_title text_medium upper_cased pp_dark_gray">how we make</div>
                </div>-->
        <!--<a href="http://www.youtube.com/embed/YE7VzlLtp-4?rel=0&amp;vq=hd1080" data-group="mygroup" class="html5lightbox" title="How we make">how we make</a>-->
<!--        <div class="video_item_wrapper">
            <div class="video_item_screen"></div>
            <div class="video_item_title text_medium upper_cased pp_dark_gray">enjoy it</div>
        </div>-->
<!--<a href="http://www.youtube.com/embed/YE7VzlLtp-4?rel=0&amp;vq=hd1080" data-group="mygroup" class="html5lightbox" title="Enjoy it">Enjoy it</a>-->
<a href="JavaScript:html5Lightbox.showLightbox(3, 'http://www.youtube.com/embed/YE7VzlLtp-4', 'What is pp');">What is pp</a>
<a href="JavaScript:html5Lightbox.showLightbox(3, 'http://www.youtube.com/embed/YE7VzlLtp-4', 'How to create');">How to create</a>
<a href="JavaScript:html5Lightbox.showLightbox(3, 'http://www.youtube.com/embed/YE7VzlLtp-4', 'How to make');">How we make</a>
<a href="JavaScript:html5Lightbox.showLightbox(3, 'http://www.youtube.com/embed/YE7VzlLtp-4', 'Enjoy it');">Enjoy it</a>
    </div>
</div>

<?php
//js
echo link_tag('assets/javascript/jquery.mCustomScrollbar.concat.min.js');
?>
<!--</body>
</html>-->
