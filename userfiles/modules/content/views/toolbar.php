<?php

$custom_tabs = false;

$type = 'page';
$act = url_param('action', 1);
?>

<?php

if (isset($params['page-id'])) {
    $last_page_front = $params['page-id'];
} else {

    $last_page_front = session_get('last_content_id');
    if ($last_page_front == false) {
        if (isset($_COOKIE['last_page'])) {
            $last_page_front = $_COOKIE['last_page'];
        }
    }
}


$past_page = false;
if ($last_page_front != false) {
    $cont_by_url = mw()->content_manager->get_by_id($last_page_front, true);
    if (isset($cont_by_url) and $cont_by_url == false) {
        $past_page = mw()->content_manager->get("order_by=updated_at desc&limit=1");
        $past_page = mw()->content_manager->link($past_page[0]['id']);
    } else {
        $past_page = mw()->content_manager->link($last_page_front);
    }
} else {
    $past_page = mw()->content_manager->get("order_by=updated_at desc&limit=1");
    if (isset($past_page[0])) {
        $past_page = mw()->content_manager->link($past_page[0]['id']);
    } else {
        $past_page = false;
    }


}


?>
<?php if (isset($past_page) and $past_page != false): ?>
    <script>
        $(function () {
            mw.tabs({
                nav: "#manage-content-toolbar-tabs-nav a",
                tabs: '#manage-content-toolbar-tabs .mw-ui-box-content'
            });
            $('.go-live-edit-href-set').attr('href', '<?php print $past_page; ?>');
        });
    </script>
<?php endif; ?>
<?php if (isset($params['keyword']) and $params['keyword'] != false): ?>
    <?php

    $params['keyword'] = urldecode($params['keyword']); ?>
    <script>
        $(function () {

            $('[autofocus]').focus(function () {
                this.selectionStart = this.selectionEnd = this.value.length;
            });

            $('[autofocus]:not(:focus)').eq(0).focus();


        });
    </script>
<?php endif; ?>
<?php if ($page_info): ?>

    <?php
    $content_types = array();
    $available_content_types = get_content('order_by=created_at asc&is_deleted=0&fields=content_type&group_by=content_type&parent=' . $page_info['id']);
    $have_custom_content_types_count = 0;
    if (!empty($available_content_types)) {

        foreach ($available_content_types as $available_content_type) {
            if (isset($available_content_type['content_type'])) {
                $available_content_subtypes = get_content('order_by=created_at asc&is_deleted=0&fields=subtype&group_by=subtype&parent=' . $page_info['id'] . '&content_type=' . $available_content_type['content_type']);
                if (!empty($available_content_subtypes)) {

                    $content_types[$available_content_type['content_type']] = $available_content_subtypes;

                }

            }
        }
    }
    $have_custom_content_types_count = count($content_types);

    if ($have_custom_content_types_count < 3) {
        $content_types = false;
    }

    ?>
    <?php if (isset($content_types) and !empty($content_types)): ?>
        <?php $content_type_filter = (isset($params['content_type_filter'])) ? ($params['content_type_filter']) : false; ?>
        <?php $subtype_filter = (isset($params['subtype_filter'])) ? ($params['subtype_filter']) : false; ?>
        <?php


        $selected = $content_type_filter;
        if ($subtype_filter != false) {
            $selected = $selected . '.' . $subtype_filter;

        }


        ?>
        <script>
            $(function () {
                $("#content_type_filter_by_select").change(function () {
                    var val = $(this).val();
                    if (val != null) {
                        vals = val.split('.');
                        if (vals[0] != null) {
                            mw.$('#<?php print $params['id']; ?>').attr('content_type_filter', vals[0]);

                        } else {
                            mw.$('#<?php print $params['id']; ?>').removeAttr('content_type_filter');

                        }
                        if (vals[1] != null) {
                            mw.$('#<?php print $params['id']; ?>').attr('subtype_filter', vals[1]);

                        } else {
                            mw.$('#<?php print $params['id']; ?>').removeAttr('subtype_filter');

                        }


                        mw.reload_module('#<?php print $params['id']; ?>');

                    }

                });

            });
        </script>
    <?php endif; ?>
<?php endif; ?>


<div class="admin-manage-toolbar-holder">
    <div class="admin-manage-toolbar">
        <div class="admin-manage-toolbar-content">
            <?php if (!isset($edit_page_info)): ?>
                <?php mw()->event_manager->trigger('module.content.manager.toolbar.start', $page_info) ?>

                <?php


                if ($page_info['is_shop'] == 1) {
                    $type = 'shop';
                } elseif ($page_info['subtype'] == 'dynamic') {
                    $type = 'dynamicpage';
                } else if (isset($page_info ['layout_file']) and stristr($page_info ['layout_file'], 'blog')) {
                    $type .= 'blog';
                } else {
                    $type = 'page';
                }

                ?>

                <div class="section-header">
                    <div class="mw-ui-row" style="margin-bottom: 20px;">
                        <div class="mw-ui-col" style="width: 50%;">

                            <h2 class="pull-left">
                                <?php if (!isset($params['category-id']) and isset($page_info) and is_array($page_info)): ?>

                                <span class="<?php if ($type == 'shop'): ?>mai-market2<?php else: ?>mw-icon-<?php print $type; ?><?php endif; ?>"></span><?php print ($page_info['title']) ?>
                                <?php elseif (isset($params['category-id'])): ?>
                                    <?php $cat = get_category_by_id($params['category-id']); ?>
                                    <?php if (isset($cat['title'])): ?>
                                        <span class="mw-icon-category"></span> <?php print $cat['title'] ?>
                                    <?php endif; ?>
                                <?php elseif ($act == 'pages'): ?>
                                    <span class="mai-page"></span>
                                    <?php _e("Pages"); ?>
                                <?php elseif ($act == 'posts'): ?>
                                    <span class="mai-post"></span>
                                    <?php _e("Posts"); ?>
                                <?php elseif ($act == 'products'): ?>
                                    <span class="mai-product"></span>
                                    <?php _e("Products"); ?>
                                <?php elseif (isset($params['is_shop'])): ?>
                                    <span class="mai-market2"></span>
                                    <?php _e("My Shop"); ?>
                                <?php else: ?>
                                    <span class="mai-website"></span>
                                    <?php _e("Website"); ?>
                                <?php endif; ?>
                            </h2>

                            <?php
                            $cat_page = false;
                            if (isset($params['category-id']) and $params['category-id']) {
                                $cat_page = get_page_for_category($params['category-id']);

                                //  $add_new_btn_url = $add_new_btn_url . "&amp;category_id=" . $params['category-id'];
                            }

                            $url_param_action = url_param('action', true);
                            $url_param_view = url_param('view', true);

                            $url_param_type = 'page';


                            if ($type == 'shop' or $url_param_view == 'shop' or $url_param_action == 'products') {
                                $url_param_type = 'product';
                            } else if ($cat_page and isset($cat_page['is_shop']) and intval($cat_page['is_shop']) != 0) {
                                $url_param_type = 'product';
                            } else if ($url_param_action == 'categories' or $url_param_view == 'category') {
                                $url_param_type = 'category';
                            } else if ($url_param_action == 'showposts' or $url_param_action == 'posts' or $type == 'dynamicpage') {
                                $url_param_type = 'post';
                            } else if ($cat_page and isset($cat_page['subtype']) and ($cat_page['subtype'])  == 'dynamic') {
                                $url_param_type = 'product';
                            }





                             $add_new_btn_url = admin_url('view:content#action=new:') . $url_param_type;


                            if (isset($page_info['id']) and $page_info['id']) {
                               //   $add_new_btn_url = $add_new_btn_url . "&amp;parent_page=" . $page_info['id'];

                            }


                            //                            elseif (isset($post_params['category'])) {
                            //                                $url = "#action=new:product&amp;category_id=" . $post_params['category'];
                            //                            } else if (isset($post_params['parent'])) {
                            //                                $url = "#action=new:product&amp;parent_page=" . $post_params['parent'];
                            //                            } else {
                            //                                $url = "#action=new:product";
                            //                            }


                            // d($type);
                            //d($url_param_type);
                            //d($page_info);

                            ?>

                            <a href="<?php print $add_new_btn_url ?>"
                               class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-medium pull-left m-l-10"
                               style="margin-top: 2px;">
                                <?php print _e('Add new ' . $url_param_type); ?>
                            </a>


                            <div class="pull-right">
                                <div class="mw-ui-btn-nav pull-right">
                                    <?php if (isset($params['add-to-page-id']) and intval($params['add-to-page-id']) != 0): ?>
                                        <div class="mw-ui-dropdown">
                                            <span class="mw-ui-btn mw-icon-plus"><span class=""></span></span>
                                            <div class="mw-ui-dropdown-content">
                                                <div class="mw-ui-btn-vertical-nav">
                                                    <?php event_trigger('content.create.menu'); ?>

                                                    <?php $create_content_menu = mw()->modules->ui('content.create.menu'); ?>
                                                    <?php if (!empty($create_content_menu)): ?>
                                                        <?php foreach ($create_content_menu as $type => $item): ?>
                                                            <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                                            <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                                            <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                                                            <?php $type = (isset($item['content_type'])) ? ($item['content_type']) : false; ?>
                                                            <?php $subtype = (isset($item['subtype'])) ? ($item['subtype']) : false; ?>
                                                            <span class="mw-ui-btn <?php print $class; ?>"><a
                                                                        href="<?php print admin_url('view:content'); ?>#action=new:<?php print $type; ?><?php if ($subtype != false): ?>.<?php print $subtype; ?><?php endif; ?>&amp;parent_page=<?php print $params['page-id'] ?>">  <?php print $title; ?> </a></span>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($params['page-id']) and intval($params['page-id']) != 0): ?>
                                        <?php $edit_link = admin_url('view:content#action=editpost:' . $params['page-id']); ?>
                                    <?php endif; ?>

                                    <?php if (isset($params['category-id'])): ?>
                                        <?php $edit_link = admin_url('view:content#action=editcategory:' . $params['category-id']); ?>
                                    <?php endif; ?>

                                    <?php if (isset($params['page-id']) and intval($params['page-id']) != 0): ?>
                                        <?php $edit_link = admin_url('view:content#action=editpost:' . $params['page-id']); ?>
                                        <a href="<?php print $edit_link; ?>"
                                           class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline m-l-10"
                                           id="edit-content-btn" data-tip="bottom-left">
                                            <span class="mai-edit"></span>&nbsp; <span><?php _e("Edit page"); ?></span>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (isset($params['category-id'])): ?>
                                        <?php $edit_link = admin_url('view:content#action=editcategory:' . $params['category-id']); ?>
                                        <a href="<?php print $edit_link; ?>"
                                           class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline" id="edit-category-btn"
                                           data-tip="bottom-left">
                                            <span><?php _e("Edit category"); ?></span>
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <?php if (isset($content_types) and !empty($content_types)): ?>
                                    <div class="pull-right" style="margin-right:5px;">

                                        <select id="content_type_filter_by_select"
                                                class="mw-ui-field" <?php if (!$selected): ?> style="display:none" <?php endif; ?>>
                                            <option value=""><?php _e('All'); ?></option>
                                            <?php foreach ($content_types as $k => $items): ?>
                                                <optgroup label="<?php print ucfirst($k); ?>">
                                                    <option value="<?php print $k; ?>" <?php if ($k == $selected): ?> selected="selected" <?php endif; ?>><?php print ucfirst($k); ?></option>
                                                    <?php foreach ($items as $item): ?>
                                                        <?php if (isset($item['subtype']) and $item['subtype'] != $k): ?>
                                                            <option value="<?php print $k; ?>.<?php print $item['subtype']; ?>" <?php if ($k . '.' . $item['subtype'] == $selected): ?> selected="selected" <?php endif; ?>><?php print ucfirst($item['subtype']); ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (!$selected): ?>
                                            <span class="mw-ui-btn mw-icon-menu"
                                                  onclick="$('#content_type_filter_by_select').toggle(); $(this).hide();"></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="pull-right relative m-r-10">
                                    <div class="top-search">
                                        <input value="<?php if (isset($params['keyword']) and $params['keyword'] != false): ?><?php print $params['keyword'] ?><?php endif; ?>"
                                            <?php if (isset($params['keyword']) and $params['keyword'] != false): ?> autofocus="autofocus"
                                            <?php endif; ?>
                                               placeholder="<?php _e("Search"); ?>" type="text"
                                               onkeyup="event.keyCode==13?mw.url.windowHashParam('search',this.value):false"/>
                                        <span class="top-form-submit"
                                              onclick="mw.url.windowHashParam('search',$(this).prev().val())"><span
                                                    class="mw-icon-search"></span></span>
                                    </div>
                                    <script>
                                        $(document).ready(function () {
                                            $(".top-search input").on('focus', function () {
                                                $(this).parent().addClass('focused');
                                            });
                                            $(".top-search input").on('blur', function () {
                                                $(this).parent().removeClass('focused');
                                            });
                                        })
                                    </script>
                                </div>

                                <?php mw()->event_manager->trigger('module.content.manager.toolbar.end', $page_info); ?>
                            </div>


                        </div>
                    </div>
                </div>
            <?php endif; ?>


            <?php if ($page_info): ?>
                <?php mw()->event_manager->trigger('module.content.manager.toolbar', $page_info) ?>
            <?php endif; ?>
            <?php $custom_tabs = mw()->modules->ui('content.manager.toolbar'); ?>
            <?php if (!empty($custom_tabs)): ?>
                <div id="manage-content-toolbar-tabs">
                    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs" id="manage-content-toolbar-tabs-nav">
                        <?php foreach ($custom_tabs as $item): ?>
                            <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                            <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                            <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                            <a class="mw-ui-btn tip" data-tip="<?php print $title; ?>"> <span
                                        class="<?php print $class; ?>"></span> <span> <?php print $title; ?> </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="mw-ui-box">
                        <?php foreach ($custom_tabs as $item): ?>
                            <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                            <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                            <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                            <div class="mw-ui-box-content" style="display: none;"><?php print $html; ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (!isset($edit_page_info)): ?>
                <div class="manage-toobar manage-toolbar-top">
                    <div class="manage-toobar-content">
                        <div class="">


                            <label class="mw-ui-check" id="posts-check">
                                <input type="checkbox">
                                <span></span>
                                <span>Check all</span>
                            </label>


                            <div class="mw-dropdown mw-dropdown-default" id="bulk-actions">
                                <span class="mw-dropdown-value mw-ui-btn mw-ui-btn-medium mw-dropdown-val"><?php _e("Bulk actions"); ?></span>
                                <div class="mw-dropdown-content">
                                    <ul>
                                        <li>
                                            <a onclick="assign_selected_posts_to_category();"><?php _e("Move to category"); ?></a>
                                        </li>
                                        <li><a onclick="delete_selected_posts();"><?php _e("Delete"); ?></a></li>
                                    </ul>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        mw.dropdown();
        var el = $("#posts-check input")
        el.on('change', function () {
            mw.check.toggle('#mw_admin_posts_sortable');


            var all = $('#mw_admin_posts_sortable input[type="checkbox"]');
            var checked = all.filter(':checked');
            if (checked.length && checked.length === all.length) {
                el[0].checked = true;
            }
            else {
                el[0].checked = false;
            }


        });
    });
</script>


