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
        <script>
            function editTaggingTag(tagging_tag_id, taggable_ids) {
                var modal_title = 'Add new tag';
                if (tagging_tag_id) {
                    modal_title = 'Edit tag';
                }

                mw_admin_edit_tag_modal = mw.dialog({
                    content: '<div id="mw_admin_edit_tagging_tag_item_module">Loading...</div>',
                    title: modal_title,
                    id: 'mw_admin_edit_tagging_tag_item_popup_modal'
                });

                var params = {};
                params.tagging_tag_id = tagging_tag_id;
                params.taggable_ids = taggable_ids;

                mw.load_module('tags/edit_tagging_tag', '#mw_admin_edit_tagging_tag_item_module', null, params);
            }

            function deleteTaggingTag(tagging_tag_id) {
                mw.tools.confirm(mw.msg.del, function () {
                    $.ajax({
                        url: mw.settings.api_url + 'tagging_tag/delete',
                        type: 'post',
                        data: {
                            tagging_tag_id: tagging_tag_id
                        },
                        success: function (data) {
                            $('.btn-tag-id-' + tagging_tag_id).remove();
                            //mw.reload_module_everywhere('tags');

                            selected_taggable_items = getSelectedTaggableItems();
                            if (selected_taggable_items) {
                                for (i = 0; i < selected_taggable_items.length; i++) {
                                    getPostTags(selected_taggable_items[i].post_id);
                                }
                            }

                            mw.notification.error('<?php _e('Tag is deleted!');?>');
                        }
                    });
                });
            }

            function addTaggingTagged(taggable_id = false, taggable_ids = false) {
                var modal_title = 'Add new tag';

                mw_admin_edit_tag_modal = mw.dialog({
                    content: '<div id="mw_admin_add_tagging_tagged_item_module">Loading...</div>',
                    title: modal_title,
                    id: 'mw_admin_add_tagging_tagged_item_popup_modal'
                });

                var params = {}
                params.taggable_id = taggable_id;
                params.taggable_ids = taggable_ids;

                mw.load_module('tags/add_tagging_tagged', '#mw_admin_add_tagging_tagged_item_module', null, params);
            }

            function deleteTaggingTagged(tagging_tagged_id) {
                mw.tools.confirm(mw.msg.del, function () {
                    $.ajax({
                        url: mw.settings.api_url + 'tagging_tagged/delete',
                        type: 'post',
                        data: {
                            tagging_tagged_id: tagging_tagged_id,
                        },
                        success: function (data) {
                            $('.btn-tag-id-' + tagging_tagged_id).remove();
                            //mw.reload_module_everywhere('tags');
                            mw.notification.error('<?php _e('Post tag is deleted!');?>');
                        }
                    });
                });
            }

            function showPostsWithTags(instance) {
                mw.modal({
                    content: '<div id="mw_admin_preview_module_content_with_tags"></div>',
                    title: 'View content with tags',
                    width: 1000,
                    height: 600,
                    id: 'mw_admin_preview_module_modal'
                });

                var params = {}
                params.tags = $(instance).attr('data-slug');
                params.no_toolbar = 1;

                mw.load_module('content/manager', '#mw_admin_preview_module_content_with_tags', null, params);
            }
        </script>

        <?php sync_tags(); ?>

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> Posts</a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#global-tags"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php _e('Global Tags'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">
                <module type="tags/manage_tagging_tagged"/>
            </div>

            <div class="tab-pane fade" id="global-tags">

                <div class="row">
                    <div class="col-md-6">
                        <div style="font-weight: bold;">Search tags</div>
                        <div class="input-group">
                            <input type="text" class="form-control js-search-tags-keyword" placeholder="Keyword...">
                            <div class="input-group-append">
                                <button class="btn btn-success js-search-posts-submit" type="button">Search</button>
                            </div>
                        </div>
                        <span class="mb-3">You can search multiple tags seperated by coma.</span>
                    </div>

                    <div class="col-md-6">
                        <button class="btn btn-success pull-right" onclick="editTaggingTag(false);"><i class="fa fa-plus"></i> Create new global tag</button>
                    </div>

                    <div class="col-md-12">
                        <div class="card style-1">
                            <div class="card-header">Global tags</div>
                            <div class="card-body">
                                <div class="js-all-tags"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <module type="help/modal_with_button" for_module="tags"/>
    </div>
</div>

