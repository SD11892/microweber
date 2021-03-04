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
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo _e($module_info['name']); ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <div class="settings-wrapper">
            <script type="text/javascript">
                mw.require("custom_fields.js", true);
                mw.require("options.js", true);
                mw.require("admin.js", true);
                mw.require("admin_custom_fields.js", true);
            </script>

            <?php
            $for = 'module';
            $for_id = false;

            $copy_from = false;
            $suggest_from_rel = false;
            $list_preview = false;

            if (isset($params['for'])) {
                $for = $params['for'];
            }

            if (isset($params['copy_from'])) {
                $copy_from = $params['copy_from'];
            }

            $hide_preview = '';
            if (isset($params['live_edit'])) {
                $hide_preview = " live_edit=true ";
            }

            if (isset($params['suggest-from-related']) and $params['suggest-from-related'] != 'false') {
                $suggest_from_rel = true;
            }

            if (isset($params['list-preview']) and $params['list-preview'] != 'false') {
                $list_preview = true;
            }

            if (isset($params['data-content-id'])) {
                $for_id = $params['data-content-id'];
                $for = 'content';
            } else if (isset($params['content-id'])) {
                $for_id = $for_module_id = $params['rel_id'] = $params['content-id'];
                $for = 'content';
                $for = 'content';
            } elseif (isset($params['content_id'])) {
                $for_id = $params['content_id'];
                $for = 'content';
            } elseif (isset($params['for_id'])) {
                $for_id = $params['for_id'];
            } elseif (isset($params['rel_id'])) {
                $for_id = $module_id = $params['rel_id'];
            } else if (isset($params['for_id'])) {
                $for_id = $params['for_id'];
            } else if (isset($params['for-id'])) {
                $for_id = $params['for-id'];
            } else if (isset($params['parent-module-id'])) {
                $for_id = $params['parent-module-id'];
            } else if (isset($params['data-id'])) {
                $for_id = $params['data-id'];
            } else if (isset($params['id'])) {
                $for_id = $params['id'];
            } elseif (isset($params['data-id'])) {
                $for_id = $module_id = $params['data-id'];
            }

            $fields = mw()->ui->custom_fields();
            ?>

            <script type="text/javascript">
                function addCustomFieldByVal(fieldName) {
                    $('.js-cf-options').val(fieldName);
                    $('.js-cf-options ').trigger('change');
                }

                $(document).ready(function () {
                    mw.dropdown();
                    mw.$('.js-cf-options').on('change', function () {
                        var val = $(this).val();
                        var copyof = mw.$('.js-cf-options li[value="' + val + '"][data-copyof]').dataset('copyof');
                        copyof = false;
                        if (copyof == false) {
                            var make_field = {}
                            make_field.rel_type = '<?php print $for; ?>';
                            make_field.rel_id = '<?php print $for_id; ?>';
                            make_field.type = val;
                            mw.custom_fields.create(make_field, mw_custom_fileds_changed_callback);
                        } else {
                            mw.custom_fields.copy_field_by_id(copyof, '<?php print $for; ?>', '<?php print $for_id; ?>');
                        }
                    });
                });

                mw_custom_fileds_changed_callback = function (el) {
                    mw.tools.loading('#quick-add-post-options-items-holder-container');
                    mw.reload_module('#mw_custom_fields_list_preview', function () {
                        mw.admin.custom_fields.initValues();
                        mw.tools.loading('#quick-add-post-options-items-holder-container', false);
                    });
                    mw.custom_fields.after_save();
                }
                if (!!window.thismodal) {
                    thismodal.resize(800)
                }
            </script>
            <style>
                .custom-fields-add-buttons [class*='mw-custom-field-icon-'] {
                    font-size: 25px;
                    display: block;
                    margin-bottom: 10px;
                }

                .custom-fields-add-buttons button .mw-custom-field-title text-break-line-1 {
                    font-size: 12px;
                }

                .custom-fields-add-buttons button {
                    width: 20%;
                    text-align: center;
                    display: block;
                    float: left;
                }

                .custom-fields-add-buttons button:hover {
                    background: #f2f3f5;
                }

                .card-closed {
                    width: auto !important;
                    display: inline-block;
                }

                .card .card-header {
                    /*cursor: pointer;*/
                }

                .card-closed .card-header:first-child {
                    border-radius: 10px;
                }

                .card-closed .card-header:after {
                    display: none;
                }
            </style>

            <div class="module-live-edit-settings">
                <div id="custom-field-editor" class="mw-ui-box mw-ui-box-content" style="display: none">
                    <label class="mw-ui-label">
                        <small><?php _e("Edit"); ?> <b id="which_field"></b> <?php _e("Field"); ?></small>
                    </label>
                    <div class="custom-field-edit">
                        <div class="custom-field-edit-header">
                            <span class="custom-field-edit-title"></span> <span onmousedown="mw_cf_close_edit_window()" class="custom-field-edit-title-head right" style="cursor:pointer;"><?php _e('close'); ?> <span class="mw-ui-arr mw-ui-arr-down" style="opacity:0.6;"></span> </span>
                        </div>
                        <div class="mw-admin-custom-field-edit-item-wrapper">
                            <div class="mw-admin-custom-field-edit-item mw-admin-custom-field-edit-<?php print $params['id']; ?> "></div>
                        </div>
                    </div>
                </div>

                <div>
                    <?php
                    $ex = array();
                    $ex['rel_type'] = $for;
                    //$ex['rel_id'] = $for_id;
                    $ex['group_by'] = 'type';
                    $ex['order_by'] = 'created_at desc';
                    $name_not_in = array();
                    if (is_array($fields)) {
                        foreach ($fields as $field => $value) {
                            $name_not_in[] = $field;
                        }
                    }
                    if (!empty($name_not_in)) {
                        //$ex['name'] = '[not_in]'.implode(',',$name_not_in);
                    }
                    //$exiisting_fields = mw()->fields_manager->get_all($ex);
                    ?>

                    <?php $exiisting_fields = false; //TODO ?>

                    <div>
                        <div class="card mb-3 mt-3 card-closed">
                            <div class="card-header no-border bg-primary text-white py-1 pl-3" onClick="javascript:$('#add-field-select').toggleClass('collapse');$(this).parent().toggleClass('card-closed');$(this).find('.d-flex').toggleClass('justify-content-between');">
                                <div class="d-flex align-items-center w-100">
                                    <i class="mdi mdi-plus mdi-20px mr-2"></i>
                                    <span><?php _e("Add new field"); ?></span>
                                </div>
                            </div>
                            <div class="card-body collapse" id="add-field-select">
                                <div class="custom-fields-add-buttons">
                                    <?php if (is_array($exiisting_fields)): ?>
                                        <?php foreach ($exiisting_fields as $item): ?>
                                            <button type="button" class="btn btn-link text-dark px-1"  onclick="javascript:addCustomFieldByVal('<?php print $item['type']; ?>');">
                                                <div>
                                                    <span class="mw-custom-field-icon-text mw-custom-field-icon-<?php print $item['type']; ?>"></span>
                                                    <span class="mw-custom-field-title text-break-line-1 small" title="<?php print htmlspecialchars($item['name']); ?>"><?php print $item['name']; ?></span>
                                                </div>
                                            </button>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <?php foreach ($fields as $field => $value): ?>
                                        <button type="button" class="btn btn-link text-dark px-1"  onclick="javascript:addCustomFieldByVal('<?php print $field; ?>');">
                                            <div>
                                                <span class="mw-custom-field-icon-<?php print $field; ?>"></span>
                                                <span class="mw-custom-field-title text-break-line-1 text-center small"><?php _e($value); ?></span>
                                            </div>
                                        </button>
                                    <?php endforeach; ?>

                                    <select class="js-cf-options" data-live-search="true" data-size="7" style="display: none;">
                                        <?php if (is_array($exiisting_fields)): ?>
                                            <?php foreach ($exiisting_fields as $item): ?>
                                                <option data-copyof="<?php print $item['id'] ?>" value="<?php print $item['type']; ?>">
                                                    <span class="mw-custom-field-icon-text mw-custom-field-icon-<?php print $item['type']; ?>"></span>
                                                    <span class="mw-custom-field-title" title="<?php print htmlspecialchars($item['name']); ?>"><?php print $item['name']; ?></span>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                        <?php foreach ($fields as $field => $value): ?>
                                            <option value="<?php print $field; ?>">
                                                <span class="mw-custom-field-icon-<?php print $field; ?>"></span>
                                                <span class="mw-custom-field-title"><?php _e($value); ?></span>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="thin">

                <div id="custom-fields-box">
                    <?php if (isset($params['live_edit'])): ?>
                        <module type="admin/modules/templates" simple="true"/>
                        <br/>
                    <?php endif; ?>

                    <module data-type="custom_fields/list" for="<?php print $for ?>" <?php if (isset($for_id)): ?> rel_id='<?php print $for_id; ?>'  <?php endif; ?> list-preview="true" id="mw_custom_fields_list_preview"/>
                </div>


            </div>
        </div>

        <module type="help/modal_with_button" for_module="custom_fields"/>
    </div>
</div>