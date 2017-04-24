<?php

/*

type: layout

name: Default

description: Default

*/
?>

<script>mw.moduleCSS('<?php print module_url(); ?>style.css');</script>
<div class="mw-social-links">

    <?php if ($social_links_has_enabled == false) {
        print lnotif('Social links');
    } ?>


    <?php if ($facebook_enabled) { ?>


        <a href="//facebook.com/<?php print $facebook_url; ?>" target="_blank"><span class="mw-icon-facebook"></span></a>

    <?php } ?>

    <?php if ($twitter_enabled) { ?>

        <a href="//twitter.com/<?php print $twitter_url; ?>" target="_blank"><span class="mw-icon-twitter"></span></a>

    <?php } ?>


    <?php if ($googleplus_enabled) { ?>

        <a href="//plus.google.com/<?php print $googleplus_url; ?>" target="_blank"><span class="mw-icon-googleplus"></span></a>

    <?php } ?>

    <?php if ($pinterest_enabled) { ?>

        <a href="//pinterest.com/<?php print $pinterest_url; ?>" target="_blank"><span class="mw-icon-social-pinterest"></span></a>

    <?php } ?>

    <?php if ($youtube_enabled) { ?>

        <a href="//youtube.com/<?php print $youtube_url; ?>" target="_blank"><span class="mw-icon-social-youtube"></span></a>

    <?php } ?>

    <?php if ($instagram_enabled) { ?>

        <a href="https://instagram.com/<?php print $instagram_url; ?>" target="_blank"><span class="mw-icon-social-instagram"></span></a>

    <?php } ?>

    <?php if ($linkedin_enabled) { ?>

        <a href="//linkedin.com/<?php print $linkedin_url; ?>" target="_blank"><span class="mw-icon-social-linkedin"></span></a>

    <?php } ?>


</div>