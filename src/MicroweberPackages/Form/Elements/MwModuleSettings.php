<?php

namespace MicroweberPackages\Form\Elements;

class MwModuleSettings extends TextArea
{
    public function render()
    {
        $mwModuleSettingsId = str_random(9);

        $fieldsData = [];

        $data = json_encode($fieldsData);
        $schema = json_encode($this->getAttribute('schema'));

        $html = '
<script>mw.require(\'prop_editor.js\')</script>
<script>mw.require(\'module_settings.js\')</script>
<script>mw.require(\'icon_selector.js\')</script>
<script>mw.require(\'wysiwyg.css\')</script>

<script>
$(window).on(\'load\', function () {

    var data'.$mwModuleSettingsId.' = '.$data.';
    $.each(data'.$mwModuleSettingsId.', function (key) {
        if (typeof data'.$mwModuleSettingsId.'[key].images === \'string\') {
            data'.$mwModuleSettingsId.'[key].images = data'.$mwModuleSettingsId.'[key].images.split(\',\');
        }
    });

    this.bxSettings_'.$mwModuleSettingsId.' = new mw.moduleSettings({
        element: \'#settings-box\',
        header: \'<i class="mw-icon-drag"></i> Content #{count} <a class="pull-right" data-action="remove"><i class="mw-icon-close"></i></a>\',
        data: data'.$mwModuleSettingsId.',
        key: \'settings\',
        group: \'id\',
        autoSave: true,
        schema: '.$schema.'
    });

    $(bxSettings_'.$mwModuleSettingsId.').on(\'change\', function (e, val) {
        var final = [];
        $.each(val, function () {
            var current = $.extend({}, this);
            current.images = (current.images||[]).join(\',\');
            final.push(current)
        });
        $(\'#settingsfield'.$mwModuleSettingsId.'\').val(JSON.stringify(final)).trigger(\'change\')
    });
});
</script>

<!-- Settings Content -->
<div class="module-live-edit-settings module-'.$mwModuleSettingsId.'-settings">
    <input type="hidden" name="'.$this->getAttribute('name').'" id="settingsfield'.$mwModuleSettingsId.'" value="" class="mw_option_field" />
    <div class="mb-3">
        <span class="btn btn-primary btn-rounded" onclick="bxSettings_'.$mwModuleSettingsId.'.addNew(0, \'blank\');"> '. _e('Add new', true) . '</span>
    </div>
    <div id="settings-box"></div>
</div>
<!-- Settings Content - End -->';

        return $html;
    }
}
