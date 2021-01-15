<?php must_have_access(); ?>

<script type="text/javascript">
    mw.require('options.js');
    mw.require('forms.js');
</script>

<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
        });
    });
</script>

<div class="<?php print $config['module_class'] ?>">
    <?php $data = get_option('current_template', 'template', 1); ?>
    <?php
    if (!isset($data['id'])) {
        $data['id'] = 0;
    }
    if (!isset($data['option_value'])) {
        $data['option_value'] = 'default';
    }
    if (!isset($data['option_key'])) {
        $data['option_key'] = 'current_template';
    }
    ?>


    <script type="text/javascript">
        function mw_set_default_template() {
            var el1 = mw.$('.mw-site-theme-selector').find("[name='<?php print  $data['option_key']; ?>']")[0];
            mw.options.save(el1, function () {
                mw.notification.success("<?php _ejs("Template settings are saved"); ?>.");
            });
        }

        $(document).ready(function () {
            $(window).on('templateSelected', function () {
                $(".mw-site-theme-selector").find("[name='active_site_template'] option:selected").each(function (index) {
                    $("#mw_curr_theme_val").val($(this).val());
                });
            });
        });
    </script>

    <div class="mw-site-theme-selector">
        <input id="mw_curr_theme_val" name="current_template" class="mw_option_field mw-ui-field" type="hidden" option-group="template" value="<?php print  $data['option_value']; ?>" data-id="<?php print  $data['id']; ?>"/>
        <module type="content/views/layout_selector" show_save_changes_buttons="true" show_full="true" data-active-site-template="<?php print $data['option_value'] ?>" autoload="1" xxlive_edit_styles_check="1" no-default-name="true"/>
    </div>
</div>
