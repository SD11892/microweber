<?php only_admin_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {

        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _e("Saved"); ?>.");
        });
    });
</script>

<?php
$mod_id = 'newsletter';
if (isset($params['for_module_id'])) {
    $mod_id = $params['for_module_id'];
}
?>

<div class="mw-ui-field-holder">
    <label class="mw-ui-check">
        <input type="checkbox" parent-reload="true" name="require_terms" value="y" data-value-unchecked="n" data-value-checked="y" class="mw_option_field" option-group="newsletter"
            <?php if (get_option('require_terms', 'newsletter') == 'y'): ?>   checked="checked"  <?php endif; ?>
        />
        <span></span><span><?php _e("Users must agree to the Terms and Conditions"); ?></span> </label>
</div>
