<?php
must_have_access();
?>

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

    <div class="card-body h-100 d-flex align-items-center justify-content-center flex-column">
        <div class="icon-title d-inline-flex">
            <i class="mdi mdi-view-grid-plus"></i> <h5 class="mb-0"><?php _e('This module has no settings'); ?></h5>
        </div>
    </div>
</div>