<?php

/*

type: layout

name: CTA Basic

position: 56

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="cta cta-6 safe-mode nodrop edit <?php print $padding ?>" field="layout-skin-56-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center allow-drop">
                <h3><?php _lang("Start building with Dream", "templates/dream"); ?></h3>
                <p class="lead"><?php _lang("We'd love to hear from you to discuss web design, product development or to hear your new startup idea.", "templates/dream"); ?></p>

                <module type="btn" text="<?php _lang("Lets Talk", "templates/dream"); ?>"/>
            </div>
        </div>
    </div>
</section>