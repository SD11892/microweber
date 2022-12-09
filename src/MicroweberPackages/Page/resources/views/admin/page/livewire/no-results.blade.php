<div class="no-items-found">
    <?php

    $url = route('admin.page.create');
    ?>

    <div class="row">
        <div class="col-12">
            <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_products.svg'); ">
                <h4><?php _e('You don’t have any pages'); ?></h4>
                <p><?php _e('Create your first page right now.');?> <br/>
                    <?php _e( 'You are able to do that in very easy way!'); ?></p>
                <br/>
                <a href="<?php print $url; ?>" class="btn btn-primary btn-rounded"><?php _e('Create a Page'); ?></a>
            </div>
        </div>
    </div>

</div>
