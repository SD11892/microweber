<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <?php
        $text = get_option('text', $params['id']);

        if ($text == false) {
            $text = '<?php print "Hello World"; ?>';
        }
        ?>

        <div class="module-live-edit-settings module-highlight-code-settings">
            <div class="form-group">
                <label class="control-label"><?php _e('Enter some text'); ?></label>
                <textarea class="mw_option_field form-control" rows="20" name="text"><?php print $text; ?></textarea>
            </div>
        </div>
    </div>
</div>
