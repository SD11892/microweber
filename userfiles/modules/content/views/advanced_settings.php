<?php
has_access();
$data = false;
if (isset($params['content-id'])) {
    $data = get_content_by_id($params["content-id"]);
}

$available_content_types = false;
$available_content_subtypes = false;
/* FILLING UP EMPTY CONTENT WITH DATA */
if ($data == false or empty($data)) {
    $is_new_content = true;
    include('_empty_content_data.php');
} else {
    $available_content_types = get_content('group_by=content_type');
    $available_content_subtypes = get_content('group_by=subtype');
}

/* END OF FILLING UP EMPTY CONTENT  */
$show_page_settings = false;
if (isset($params['content-type']) and $params['content-type'] == 'page') {
    $show_page_settings = 1;
}

$template_config = mw()->template->get_config();
$data_fields_conf = false;
$data_fields_values = false;

if (!empty($template_config)) {
    if (isset($params['content-type'])) {
        if (isset($template_config['data-fields-' . $params['content-type']]) and is_array($template_config['data-fields-' . $params['content-type']])) {
            $data_fields_conf = $template_config['data-fields-' . $params['content-type']];
            if (isset($params['content-id'])) {
                $data_fields_values = content_data($params['content-id']);
            }
        }
    }
}

$post_author_id = user_id();
$all_users = true;

if (isset($data['created_by']) and $data['created_by']) {
    $post_author_id = $data['created_by'];
}
?>
    <script type="text/javascript">
        mw.lib.require('mwui_init');
    </script>

    <script type="text/javascript">
        mw.reset_current_page = function (a, callback) {
            mw.tools.confirm("<?php _ejs("Are you sure you want to Reset the content of this page?  All your text will be lost forever!!"); ?>", function () {
                var obj = {id: a}
                $.post(mw.settings.site_url + "api/content/reset_edit", obj, function (data) {
                    mw.notification.success("<?php _ejs('Content was resetted!'); ?>");

                    if (typeof(mw.edit_content) == 'object') {
                        mw.edit_content.load_editor()
                    }

                    typeof callback === 'function' ? callback.call(data) : '';
                });
            });
        }
        mw.copy_current_page = function (a, callback) {
            mw.tools.confirm("<?php _ejs("Are you sure you want to copy this page?"); ?>", function () {
                var obj = {id: a}
                $.post(mw.settings.site_url + "api/content/copy", obj, function (data) {
                    mw.notification.success("<?php _ejs('Content was copied'); ?>");
                    if (data != null) {
                        var r = confirm("<?php _ejs('Go to the new page?'); ?>");
                        if (r == true) {
                            if (self != top) {
                                top.window.location = mw.settings.site_url + "api/content/redirect_to_content?id=" + data;

                            } else {
                                mw.url.windowHashParam('action', 'editpage:' + data);

                            }
                            //content/redirect_to_content_id
                        } else {

                        }
                    }
                    typeof callback === 'function' ? callback.call(data) : '';
                });
            });
        }
        mw.del_current_page = function (a, callback) {
            mw.tools.confirm("<?php _ejs("Are you sure you want to delete this"); ?>", function () {
                var arr = (a.constructor === [].constructor) ? a : [a];
                var obj = {ids: arr}
                $.post(mw.settings.site_url + "api/content/delete", obj, function (data) {
                    mw.notification.warning("<?php _ejs('Content was sent to Trash'); ?>");
                    typeof callback === 'function' ? callback.call(data) : '';
                });
            });
        }

        mw.adm_cont_type_change_holder_event = function (el) {
            mw.tools.confirm("<?php _ejs("Are you sure you want to change the content type"); ?>? <?php _e("Please consider the documentation for more info"); ?>", function () {
                var root = mwd.querySelector('#<?php print $params['id']; ?>');
                var form = mw.tools.firstParentWithClass(root, 'mw_admin_edit_content_form');
                var ctype = $(el).val()
                if (form != undefined && form.querySelector('input[name="content_type"]') != null) {
                    form.querySelector('input[name="content_type"]').value = ctype;
                }
            });
        }
        mw.adm_cont_subtype_change_holder_event = function (el) {
            mw.tools.confirm("<?php _ejs("Are you sure you want to change the content subtype"); ?>? <?php _e("Please consider the documentation for more info"); ?>", function () {
                var root = mwd.querySelector('#<?php print $params['id']; ?>');
                var form = mw.tools.firstParentWithClass(root, 'mw_admin_edit_content_form');
                var ctype = $(el).val();
                if (form != undefined && form.querySelector('input[name="subtype"]') != null) {
                    form.querySelector('input[name="subtype"]').value = ctype
                }
            });
        }
        mw.adm_cont_enable_edit_of_created_at = function () {
            $('.mw-admin-edit-post-change-created-at-value').removeAttr('disabled').show();
            $('.mw-admin-edit-post-display-created-at-value').remove();
        }

        mw.adm_cont_enable_edit_of_updated_at = function () {
            $('.mw-admin-edit-post-change-updated-at-value').removeAttr('disabled').show();
            $('.mw-admin-edit-post-display-updated-at-value').remove();
        }
    </script>

<?php event_trigger('mw.admin.content.edit.advanced_settings', $data); ?>

<?php if (isset($params['content-type']) and isset($params['content-id'])): ?>
    <module type="content/views/settings_from_template" content-type="<?php print $params['content-type'] ?>" content-id="<?php print $params['content-id'] ?>"/>
<?php endif; ?>

    <!-- SEO Settings -->
    <div class="card style-1 mb-3">
        <div class="card-header no-border">
            <h6><strong>Search engine</strong></h6>
            <a href="javascript:;" class="btn btn-link" data-toggle="collapse" data-target="#seo-settings">Show SEO setttings</a>
        </div>

        <div class="card-body py-0">
            <div class="collapse" id="seo-settings">
                <p>Add a title and description to see how this product might appear in a search engine listing</p>

                <hr class="thin no-padding"/>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group js-count-letters">
                            <div class="d-flex justify-content-between">
                                <label><?php _e("Meta title"); ?>
                                    <small data-toggle="tooltip" title="Title for this <?php print $data['content_type'] ?> that will appear on the search engines on social networks.">(?)</small>
                                </label>
                                <span class="text-muted"><span class="js-typed-letters">0</span> of 70 characters used</span>
                            </div>
                            <input type="text" class="form-control" name="content_meta_title" placeholder="<?php _e("Title to appear on the search engines results page"); ?>" value="<?php if (isset($data['content_meta_title']) and $data['content_meta_title'] != '') print ($data['content_meta_title']) ?>"/>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group js-count-letters">
                            <div class="d-flex justify-content-between">
                                <label><?php _e("Meta description"); ?>
                                    <small data-toggle="tooltip" title="Short description for yor content.">(?)</small>
                                </label>
                                <span class="text-muted"><span class="js-typed-letters">0</span> of 70 characters used</span>
                            </div>
                            <textarea class="form-control" name="description" placeholder="<?php _e("Describe your page in short"); ?>"><?php if ($data['description'] != '') print ($data['description']) ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?php _e("Meta keywords"); ?>
                                <small data-toggle="tooltip" title="Keywords for this <?php print $data['content_type'] ?> that will help the search engines to find it. Ex: ipad, book, tutorial"> (?)</small>
                            </label>
                            <small class="text-muted d-block mb-2">Separate keywords with a comma and space</small>
                            <textarea class="form-control" name="content_meta_keywords" placeholder="<?php _e("Type keywords that describe your content - Example: Blog, Online News, Phones for Sale etc"); ?>"><?php if (isset($data['content_meta_keywords']) and $data['content_meta_keywords'] != '') print ($data['content_meta_keywords']) ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?php _e("OG Images"); ?></label>
                            <small class="text-muted d-block mb-2">
                                Those images will be shown as a post image at facebook shares.<br/>
                                If you want to attach a og images, you must upload them to gallery from "Add media".
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Settings -->
    <div class="card style-1 mb-3">
        <div class="card-header no-border">
            <h6><strong>Advanced settings</strong></h6>
            <a href="javascript:;" class="btn btn-link" data-toggle="collapse" data-target="#advenced-settings">Show advanced setttings</a>
        </div>

        <div class="card-body py-0">
            <div class="collapse" id="advenced-settings">
                <p>Use the advanced settings to customize your blog post</p>

                <hr class="thin no-padding"/>

                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $redirected = false;
                        if (isset($data['original_link']) and $data['original_link'] != '') {
                            $redirected = true;
                        } else {
                            $data['original_link'] = '';
                        }
                        ?>

                        <div class="form-group">
                            <label><?php _e("Redirect to URL"); ?></label>
                            <small class="text-muted d-block mb-2"><?php _e("If set this, the user will be redirected to the new URL when visits the page"); ?></small>
                            <input type="text" name="original_link" class="form-control" placeholder="<?php _e('http://yoursite.com'); ?>" value="<?php print $data['original_link'] ?>"/>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?php _e("Require login"); ?></label>
                            <small class="text-muted d-block mb-2"><?php _e("If set to yes - this page will require login from a registered user in order to be opened"); ?></small>
                            <div class="custom-control custom-switch pl-0">
                                <label class="d-inline-block mr-5" for="require_login"><?php _e("No"); ?></label>
                                <input type="checkbox" class="custom-control-input" id="require_login" name="require_login" data-value-checked="1" data-value-unchecked="0" <?php if ('1' == trim($data['require_login'])): ?>checked="1"<?php endif; ?>>
                                <label class="custom-control-label" for="require_login"><?php _e("Yes"); ?></label>
                            </div>
                        </div>
                    </div>

                    <?php if ($all_users) : ?>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php _e("Author"); ?></label>

                                <div id="select-post-author"></div>

                                <script>mw.require('autocomplete.js')</script>
                                <?php $user = get_user($post_author_id); ?>
                                <script>
                                    $(document).ready(function () {
                                        var created_by_field = new mw.autoComplete({
                                            element: "#select-post-author",
                                            ajaxConfig: {
                                                method: 'get',
                                                url: mw.settings.api_url + 'users/search_authors?kw=${val}',
                                                cache: true
                                            },
                                            map: {
                                                value: 'id',
                                                title: 'display_name',
                                                image: 'picture'
                                            },
                                            selected: [
                                                {
                                                    id: <?php print $post_author_id ?>,
                                                    display_name: '<?php print user_name($post_author_id) ?>'
                                                }
                                            ]
                                        });
                                        $(created_by_field).on("change", function (e, val) {
                                            $("#created_by").val(val[0].id).trigger('change')
                                        })
                                    });
                                </script>

                                <input type="hidden" name="created_by" id="created_by" value="<?php print $post_author_id ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <hr class="thin no-padding"/>

                <!-- More Advanced Settings -->
                <?php if (isset($data['id']) and $data['id'] > 0): ?>
                    <div class="row d-flex align-items-center">
                        <div class="col-md-6">
                            <label class="control-label my-2"><?php print _e('More options'); ?>:</label>
                        </div>

                        <div class="col-md-6 text-center text-md-right">
                            <a class="btn btn-info btn-sm" href="javascript:mw.copy_current_page('<?php print ($data['id']) ?>');"><?php _e("Duplicate"); ?></a>&nbsp;
                            <a class="btn btn-danger btn-sm" href="javascript:mw.reset_current_page('<?php print ($data['id']) ?>');"><?php _e("Reset Content"); ?></a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($show_page_settings != false): ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label><?php _e("Is Home"); ?></label>
                                <small class="text-muted d-block mb-2"><?php _e("If yes this page will be your Home"); ?></small>
                                <div class="custom-control custom-switch pl-0">
                                    <label class="d-inline-block mr-5" for="is_home"><?php _e("No"); ?></label>
                                    <input type="checkbox" name="is_home" class="custom-control-input" id="is_home" data-value-checked="1" data-value-unchecked="0" <?php if ($data['is_home']): ?>checked="1"<?php endif; ?> />
                                    <label class="custom-control-label" for="is_home"><?php _e("Yes"); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label><?php _e("Is Shop"); ?></label>
                                <small class="text-muted d-block mb-2"><?php _e("If yes this page will accept products to be added to it"); ?></small>
                                <div class="custom-control custom-switch pl-0">
                                    <label class="d-inline-block mr-5" for="is_shop"><?php _e("No"); ?></label>
                                    <input type="checkbox" name="is_shop" class="custom-control-input" id="is_shop" data-value-checked="1" data-value-unchecked="0" <?php if ($data['is_shop']): ?>checked="1"<?php endif; ?> />
                                    <label class="custom-control-label" for="is_shop"><?php _e("Yes"); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($data['position'])): ?>
                    <input name="position" type="hidden" value="<?php print ($data['position']) ?>"/>
                <?php endif; ?>

                <?php /* PAGES ONLY  */ ?>
                <?php event_trigger('mw_admin_edit_page_advanced_settings', $data); ?>

                <?php if (is_array($available_content_types) and !empty($available_content_types)): ?>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="mw-ui-field-holder"><br/>
                                <span class="font-weight-bold"><?php _e("Content type"); ?>: &nbsp;</span>

                                <button class="btn btn-outline-warning btn-sm" data-toggle="collapse" data-target="#content-type-settings"><?php print($data['content_type']) ?></button>

                                <div class="collapse" id="content-type-settings">
                                    <div class="alert alert-dismissible alert-warning mt-3">
                                        <h4 class="alert-heading"><?php _e("Warning!"); ?></h4>
                                        <p class="mb-0"><?php _e("Do not change these settings unless you know what you are doing."); ?></p>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>
                                                <?php _e("Change content type"); ?>
                                                <small data-toggle="tooltip" data-title="<?php _e("Changing the content type to different than"); ?> '<?php print $data['content_type'] ?>' <?php _e("is advanced action. Please read the documentation and consider not to change the content type"); ?>">(?)</small>
                                            </label>

                                            <select class="selectpicker" data-width="100%" name="change_content_type" onchange="mw.adm_cont_type_change_holder_event(this)">
                                                <?php foreach ($available_content_types as $item): ?>
                                                    <option value="<?php print $item['content_type']; ?>" <?php if ($item['content_type'] == trim($data['content_type'])): ?>   selected="selected"  <?php endif; ?>><?php print $item['content_type']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>
                                                <?php _e("Change content sub type"); ?>
                                                <small data-toggle="tooltip" data-title="<?php _e("Changing the content type to different than"); ?> '<?php print $data['subtype'] ?>' <?php _e("is advanced action. Please read the documentation and consider not to change the content type"); ?>">(?)</small>
                                            </label>

                                            <select class="selectpicker" data-width="100%" name="change_contentsub_type" onchange="mw.adm_cont_subtype_change_holder_event(this)">
                                                <?php foreach ($available_content_subtypes as $item): ?>
                                                    <option value="<?php print $item['subtype']; ?>" <?php if ($item['subtype'] == trim($data['subtype'])): ?>   selected="selected"  <?php endif; ?>><?php print $item['subtype']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php $custom = mw()->module_manager->ui('mw.admin.content.edit.advanced_settings.end'); ?>

<?php if (!empty($custom)): ?>
    <div>
        <?php foreach ($custom as $item): ?>
            <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
            <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
            <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
            <?php print $html; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>