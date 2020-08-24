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
                <div class="module-live-edit-settings module-shop-cart-settings">
                    <div class="form-group">
                        <label class="control-label d-block"><?php _e("Checkout link enabled"); ?> ?</label>

                        <?php $checkout_link_enanbled = get_option('data-checkout-link-enabled', $params['id']); ?>
                        <select name="data-checkout-link-enabled" class="mw_option_field selectpicker" data-width="100%">
                            <option value="y" <?php if (('n' != strval($checkout_link_enanbled))): ?>  selected="selected"  <?php endif; ?>><?php _e("Yes"); ?></option>
                            <option value="n" <?php if (('n' == strval($checkout_link_enanbled))): ?>  selected="selected"  <?php endif; ?>><?php _e("No"); ?></option>
                        </select>
                    </div>

                    <div class="form-group">

                        <label class="control-label d-block"><?php _e("Use Checkout Page From"); ?></label>
                        <?php $selected_page = get_option('data-checkout-page', $params['id']); ?>

                        <select name="data-checkout-page" class="mw_option_field selectpicker" data-width="100%" data-size="5">
                            <option value="default" <?php if ((0 == intval($selected_page)) or ('default' == strval($selected_page))): ?>   selected="selected"  <?php endif; ?>><?php _e("Default"); ?></option>
                            <?php
                            $pt_opts = array();
                            $pt_opts['link'] = "{title}";
                            $pt_opts['list_tag'] = " ";
                            $pt_opts['is_shop'] = "y";
                            $pt_opts['list_item_tag'] = "option";
                            $pt_opts['active_ids'] = $selected_page;
                            $pt_opts['active_code_tag'] = '   selected="selected"  ';
                            pages_tree($pt_opts);
                            ?>
                        </select>
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