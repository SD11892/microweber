<!DOCTYPE html>
<html <?php print lang_attributes(); ?>>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex">
    <script type="text/javascript">
        if (!window.CanvasRenderingContext2D) {
            var h = "<div id='UnsupportedBrowserMSG'><h1><?php _e("Your a need better browser to run Microweber>"); ?></h1></div>"
                + "<div id='download_browsers_holder'><h2><?php _e("Update your browser"); ?></h2><p id='choose_browsers'>"
                + "<a id='u__ie' target='_blank' href='http://windows.microsoft.com/en-us/internet-explorer/download-ie'></a>"
                + "<a id='u__ff' target='_blank' href='http://www.mozilla.org/en-US/firefox/new/'></a>"
                + "<a id='u__chr' target='_blank' href='https://www.google.com/intl/en/chrome/'></a>"
                + "<a id='u__sf' target='_blank' href='http://support.apple.com/kb/DL1531'></a>"
                + "</p></div>";
            document.write(h);
            document.body.id = 'UnsupportedBrowser';
            document.body.className = 'UnsupportedBrowser';
        }
        mwAdmin = true;
        admin_url = '<?php print admin_url(); ?>';
    </script>
    <script type="text/javascript">
        mw.lib.require('jqueryui');
    </script>
    <script type="text/javascript">

        mw.require("<?php print mw_includes_url(); ?>api/libs/jquery_slimscroll/jquery.slimscroll.min.js");
        mw.require("liveadmin.js");
        mw.require("<?php print mw_includes_url(); ?>css/wysiwyg.css");
        mw.require("<?php print mw_includes_url(); ?>css/components.css");

        mw.require("<?php print mw_includes_url(); ?>css/admin-new.css");
        mw.require("wysiwyg.js");
        mw.require("url.js");
        mw.require("options.js");
        mw.require("events.js");
        mw.require("admin.js");
        mw.require("editor_externals.js");
        mw.require("keys.js");
        mw.require("css_parser.js");
        mw.require("custom_fields.js");
        mw.require("session.js");
        mw.require("content.js");
        mw.require("upgrades.js");
        mw.require("tree.js");

        mw.lib.require('mwui');
        mw.lib.require('flag_icons', true);
        mw.lib.require('font_awesome5');
        mw.require("<?php print mw_includes_url(); ?>css/admin.css", true);

        <?php /*  mw.require("<?php print mw_includes_url(); ?>css/helpinfo.css");
        mw.require("helpinfo.js");*/ ?>
        <?php if(_lang_is_rtl()){ ?>
        mw.require("<?php print mw_includes_url(); ?>css/rtl.css");
        <?php } ?>
    </script>
    <?php if (!isset($_REQUEST['no_toolbar'])): ?>
        <script type="text/javascript">
            $(document).ready(function () {

                $('.mw-lazy-load-module').reload_module();

                if (self === top) {
                    window.onhashchange = function () {
                        mw.cookie.set('back_to_admin', location.href);
                    }
                    mw.cookie.set('back_to_admin', location.href);
                }
                mw.$("#mw-quick-content,#mw_edit_pages_content,#mw-admin-content").click(function () {
                    if (mw.helpinfo != undefined) {
                        mw.cookie.set('helpinfo', false, 4380);
                        $(".helpinfo_helper").fadeOut();
                    }
                });
            });
            // mw.require("<?php print mw_includes_url(); ?>css/ui.css");
            mw.require("fonts.js");


            $(window).load(function () {
                if ($(".bootstrap3ns").size() > 0) {
                    mw.lib.require("bootstrap3ns");
                }
            });


        </script>
    <?php endif; ?>
    <?php event_trigger('admin_head'); ?>
</head>
<body class="is_admin loading view-<?php print mw()->url_manager->param('view'); ?> action-<?php print mw()->url_manager->param('action'); ?>">


<?php

$new_version_notifications = mw()->notifications_manager->get('rel_type=update_check&rel_id=updates');

?>


<?php
$past_page = site_url() . '?editmode=y';
$last_page_front = session_get('last_content_id');
if ($last_page_front == false) {
    if (isset($_COOKIE['last_page'])) {
        $last_page_front = $_COOKIE['last_page'];
    }
}

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
    }
}


?>
<?php
$last_page_front = session_get('last_content_id');
if ($last_page_front == false) {
    if (isset($_COOKIE['last_page'])) {
        $last_page_front = $_COOKIE['last_page'];
    }
}
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
        $past_page = site_url();
    }
}


$shop_disabled = get_option('shop_disabled', 'website') == 'y';

if (!$shop_disabled) {
    if (!mw()->module_manager->is_installed('shop')) {
        $shop_disabled = true;
    }
}


?>
<?php /*<div id="admin-user-nav">


<a href="javascript:;" class="mw-icon-off pull-right"></a>
<a href="<?php print $past_page; ?>?editmode=y" class="mw-ui-btn mw-ui-btn-invert pull-right"><span class="mw-icon-live"></span><?php _e("Live Edit"); ?></a>

</div>*/ ?>


<script>
    $(document).ready(function () {
        $(".mw-admin-mobile-admin-sidebar-toggle").on('click', function () {
            $("#main-bar").toggleClass('mobile-active')
        })
        $("body").on('click', function (e) {
            if (!mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['mw-admin-mobile-admin-sidebar-toggle'])) {
                $("#main-bar").removeClass('mobile-active')
            }

        })


    })


    function mw_admin_add_order_popup(ord_id) {

        if (!!ord_id) {
            var modalTitle = '<?php _e('Edit order'); ?>';
        } else {
            var modalTitle = '<?php _e('Add order'); ?>';
        }


        mw_admin_edit_order_item_popup_modal_opened = mw.dialog({
            content: '<div id="mw_admin_edit_order_item_module"></div>',
            title: modalTitle,
            id: 'mw_admin_edit_order_item_popup_modal',
            width: 900
        });

        var params = {}
        params.order_id = ord_id;
        mw.load_module('shop/orders/admin/add_order', '#mw_admin_edit_order_item_module', null, params);
    }


</script>

<?php if (is_admin()): ?>

    <?php

    $order_notif_html = false;
    $new_orders_count = mw()->order_manager->get_count_of_new_orders();
    if ($new_orders_count) {
        $order_notif_html = '<sup class="badge badge-success badge-pill mr-2">' . $new_orders_count . '</sup>';
    }

    ?>
    <?php
    $comments_notif_html = false;
    $new_comments_count = mw()->notifications_manager->get('module=comments&is_read=0&count=1');
    if ($new_comments_count) {
        $comments_notif_html = '<sup class="badge badge-success badge-pill mr-2">' . $new_comments_count . '</sup>';

    }
    ?>


    <?php
    $notif_html = '';

    $notif_count = mw()->notifications_manager->get_unread_count();

    if ($notif_count > 0) {
        $notif_html = '<sup class="badge badge-success badge-pill mr-2">' . $notif_count . '</sup>';
    }


    ?>

    <?php $user_id = user_id();
    $user = get_user_by_id($user_id);
    if (!empty($user)) {
        $img = user_picture($user_id);
        if ($img != '') {
            ?>
            <a href="javascript:;" id="main-bar-user-menu-link-top" class="main-bar-user-menu-link-has-image">
                <span class="main-bar-profile-img" style="background-image: url('<?php print $img; ?>');"></span>
            </a>
        <?php } else { ?>
            <a href="javascript:;" id="main-bar-user-menu-link-top" class="main-bar-user-menu-link-no-image">
                <span class="mw-icon-user" id="main-bar-profile-icon"></span>
            </a>
        <?php }
    } ?>

<?php endif; ?>

<div id="mw-admin-container">
    <?php if (is_admin()): ?>
    <header class="position-sticky sticky-top bg-white">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center py-1">
                <ul class="nav">
                    <li class="mx-1 mobile-toggle">
                        <button type="button" class="js-toggle-mobile-nav"><i class="mdi mdi-menu"></i></button>
                    </li>
                    <li class="mx-1 logo d-none d-md-block">
                        <a class="mw-admin-logo" href="<?php print admin_url('view:dashboard'); ?>">
                            <?php if (mw()->ui->admin_logo != false) : ?>
                                <img src="<?php print mw()->ui->admin_logo ?>"/>
                            <?php else: ?>
                                <img src="<?php print mw()->ui->admin_logo_login(); ?>"/>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="mx-1 logo d-none d-md-block">
                        <button
                                type="button"
                                class="btn btn-outline-secondary btn-rounded btn-sm-only-icon"
                                data-toggle="dropdown"
                                aria-expanded="false">
                            <span class="d-none d-md-block"><?php _e("Add New"); ?></span> <i class="mdi mdi-plus"></i>
                        </button>
                        <div class="dropdown-menu ">
                            <?php $custom_view = url_param('view'); ?>
                            <?php $custom_action = url_param('action'); ?>
                            <?php event_trigger('content.create.menu'); ?>
                            <?php $create_content_menu = mw()->module_manager->ui('content.create.menu'); ?>
                            <?php if (!empty($create_content_menu)): ?>
                                <?php foreach ($create_content_menu as $type => $item): ?>
                                    <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                    <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                    <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                                    <?php $type = (isset($item['content_type'])) ? ($item['content_type']) : false; ?>
                                    <?php $subtype = (isset($item['subtype'])) ? ($item['subtype']) : false; ?>
                                    <?php $base_url = (isset($item['base_url'])) ? ($item['base_url']) : false; ?>
                                    <?php
                                    if ($base_url == false) {
                                        $base_url = admin_url('view:content');
                                        if ($custom_action != false) {
                                            if ($custom_action == 'pages' or $custom_action == 'posts' or $custom_action == 'products') {
                                                $base_url = $base_url . '/action:' . $custom_action;
                                            }
                                        }
                                    }
                                    ?>
                                    <a class="dropdown-item" href="<?php print $base_url; ?>#action=new:<?php print $type; ?><?php if ($subtype != false): ?>.<?php print $subtype; ?><?php endif; ?>"><span class="<?php print $class; ?>"></span><strong><?php print $title; ?></strong></a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </li>
                </ul>


                <ul class="nav">
                    <?php if ($new_orders_count != ''): ?>
                        <li class="mx-1">
                            <a href="<?php print admin_url(); ?>view:shop/action:orders"
                               class="btn btn-link btn-rounded icon-left text-dark px-0">
                                <?php print $order_notif_html; ?>
                                <i class="mdi mdi-shopping text-muted"></i> &nbsp;
                                <span class="d-none d-md-block">
                                            <?php if ($new_orders_count == 1): ?>
                                                <?php _e("New order"); ?>
                                            <?php elseif ($new_orders_count > 1): ?>
                                                <?php _e("New orders"); ?>
                                            <?php endif; ?>
                                        </span>
                            </a>

                        </li>
                    <?php endif; ?>

                    <?php if ($comments_notif_html != ''): ?>
                        <li class="mx-1">
                            <a href="<?php print admin_url(); ?>view:modules/load_module:comments"
                               class="btn btn-link btn-rounded icon-left text-dark px-0">
                                <?php print $comments_notif_html; ?>&nbsp;
                                <i class="mdi mdi-comment-account text-muted"></i>
                                <span class="d-none d-md-block">
                                            <?php if ($new_comments_count == 1): ?>
                                                <?php _e("New comment"); ?>
                                            <?php elseif ($new_comments_count > 1): ?>
                                                <?php _e("New comments"); ?>
                                            <?php endif; ?>
                                        </span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($notif_count != ''): ?>
                        <li class="mx-1">
                            <a href="<?php echo route('admin.notification.index'); ?>"
                               class="btn btn-link btn-rounded icon-left text-dark px-0">
                                <?php print $notif_html; ?>
                                <i class="mdi mdi-newspaper-variant-multiple text-muted"></i>&nbsp;
                                <span class="notif-label">
                                            <?php if ($notif_count == 1): ?>
                                                <?php _e("New notification"); ?>
                                            <?php elseif ($notif_count > 1): ?>
                                                <?php _e("New notifications"); ?>
                                            <?php endif; ?>
                                        </span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <?php event_trigger('mw.admin.header.toolbar'); ?>
                <ul class="nav">
                    <li class="mx-1">
                        <a href="<?php print $past_page ?>?editmode=y"
                           class="btn btn-primary btn-rounded btn-sm-only-icon go-live-edit-href-set" target="_blank">
                            <i class="mdi mdi-eye-outline"></i><span class="d-none d-md-block ml-1"><?php _e("Live Edit"); ?></span>
                        </a>
                    </li>
                    <li class="mx-1 language-selector">
                        <?php $current_lang = current_lang(); ?>
                        <button
                                type="button"
                                class="btn btn-outline-secondary btn-rounded btn-icon"
                                data-toggle="dropdown">
                            <i class="flag-icon flag-icon-<?php print $current_lang; ?>"></i>
                        </button>
                        <div class="dropdown-menu ">
                            <?php
                            $langs = get_available_languages();
                            $selected_lang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'en';
                            foreach ($langs as $lang):
                                ?>
                                <span
                                        onclick='mw.admin.language("<?php print $lang; ?>");'
                                        class="dropdown-item<?php if ($selected_lang == $lang) {
                                            print ' active';
                                        } ?>">
                                        <i class="flag-icon flag-icon-<?php print $lang ?>"></i><?php print $lang ?>
                                    </span>
                            <?php endforeach; ?>
                        </div>

                    </li>
                </ul>
            </div>
        </div>
    </header>


    <div class="main container my-3">
        <aside>
            <?php $view = url_param('view'); ?>
            <?php $action = url_param('action'); ?>
            <?php $load_module = url_param('load_module'); ?>
            <ul class="nav flex-column" id="mw-admin-main-navigation">
                <li class="nav-item">
                    <a href="<?php print admin_url(); ?>" class="nav-link <?php if (!$view): ?> active <?php endif; ?>">
                        <i class="mdi mdi-view-dashboard"></i> <strong><?php _e("Dashboard"); ?></strong>
                    </a>
                </li>
                <li><?php event_trigger('mw.admin.sidebar.li.first'); ?></li>
                <li class="nav-item dropdown <?php if ($view == 'content' and $action == false) {
                    print 'active';
                } elseif ($view == 'content' and $action != false) {
                    print 'active-parent';
                } ?>">
                    <a href="<?php print admin_url(); ?>view:content" class="nav-link dropdown-toggle <?php if ($view == 'content' and $action == false) {
                        print 'active';
                    } elseif ($view == 'content' and $action != false) {
                        print 'active-parent';
                    } ?>">
                        <i class="mdi mdi-earth"></i>
                        <?php _e("Website"); ?>
                    </a>

                    <div class="dropdown-menu">

                        <a href="<?php print admin_url(); ?>view:content/action:pages" class="dropdown-item <?php if ($action == 'pages'): ?> active <?php endif; ?>">
                            <?php _e("Pages"); ?>
                            <span class="btn btn-primary btn-rounded btn-icon btn-sm add-new" data-toggle="tooltip" title="<?php _e("Add new page") ?>" data-href="<?php print admin_url('view:content#action=new:page'); ?>"><i class="mdi mdi-plus"></i></span>
                        </a>
                        <a class="dropdown-item <?php if ($action == 'posts'): ?> active <?php endif; ?>" href="<?php print admin_url(); ?>view:content/action:posts">
                            <?php _e("Posts"); ?>
                            <span
                                    class="btn btn-primary btn-rounded btn-icon btn-sm add-new"
                                    data-toggle="tooltip"
                                    title="<?php _e("Add new post") ?>"
                                    data-href="<?php print admin_url('view:content#action=new:post'); ?>">
                                    <i class="mdi mdi-plus"></i>
                                </span>
                        </a>
                        <?php if ($shop_disabled == false AND mw()->module_manager->is_installed('shop') == true): ?>
                            <a
                                    class="dropdown-item <?php if ($action == 'products'): ?> active <?php endif; ?>"
                                    href="<?php print admin_url(); ?>view:content/action:products">
                                <?php _e("Products"); ?>
                                <span
                                        data-href="<?php print admin_url('view:content#action=new:product'); ?>"
                                        class="btn btn-primary btn-rounded btn-icon btn-sm add-new"
                                        data-toggle="tooltip" title="<?php _e("Add new product") ?>"><i class="mdi mdi-plus"></i></span>
                            </a>
                        <?php endif; ?>

                        <a class="dropdown-item <?php if ($action == 'categories'): ?> active <?php endif; ?>" href="<?php print admin_url(); ?>view:content/action:categories">
                            <?php _e("Categories"); ?>
                            <span class="btn btn-primary btn-rounded btn-icon btn-sm add-new" data-href="<?php print admin_url('view:content#action=new:category'); ?>" data-toggle="tooltip" title="<?php _e("Add new category") ?>"><i class="mdi mdi-plus"></i></span>
                        </a>
                    </div>
                </li>
                <?php if ($shop_disabled == false AND mw()->module_manager->is_installed('shop') == true): ?>
                    <?php
                    $shopCls = '';
                    if ($view == 'shop' and $action == false) {
                        $shopCls = "active";
                    } elseif ($view == 'shop' and $action != false) {
                        $shopCls = "active-parent";
                    } elseif ($view == 'modules' and $load_module == 'shop__coupons') {
                        $shopCls = "active";
                    }
                    ?>
                    <li class="nav-item dropdown <?php print $shopCls; ?>">
                        <a href="<?php print admin_url(); ?>view:shop" class="nav-link dropdown-toggle <?php print $shopCls; ?>">
                            <i class="mdi mdi-shopping"></i>
                            <span class="badge-holder"><?php _e("Shop"); ?>
                                <?php if ($view != 'shop' and $notif_count > 0) {
                                    print $order_notif_html;
                                }; ?>
                                </span>
                        </a>
                        <div class="dropdown-menu">
                            <a href="<?php print admin_url(); ?>view:shop/action:products" class="dropdown-item <?php if ($action == 'products'): ?> active <?php endif; ?>">
                                <?php _e("Products"); ?>
                            </a>
                            <a href="<?php print admin_url(); ?>view:shop/action:orders" class="dropdown-item <?php if ($action == 'orders'): ?> active <?php endif; ?>">
                                <?php _e("Orders"); ?>
                                <?php if ($view == 'shop') {
                                    print $order_notif_html;
                                } ?>

                            </a>
                            <a href="<?php print admin_url(); ?>view:shop/action:clients" class="dropdown-item <?php if ($action == 'clients'): ?> active <?php endif; ?>">
                                <?php _e("Clients"); ?>
                            </a>
                            <a href="<?php print admin_url(); ?>view:shop/action:options/" class="dropdown-item <?php if ($action == 'options'): ?> active <?php endif; ?>">
                                <?php _e("Shop settings"); ?>
                            </a>
                        </div>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="<?php print admin_url(); ?>view:modules" class="nav-link <?php if (
                    ($view == 'modules' AND $load_module != 'users' AND $load_module != 'shop__coupons')
                    ): ?> active <?php endif; ?>"><i class="mdi mdi-view-grid-plus"></i> <?php _e("Modules"); ?> </a></li>
                <?php if (mw()->ui->disable_marketplace != true): ?>
                    <li class="nav-item">
                        <a href="<?php print admin_url(); ?>view:packages" class="nav-link <?php if ($view == 'packages'): ?>active<?php endif; ?>">
                            <i class="mdi mdi-fruit-cherries"></i> <?php _e("Marketplace"); ?>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php if (!url_param('has_core_update') and ($view == 'settings')): ?> active <?php endif; ?>" href="<?php print admin_url(); ?>view:settings#option_group=website">
                        <i class="mdi mdi-cog"></i>
                        <span class="badge-holder"><?php _e("Settings"); ?></span>
                    </a>
                    <div class="dropdown-menu">

                        <a class="item-website dropdown-item" href="<?php print admin_url(); ?>view:settings#option_group=website">
                            <span class="mai-website"></span><strong><?php _e("Website"); ?></strong>
                        </a>

                        <a class="item-template dropdown-item" href="<?php print admin_url(); ?>view:settings#option_group=template">
                            <span class="mai-templates"></span><strong><?php _e("Template"); ?></strong>
                        </a>

                        <a class="item-users dropdown-item" href="<?php print admin_url(); ?>view:settings#option_group=users">
                            <span class="mai-login"></span><strong><?php _e("Login & Register"); ?></strong>
                        </a>

                        <a class="item-email dropdown-item" href="<?php print admin_url(); ?>view:settings#option_group=email">
                            <span class="mai-mail"></span><strong><?php _e("Email"); ?></strong>
                        </a>


                        <?php event_trigger('mw_admin_settings_menu'); ?>
                        <?php $settings_menu = mw()->module_manager->ui('admin.settings.menu'); ?>
                        <?php if (is_array($settings_menu) and !empty($settings_menu)): ?>
                            <?php foreach ($settings_menu as $item): ?>
                                <?php $module = (isset($item['module'])) ? module_name_encode($item['module']) : false; ?>
                                <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                <?php if ($module != 'admin') { ?>
                                    <a onclick="mw.url.windowHashParam('option_group', '<?php print $module ?>');return false;" class="dropdown-item <?php print $class ?>" href="#option_group=<?php print $module ?>">
                                        <span class="<?php print isset($item['icon']) ? $item['icon'] : ''; ?>"></span>
                                        <strong><?php print $title ?></strong>
                                    </a>
                                <?php } ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <a onclick="mw.url.windowHashParam('option_group', 'advanced');return false;" class="dropdown-item item-advanced" href="#option_group=advanced">
                            <span class="mai-options"></span>
                            <stong><?php _e("Advanced"); ?></stong>
                        </a>

                        <a onclick="mw.url.windowHashParam('option_group', 'language');return false;" class="dropdown-item item-language" href="#option_group=language">
                            <span class="mai-languages"></span>
                            <strong><?php _e("Language"); ?></strong>
                        </a>
                    </div>
                </li>


                <?php $load_module = url_param('load_module'); ?>
                <li <?php print 'class="nav-item dropdown ' . ($load_module == 'users' ? 'active' : '') . '"'; ?>>
                    <a class="nav-link dropdown-toggle <?php print ($load_module == 'users' ? 'active' : ''); ?>" href="<?php print admin_url('view:modules/load_module:users'); ?>">
                        <i class="mdi mdi-account-multiple"></i>
                        <?php _e("Users"); ?>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?php print admin_url('view:modules/load_module:users#edit-user=' . $user_id); ?>" id="main-menu-my-profile"><?php _e("My Profile"); ?></a>
                        <a class="dropdown-item" href="<?php print admin_url('view:modules/load_module:users'); ?>" id="main-menu-manage-users"><?php _e("Manage Users"); ?></a>
                        <?php if (mw()->ui->enable_service_links): ?>
                            <?php if (mw()->ui->custom_support_url): ?>
                                <a class="dropdown-item" href="<?php print mw()->ui->custom_support_url ?>"><strong><?php _e("Support"); ?></strong></a>
                            <?php else: ?>
                                <a class="dropdown-item" href="javascript:;" onmousedown="mw.contactForm();"><strong><?php _e("Support"); ?></strong></a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <a href="<?php print site_url(); ?>?editmode=y" class="go-live-edit-href-set dropdown-item"><?php _e("View Website"); ?></a>
                    </div>
                </li>
                <li class="nav-item"><a href="<?php print api_url('logout'); ?>" class="nav-link"><i class="mdi mdi-power"></i> <?php _e("Log out"); ?></a></li>
                <li><?php event_trigger('mw.admin.sidebar.li.last'); ?></li>


            </ul>


            <script>
                $(document).ready(function () {
                    mw.$('.go-live-edit-href-set').each(function () {
                        var el = $(this);
                        var href = el.attr('href');
                        if (href.indexOf("editmode") === -1) {
                            href = href + ((href.indexOf('?') === -1 ? '?' : '&') + 'editmode:y');
                        }
                        el.attr('href', href);
                    });
                });
            </script>

        </aside>
        <?php endif; ?>
