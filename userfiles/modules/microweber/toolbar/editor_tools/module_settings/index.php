<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php $module_info = false;
    if (isset($params['module'])): ?>
        <?php $module_info = mw()->modules->get('one=1&ui=any&module=' . $params['module']); ?>
    <?php endif; ?>

    <script type="text/javascript" src="<?php print(mw()->template->get_apijs_settings_url()); ?>"></script>
    <script type="text/javascript" src="<?php print(mw()->template->get_apijs_url()); ?>"></script>
    <script src="<?php print mw_includes_url(); ?>api/jquery-ui.js"></script>


    <?php if (isset($params['live_edit_sidebar'])): ?>

        <script type="text/javascript">
            window.live_edit_sidebar = true;
        </script>
    <?php endif; ?>


    <script type="text/javascript">
        liveEditSettings = true;

        mw.require('<?php print mw_includes_url(); ?>default.css');
        mw.require('<?php print mw_includes_url(); ?>css/components.css');
        mw.require('<?php print mw_includes_url(); ?>css/admin.css');
        mw.require('<?php print mw_includes_url(); ?>css/admin-new.css');
        mw.require('<?php print mw_includes_url(); ?>css/fade-window.css');
        mw.require('<?php print mw_includes_url(); ?>css/popup.css');
        <?php if(_lang_is_rtl()){ ?>
        mw.require('<?php print mw_includes_url(); ?>css/rtl.css');
        <?php } ?>
        mw.require("events.js");
        mw.require("url.js");
        mw.require("tools.js");
        mw.require('admin.js');


        mw.require("liveadmin.js");
        mw.require("forms.js");
        mw.require('wysiwyg.js');
        mw.require("wysiwyg.css")
        mw.require('options.js');
        mw.lib.require('font_awesome5');
    </script>

    <style>
        #settings-main {
            min-height: 200px;
            overflow-x: hidden;
        }

        #settings-container {
            overflow: hidden;
            position: relative;
            min-height: 200px;
        }

        #settings-container:after {
            content: ".";
            display: block;
            clear: both;
            visibility: hidden;
            line-height: 0;
            height: 0;
        }
    </style>

    <?php
    $autoSize = true;
    if (isset($_GET['autosize'])) {
        $autoSize = $_GET['autosize'];
    }

    $type = '';
    if (isset($_GET['type'])) {
        $type = $_GET['type'];
    }

    $mod_id = $mod_orig_id = false;
    $is_linked_mod = false;

    if (isset($params['id'])) {
        $mod_orig_id = $mod_id = $params['id'];
    }

    if (isset($params['data-module-original-id']) and $params['data-module-original-id']) {
        $mod_orig_id = $params['data-module-original-id'];
    }
    if ($mod_id != $mod_orig_id) {
        $is_linked_mod = true;
    }
    ?>

    <script type="text/javascript">
        addIcon = function () {
            if (window.thismodal && thismodal.main) {
                var holder = $(".mw_modal_toolbar", thismodal.main);
                if ($('.mw_modal_icon', holder).length === 0) {
                    holder.prepend('<span class="mw_modal_icon"><img src="<?php print $module_info['icon']; ?>"></span>')
                }
            }
        };
        addIcon();

        autoSize = <?php  print $autoSize; ?>;
        settingsType = '<?php print $type; ?>';

        window.onbeforeunload = function () {
            $(mwd.body).addClass("mw-external-loading")
            window.parent.$('.module-modal-settings-menu-holder').remove();
        };

        mw_module_settings_info = "";
        <?php if(is_array($module_info)): ?>

        mw_module_settings_info = <?php print json_encode($module_info); ?>
        <?php
            $mpar = $params;
            if (isset($mpar['module_settings'])) {
                unset($mpar['module_settings']);
            }
            ?>

            mw_module_params =
        <?php print json_encode($mpar); ?>

        <?php endif; ?>

        if (typeof thismodal == 'undefined' && self !== parent && typeof this.name != 'undefined' && this.name != '') {
            var frame = parent.mw.$('#' + this.name)[0];
            thismodal = parent.mw.tools.modal.get(mw.tools.firstParentWithClass(frame, 'mw_modal'));
        }

        //var the_module_settings_frame = parent.mw.$('#' + this.name)[0];

        if (typeof thismodal != 'undefined' && thismodal != false) {
            var modal_title_str = '';
            if (typeof(mw_module_settings_info.name) == "undefined") {
                modal_title_str = "<?php _e("Settings"); ?>"
            } else {
                modal_title_str = mw_module_settings_info.name;
            }

            var ex_title = $(thismodal.main).find(".mw_modal_title").html();

            if (ex_title == '') {
                $(thismodal.main).find(".mw_modal_title").html(modal_title_str + '');
            }
            if (typeof thismodal.main.scrollTop == 'function') {
                thismodal.main.scrollTop(0);
            }

            __autoresize = function (force) {
                var force = force || false;
                var _old = thismodal.main.height();

                if (typeof thismodal.main.scrollTop == 'function') {
                    thismodal.main.scrollTop(0);
                }

                if (typeof thismodal.main[0] != 'undefined') {
                    parent.mw.tools.modal.resize("#" + thismodal.main[0].id, false, mw.$('#settings-container').height() + 25, false);
                    setTimeout(function () {
                        var _new = thismodal.main.height();
                        if (_new > _old || force) {
                            parent.mw.tools.modal.center("#" + thismodal.main[0].id, 'vertical');
                        }
                    }, 400)
                }
            }

            $(window).load(function () {
                if (typeof thismodal.main[0] != 'undefined') {

                    if (autoSize) {
                        parent.mw.tools.modal.resize("#" + thismodal.main[0].id, false, $('#settings-container').height() + 25, true);

                        $(mwd.body).bind('mouseup click DOMNodeInserted', function () {
                            setTimeout(function () {
                                __autoresize();
                            }, 99);
                        }).ajaxStop(function () {
                            setTimeout(function () {
                                __autoresize();
                            }, 99);
                        });

                        setInterval(function () {
                            __autoresize();
                        }, 99);

                        $(window.parent.window).bind("resize", function () {
                            if (parent != null) {
                                parent.mw.tools.modal.center("#" + thismodal.main[0].id);
                            }
                        });
                    }
                }
            });
        }

        $(window).load(function () {
            $(mwd.body).removeClass('mw-external-loading');
            $(mwd.body).ajaxStop(function () {
                $(mwd.body).removeClass('mw-external-loading');
            });

            addIcon();
        });

        $(window).load(function () {
            // add dropdown
            if (typeof thismodal != 'undefined' && typeof thismodal.main != 'undefined' && typeof thismodal.main[0] != 'undefined') {
                module_settings_opener_titlebar_holder = thismodal.main[0];
            } else {
                if (window.parent.mw.$('.js-module-titlebar-<?php print $params['id'] ?>').length > 0) {
                    module_settings_opener_titlebar_holder = window.parent.mw.$('.js-module-titlebar-<?php print $params['id'] ?>')[0];
                }

            }

            if (typeof module_settings_opener_titlebar_holder != 'undefined') {
                var toolbar = module_settings_opener_titlebar_holder.querySelector('.mw_modal_toolbar');
                if (!toolbar) {
                    var toolbar = module_settings_opener_titlebar_holder.querySelector('.js-module-sidebar-settings-menu-holder');
                }


                var is_module_tml_holder = $(toolbar).find(".js-module-modal-settings-menu-holder");
                if (is_module_tml_holder.length == 0) {

                    var dd = mwd.createElement('div');
                    //dd.id = 'module-modal-settings-menu-holder';
                    dd.className = 'mw-presets-dropdown module-modal-settings-menu-holder';
                    $(toolbar).append(dd);
                    /*******************************************************
                     Do not delete !!! Module template: list and 'Crete Module Template'

                     $(toolbar).append(dd);
                     *******************************************************/
                }

                mw.module_preset_linked_dd_menu_show_icon = function () {
                    var toolbar = module_settings_opener_titlebar_holder.querySelector('.mw_modal_toolbar');
                    var is_module_preset_tml_holder = $(".module-modal-preset-linked-icon", toolbar);

                    if (is_module_preset_tml_holder.length == 0) {
                        var linked_dd = window.parent.mwd.createElement('div');
                        // linked_dd.id = 'module-modal-preset-linked-icon';
                        linked_dd.class = 'module-modal-preset-linked-icon';
                        linked_dd.style.display = "none";
                        $(toolbar).prepend(linked_dd);

                    }

                    is_module_preset_tml_holder = window.parent.$(".module-modal-preset-linked-icon");
                    <?php if($is_linked_mod){  ?>
                    $(".module-modal-preset-linked-icon", toolbar).addClass('is-linked').show();
                    <?php  } else { ?>
                    $(".module-modal-preset-linked-icon", toolbar).removeClass('is-linked').hide();

                    <?php  } ?>
                }


                $(document).ready(function () {
                    //   window.top.module_settings_modal_reference = thismodal;
                    <?php if(is_array($module_info)): ?>

                    <?php $mod_adm = admin_url('load_module:') . module_name_encode($module_info['module']); ?>

                    var is_module_tml_holder = $(toolbar).find(".module-modal-settings-menu-holder");

                    if (is_module_tml_holder.length > 0) {
                        is_module_tml_holder.empty();

                        var holder = mwd.createElement('div');
                        holder.className = 'mw-module-presets-content';


                        var html = ""
                            + "<div id='module-modal-settings-menu-items<?php print $params['id'] ?>' module_id='<?php print $params['id'] ?>' module_name='<?php print $module_info['module'] ?>'>"
                            + "</div>"
                            + "<hr>"
                            + "<div class='module-modal-settings-menu-holder-2<?php print $params['id'] ?>'>"
                            + "<a href='<?php print $mod_adm  ?>'><?php _e("Go to admin"); ?></a></div>";

                        window.parent.modal_preset_manager_html_placeholder_for_reload = function () {
                            var modal_preset_manager_html_placeholder_for_reload_content = ""
                                + "<div id='module-modal-settings-menu-items-presets-holder<?php print $params['id'] ?>' module_id='<?php print $params['id'] ?>' module_name='<?php print $module_info['module'] ?>'>"
                                + "</div>"

                            var presetsthismodalid = module_settings_opener_titlebar_holder.id;

                            window.parent.module_settings_modal_reference_preset_editor_modal_id = presetsthismodalid;
                            window.parent.module_settings_modal_reference_window = window;

                            //  $('#module-modal-settings-menu-holder-open-presets').html('');

                            // HERE FOR DROPDOWN
                            window.parent.$('.module-modal-settings-menu-holder-open-presets', toolbar).html(modal_preset_manager_html_placeholder_for_reload_content);
                        };
                        var html = ""
                            + "<div class='module-modal-settings-menu-content'>" +
                            "<a  href='javascript:window.parent.modal_preset_manager_html_placeholder_for_reload();'>Presets</a>" +

                            "</div>"
                            + "<div class='module-modal-settings-menu-holder-open-presets' ></div>"

                        var btn = document.createElement('a');
                        btn.className = 'mw-module-presets-opener';
                        $(btn).on('click', function () {
                            $(this).parent().toggleClass('active');


                            var presets_mod = {};

                            presets_mod.module_id = '<?php print $params['id'] ?>'
                            presets_mod.module_name = '<?php print $params['module'] ?>'
                            presets_mod.id = 'presets-<?php print $params['id'] ?>'
                            //   presets_mod.mod_orig_id='<?php print $mod_orig_id ?>'
                            //  var src = mw.settings.site_url + "api/module?" + json2url(presets_mod);
                            var src = mw.settings.site_url + 'editor_tools/module_presets?' + json2url(presets_mod);


                            var mod_presets_iframe_html_fr = '' +
                                '<div class="js-module-presets-edit-frame">' +
                                '<iframe src="' + src + '" frameborder="0" style="overflow: hidden;" width="280" height="400" onload="this.parentNode.classList.remove(\'loading\')">' +
                                '</div>';

                            window.parent.$('#module-modal-settings-menu-items-presets-holder<?php print $params['id'] ?>').html(mod_presets_iframe_html_fr);
                            top.$(".mw-presets-dropdown .module").removeClass('module');

                            //window.parent.mw.load_module("editor/module_presets", '#module-modal-settings-menu-items-presets-holder<?php //print $params['id'] ?>//', function () {
                            //    setTimeout(function () {
                            //        top.$(".mw-presets-dropdown .module").removeClass('module');
                            //    }, 100)
                            //});
                        });

                        var module_has_editable_parent = window.parent.$('#<?php print $params['id'] ?>');

                        if (typeof(module_has_editable_parent[0]) != 'undefined' && window.parent.mw.tools.hasParentsWithClass(module_has_editable_parent[0], 'edit')) {
                            $(holder).append(html);
                            $(dd).prepend(btn);

                            is_module_tml_holder.append(holder);
                        }
                    }

                    window.parent.modal_preset_manager_html_placeholder_for_reload();
                    mw.module_preset_linked_dd_menu_show_icon();
                    <?php endif; ?>
                });
            }
        });


        $(window).on('load', function () {

            // $(document).ready(function() {
            mw.options.form('#settings-container', function () {
                if (mw.notification) {
                    mw.notification.success('<?php _e('Settings are saved') ?>');
                }
            });
        });


    </script>

</head>
<body class="mw-external-loading loading">
<div id="settings-main">
    <div id="settings-container">
        <div class="mw-module-live-edit-settings <?php print $params['id'] ?>"
             id="module-id-<?php print $params['id'] ?>">{content}
        </div>
    </div>
</div>

<form method="get" id="mw_reload_this_module_popup_form" style="display:none">
    <?php $mpar = $params;
    if (isset($mpar['module_settings'])) {
        unset($mpar['module_settings']);
    }

    ?>
    <?php if (is_array($params)): ?>
        <?php foreach ($params as $k => $item): ?>
            <input type="text" name="<?php print $k ?>" value="<?php print $item ?>"/>
        <?php endforeach; ?>
        <input type="submit"/>
    <?php endif; ?>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        __global_options_save_msg = function () {
            if (mw.notification != undefined) {
                mw.notification.success('<?php _e('Settings are updated!'); ?>');
            }

            if (window.parent.mw != undefined && window.parent.mw.reload_module != undefined) {
                window.parent.mw.reload_module("#<?php print $params['id'] ?>");
            }
        }


    });
</script>

<script type="text/javascript">

    if (typeof (frame) != 'undefined') {
        // mw.log(frame);
        $(frame).on('unload', function () {

            //     window.parent.$('.module-modal-settings-menu-holder', frame).remove();
        });
    }
</script>
<script type="text/javascript">
    //$(window).on('load', function () {
    //       setTimeout(function(){
    //
    //           mw.options.form('#settings-container', function () {
    //               if (mw.notification) {
    //                   mw.notification.success('<?php //_e('Settings are saved') ?>//');
    //               }
    //           });
    //
    //
    //       }, 1000);
    //
    //
    //
    //
    //   })
</script>
</body>
</html>
