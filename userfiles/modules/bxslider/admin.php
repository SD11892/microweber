<script>mw.lib.require('font_awesome5');</script>

<?php
$defaults = array(
    'images' => '',
    'primaryText' => 'My bxSlider',
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
if (!$module_template) {
    $module_template = 'default';
}
$module_template_clean = str_replace('.php', '', $module_template);

$default_skins_path = $config['path_to_module'] . 'templates/' . $module_template_clean . '/skins';
$template_skins_path = template_dir() . 'modules/bxslider/templates/' . $module_template_clean . '/skins';
$skins = array();

if (is_dir($template_skins_path)) {
    $skins = scandir($template_skins_path);
}

if (empty($skins) and is_dir($default_skins_path)) {
    $skins = scandir($default_skins_path);
}


$count = 0;
?>

<script>mw.require('prop_editor.js')</script>
<script>mw.require('module_settings.js')</script>
<script>mw.require('icon_selector.js')</script>
<script>mw.require('ui.css')</script>
<script>mw.require('wysiwyg.css')</script>

<script>
    $(window).on('load', function () {

        var skins = [];
        var fodlerItems = <?php print json_encode($skins); ?>;

        fodlerItems.forEach(function(item){
            if(item !== '.' && item !== '..'){
                skins.push(item.split('.')[0])
            }
        });
        this.bxSettings = new mw.moduleSettings({
            element:'#settings-box',
            header:'<i class="mw-icon-drag"></i> Move <a class="pull-right" data-action="remove"><i class="mw-icon-close"></i></a>',
            data: <?php print json_encode($json); ?>,
            schema:[
                {
                    interface:'select',
                    label:['Skin'],
                    id:'skin',
                    options:skins
                },
                {
                    interface:'icon',
                    label:['Icon'],
                    id:'icon'
                },
                {
                    interface:'text',
                    label:['Main text'],
                    id:'title'
                },
                {
                    interface:'text',
                    label:['Description'],
                    id:'Description'
                },
                {
                    interface:'text',
                    label:['URL'],
                    id:'url'
                },
                {
                    interface:'text',
                    label:['See more text'],
                    id:'seemore'
                },
                {
                    interface:'file',
                    id:'image',
                    label:'Add Image',
                    types:'images'
                }
            ]
        });
        $(this.bxSettings).on("change", function(e, val){
            $("#settingsfield").val(bxSettings.toString()).trigger("change")
        });
    });


</script>




<div class="mw-accordion">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> Settings
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-bxslider-settings">
                <input type="hidden" name="settings" id="settingsfield" value="" class="mw_option_field"/>

                <div class="mw-ui-field-holder text-right">
                    <span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded" onclick="bxSettings.addNew(0);"><i class="fas fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?></span>
                </div>

               <div id="settings-box"></div>

            </div>
            <!-- Settings Content - End -->
        </div>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> Templates
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>