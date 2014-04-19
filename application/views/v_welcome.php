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

<!--<div id="video_section">
    <div id="video_section_container" class="half_video_container">
        <a href="JavaScript:html5Lightbox.showLightbox(3, 'http://www.youtube.com/embed/YE7VzlLtp-4', 'What is pp');">What is pp</a>
        <a href="JavaScript:html5Lightbox.showLightbox(3, 'http://www.youtube.com/embed/YE7VzlLtp-4', 'How to create');">How to create</a>
        <a href="JavaScript:html5Lightbox.showLightbox(3, 'http://www.youtube.com/embed/YE7VzlLtp-4', 'How to make');">How we make</a>
        <a href="JavaScript:html5Lightbox.showLightbox(3, 'http://www.youtube.com/embed/YE7VzlLtp-4', 'Enjoy it');">Enjoy it</a>
    </div>
</div>-->

<?php
//js
echo link_tag('assets/javascript/jquery.mCustomScrollbar.concat.min.js');
?>
<!--</body>
</html>-->
