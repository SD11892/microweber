<?php

/*

type: layout

name: Testimonial

position: 6

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="nodrop safe-mode testimonial testimonial-3 edit <?php print $padding ?>" field="layout-skin-6-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-push-6">
                <div class="testimonial__text allow-drop">
                    <blockquote class="safe-element">
                        &ldquo;<?php _lang("I was a blown away by the design quality and sheer level of attention-to-detail, sweet", "templates/dream"); ?>!&rdquo;
                    </blockquote>
                    <h5>&mdash; <?php _lang("Dan Gibson", "templates/dream"); ?></h5>
                    <span class="h6 safe-element"><?php _lang("Interface Designer", "templates/dream"); ?></span>
                </div>
            </div>
            <div class="col-sm-6 col-sm-pull-6">
                <img alt="avatar" src="<?php print template_url('assets/img/'); ?>avatar-large-2.png"/>
            </div>
        </div>
    </div>
</section>