<div class="module-live-edit-settings">
    <span class="mw-ui-btn mw-ui-btn-invert pull-right" onclick="slickslider.create();">Add new slide</span>

    <h2>Slick Slider - Settings</h2>
    <module type="admin/modules/templates" simple="true"/>

    <div id="slickslider-settings">

        <style>
            .slickslider-skinselector {
                width: 150px;
            }

            .slickslider-images-holder .bgimg {
                width: 120px;
                height: 120px;
                border: 1px solid #eee;
                box-shadow: 0 2px 2px -2px #000;
                background-size: contain;
            }

            .slickslider-setting-item {
                margin: 15px 0;
            }

            .slickslider-images-holder .mw-icon-close {
                position: absolute;
                top: 1px;
                right: 1px;
                cursor: pointer;
                z-index: 2;
                display: block;
                width: 20px;
                height: 20px;
                text-align: center;
                padding: 4px;
                background-color: white;
            }

            .slickslider-images-holder .mw-icon-close:hover {
                background-color: black;
                color: white;
            }

            .slickslider-images-holder {
                overflow: auto;
            }

            .slickslider-images-holder li {
                list-style: none;
                float: left;
                width: 120px;
                margin: 0 12px 12px 0;
                cursor: -moz-grab;
                cursor: -webkit-grab;
                cursor: grab;
                background: white;
                position: relative;
            }

            .slickslider-setting-item > .mw-ui-box-header {
                cursor: pointer;
            }

            .mw-icon-drag {
                cursor: -moz-grab;
                cursor: -webkit-grab;
                cursor: grab;
            }

            .mw-ui-box-header .mw-icon-close {
                margin-right: 12px;
                font-size: 14px !important;
            }
        </style>

        <?php
        $defaults = array(
            'images' => '',
            'primaryText' => 'My SlickSlider',
            'secondaryText' => 'Your slogan here',
            'seemoreText' => 'See more',
            'url' => '',
            'urlText' => '',
            'skin' => 'default'
        );

        $settings = get_option('settings', $params['id']);
        $json = json_decode($settings, true);

        if (isset($json) == false or count($json) == 0) {
            $json = array(0 => $defaults);
        }
        $module_template = get_option('data-template', $params['id']);
        if(!$module_template){
            $module_template = 'default';
        }
        $module_template_clean = str_replace('.php','',$module_template);

        $default_skins_path = $config['path_to_module'] . 'templates/'.$module_template_clean.'/skins';
        $template_skins_path = template_dir() . 'modules/slickslider/templates/'.$module_template_clean.'/skins';
        $skins = array();

        if (is_dir($template_skins_path)) {
            $skins = scandir($template_skins_path);
        }

        if (empty($skins) and is_dir($default_skins_path)) {
            $skins = scandir($default_skins_path);
        }

        $skinSelector = '<select class="mw-ui-field slickslider-skinselector">';

        foreach ($skins as $skin) {
            $isphp = substr($skin, -4) == '.php';
            if ($isphp) {
                $val = substr($skin, 0, -4);
                $skinSelector .= '<option value="' . $val . '">' . $val . '</option>';
            }
        }

        $skinSelector .= '</select>';
        $count = 0;

        foreach ($json as $slide) {
            $count++;
            if (isset($slide['skin']) == false or $slide['skin'] == '') {
                $slide['skin'] = 'default';
            }
            ?>


            <div class="mw-ui-box  slickslider-setting-item" data-skin="<?php print $slide['skin']; ?>">
                <div class="mw-ui-box-header" onclick="mw.accordion(this.parentNode);">
                    <span class="mw-icon-drag pull-right show-on-hover"></span>
                    <span class="mw-icon-close show-on-hover pull-right" onclick="deleteslicksliderslide(event);"></span>
                    <span class="mw-icon-gear"></span> Slide <?php print $count; ?>
                </div>

                <div class="mw-ui-box-content mw-accordion-content" style="display: none;">
                    <div class="mw-ui-row-nodrop">
                        <div class="mw-ui-col" style="width: 170px;">
                            <div class="mw-ui-col-container">
                                <label class="mw-ui-label">Skin</label>
                                <?php print $skinSelector; ?>
                            </div>
                        </div>

                        <div class="mw-ui-col">
                            <div class="mw-ui-col-container">
                                <label class="mw-ui-label">Main text</label>
                                <input type="text" class="mw-ui-field slickslider-main-text w100 " value="<?php print $slide['primaryText']; ?>">
                            </div>
                        </div>

                        <div class="mw-ui-col">
                            <div class="mw-ui-col-container">
                                <label class="mw-ui-label">Description</label>
                                <input type="text" class="mw-ui-field slickslider-secondary-text w100" value="<?php print $slide['secondaryText']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label">URL</label>
                        <input type="text" class="mw-ui-field slickslider-url" value="<?php print $slide['url']; ?>">
                    </div>

                    <?php if (!isset($slide['seemoreText'])) {
                        $slide['seemoreText'] = 'See more';
                    } ?>

                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label">See more text</label>
                        <input type="text" class="mw-ui-field slickslider-seemore" value="<?php print $slide['seemoreText']; ?>">
                    </div>


                    <div class="slickslider-images">
                        <ul class="slickslider-images-holder">
                            <?php
                            if (isset($slide['images'])) {
                                $arr = explode(',', $slide['images']);
                                if (sizeof($arr) > 0) {
                                    foreach ($arr as $image) {
                                        if ($image != '') {
                                            ?>
                                            <li><span class="bgimg" data-image="<?php print $image; ?>" style="background-image:url(<?php print $image; ?>);"></span><span class="mw-icon-close"
                                                                                                                                                                           onclick="deleteslicksliderimage(event);"></span>
                                            </li>
                                        <?php }
                                    }
                                }
                            } ?>
                        </ul>

                        <span class="mw-ui-btn mw-ui-btn-invert slickslider-uploader"><span class="mw-icon-upload"></span>Add Image</span>
                    </div>
                </div>
            </div>
        <?php } ?>

        <script>
            deleteslicksliderimage = function (e) {
                $(mw.tools.firstParentWithTag(e.target, 'li')).remove();
                slickslider.save();
            };

            deleteslicksliderslide = function (e) {
                if (confirm('Are you sure you want to delete this slide')) {
                    $(mw.tools.firstParentWithClass(e.target, 'slickslider-setting-item')).remove();
                    slickslider.save();
                }
            };

            slickslider = {
                collect: function () {
                    var data = {}, all = mwd.querySelectorAll('#slickslider-settings .slickslider-setting-item'), l = all.length, i = 0;

                    for (; i < l; i++) {
                        var item = all[i];
                        data[i] = {};
                        data[i]['primaryText'] = item.querySelector('.slickslider-main-text').value;
                        data[i]['secondaryText'] = item.querySelector('.slickslider-secondary-text').value;
                        data[i]['seemoreText'] = item.querySelector('.slickslider-seemore').value;
                        data[i]['url'] = item.querySelector('.slickslider-url').value;
                        data[i]['skin'] = item.querySelector('.slickslider-skinselector').value;
                        data[i]['images'] = '';

                        if (item.querySelector('.slickslider-images-holder .bgimg') !== null) {
                            var imgs = item.querySelectorAll('.slickslider-images-holder .bgimg'), imgslen = imgs.length, ii = 0;

                            for (; ii < imgslen; ii++) {
                                if ((ii + 1) !== imgslen) {
                                    data[i]['images'] += $(imgs[ii]).dataset('image') + ',';
                                }

                                else {
                                    data[i]['images'] += $(imgs[ii]).dataset('image');
                                }
                            }
                        }
                    }

                    return data;
                },

                save: function () {
                    mw.$('#settingsfield').val(JSON.stringify(slickslider.collect())).trigger('change');
                },

                initItem: function (item) {
                    $(item.querySelectorAll('input[type="text"]')).bind('keyup', function () {
                        mw.on.stopWriting(this, function () {
                            slickslider.save();
                        });
                    });

                    var skin = $(item).dataset('skin');
                    $('.slickslider-skinselector', item).val(skin);
                    $(item.querySelectorAll('select')).bind('change', function () {
                        slickslider.save();
                    });

                    var up = mw.uploader({
                        filetypes: 'images',
                        element: item.querySelector('.slickslider-uploader')
                    });

                    $(up).bind('FileUploaded', function (a, b) {
                        $(item.querySelector('.slickslider-images-holder')).append('<li><span class="bgimg" data-image="' + b.src + '" style="background-image:url(' + b.src + ');"></span><span class="mw-icon-close" onclick="deleteslicksliderimage(event);"></span></li>');
                        slickslider.save();
                    });

                    $(item.querySelector('.slickslider-images-holder')).sortable({
                        stop: function () {
                            slickslider.save();
                        }
                    });
                },

                initSlideSettings: function () {
                    var all = mwd.querySelectorAll('#slickslider-settings .slickslider-setting-item'), l = all.length, i = 0;
                    for (; i < l; i++) {
                        if (!!all[i].prepared) continue;
                        var item = all[i];
                        item.prepared = true;
                        slickslider.initItem(item);
                    }
                },

                create: function () {
                    var last = $('.slickslider-setting-item:last');
                    var html = last.html();
                    var item = mwd.createElement('div');
                    item.className = last.attr("class");
                    item.innerHTML = html;
                    $(item.querySelectorAll('input')).val('');
                    $(item.querySelectorAll('.slickslider-images-holder .bgimg')).remove();
                    $(item.querySelectorAll('.mw-uploader')).remove();
                    last.after(item);
                    slickslider.initItem(item);
                }
            }

            $(window).bind('load', function () {
                thismodal.resize(800);
                slickslider.initSlideSettings();
                mw.$("#slickslider-settings").sortable({
                    items: "> .slickslider-setting-item",
                    handle: "> .mw-ui-box-header .mw-icon-drag",
                    axis: 'y',
                    stop: function () {
                        slickslider.save();
                    }
                });
            });
        </script>
    </div>
</div>

<input type="hidden" name="settings" id="settingsfield" value="" class="mw_option_field"/>