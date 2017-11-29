<?php only_admin_access() ?>
<script type="text/javascript">
    mw.require("<?php print mw_includes_url(); ?>css/wysiwyg.css");
</script>
<?php

$settings = get_option('settings', $params['id']);

if ($settings == false) {
    if (isset($params['settings'])) {
        $settings = $params['settings'];
    }
}

$defaults = array(
    'title' => '',
    'id' => 'tab-' . uniqid(),
    'icon' => ''
);

$json = json_decode($settings, true);

if (isset($json) == false or count($json) == 0) {
    $json = array(0 => $defaults);
}

?>


<div id="mw-tabs-settings" style="padding: 10px;">
    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
        <a href="javascript:;" class="mw-ui-btn active tabnav">Tabs</a>
        <a href="javascript:;" class="mw-ui-btn tabnav">Settings</a>
    </div>
    <div class="mw-ui-box">
        <div class="mw-ui-box-content tabitem">
            <input type="hidden" class="mw_option_field" name="settings" id="settingsfield"/>
            <a class="mw-ui-btn" href="javascript:tabs.create()">Add new tab</a>
            <div id="tab-settings">
                <?php
                $count = 0;
                foreach ($json as $slide) {
                    $count++;

                    if (!isset($slide['id'])) {
                        $slide['id'] = 'tab-' . uniqid();
                    }
                    ?>
                    <div class="mw-ui-box  tab-setting-item" id="tab-setting-item-<?php print $count; ?>">
                        <div class="mw-ui-box-header"><a class="pull-right" href="javascript:tabs.remove('#tab-setting-item-<?php print $count; ?>');">x</a></div>
                        <div class="mw-ui-box-content mw-accordion-content">
                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-label">Title</label>
                                <input type="text" class="mw-ui-field tab-title w100 " value="<?php print $slide['title']; ?>">
                                <label class="mw-ui-label">Icon</label>
                                <input type="text" class="mw-ui-field tab-icon w100" value="<?php print $slide['icon']; ?>">
                                <input type="hidden" name="id" class="tab-id" value="<?php print $slide['id']; ?>">
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <script>

                tabs = {
                    init: function (item) {
                        $(item.querySelectorAll('input[type="text"],textarea')).bind('keyup', function () {
                            mw.on.stopWriting(this, function () {
                                tabs.save();
                            });
                        });

                        $('.tab-icon').on("change", function (e, el) {
                            tabs.save();
                        });
                        $('.tab-icon').on("click", function (e, el) {
                            el = $(this)[0];
                            mw.iconSelector._activeElement = el;
                            mw.iconSelector.popup(true);
                        });


                    },

                    collect: function () {
                        var data = {}, all = mwd.querySelectorAll('.tab-setting-item'), l = all.length, i = 0;
                        for (; i < l; i++) {
                            var item = all[i];
                            data[i] = {};
                            data[i]['title'] = item.querySelector('.tab-title').value;
                            data[i]['icon'] = item.querySelector('.tab-icon').value;
                            data[i]['id'] = item.querySelector('.tab-id').value;
                        }
                        return data;
                    },
                    save: function () {
                        mw.$('#settingsfield').val(JSON.stringify(tabs.collect())).trigger('change');
                    },


                    create: function () {
                        var last = $('.tab-setting-item:last');
                        var html = last.html();
                        var item = mwd.createElement('div');
                        item.className = last.attr("class");
                        item.innerHTML = html;
                        $(item.querySelectorAll('input')).val('');
                        $(item.querySelectorAll('[name=id]')).val('tab-' + mw.random());

                        $(item.querySelectorAll('.mw-uploader')).remove();
                        last.after(item);
                        tabs.init(item);
                    },

                    remove: function (element) {
                        var txt;
                        var r = confirm("Are you sure?");
                        if (r == true) {
                            $(element).remove();
                            tabs.save();
                        }
                    },


                }


                $(document).ready(function () {
                    var all = mwd.querySelectorAll('.tab-setting-item'), l = all.length, i = 0;
                    for (; i < l; i++) {
                        if (!!all[i].prepared) continue;
                        var item = all[i];
                        item.prepared = true;
                        tabs.init(item);
                    }
                });


                $(document).ready(function () {
                    if (typeof(window.parent.mw) != 'undefined') {
                        window.parent.mw.drag.save();
                    }

                    $('#tab-settings').sortable({
                        handle: '.mw-ui-box-header',
                        items: ".tab-setting-item",

                        update: function (event, ui) {
                            tabs.save();
                        }
                    });
                });

            </script>
        </div>
        <div class="mw-ui-box-content tabitem" style="display: none">
            <div class="row">
                <div class="col-xs-12">
                    <module type="admin/modules/templates"/>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        mw.tabs({
            nav: '#mw-tabs-settings  .tabnav',
            tabs: '#mw-tabs-settings .tabitem'
        });
    });
</script>


