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
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php print _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php print _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="settings">
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-breadcrumb-settings">
                    <?php $selected_start_depth = get_option('data-start-from', $params['id']); ?>

                    <div class="form-group">
                        <label class="control-label"><?php _e("Root level"); ?></label>
                        <div>
                            <select name="data-start-from" class="mw_option_field selectpicker" data-width="100%">
                                <option value='' <?php if ('' == $selected_start_depth): ?>   selected="selected"  <?php endif; ?>><?php _e("Default"); ?></option>
                                <option value='page' <?php if ('page' == $selected_start_depth): ?>   selected="selected"  <?php endif; ?>><?php _e("Page"); ?></option>
                                <?php ?>
                                <option value='category' <?php if ('category' == $selected_start_depth): ?>   selected="selected"  <?php endif; ?>><?php _e("Category"); ?></option>
                                <?php ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Settings Content - End -->
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>