<?php
$field = false;

if (isset($params['field-id'])) {
    $field = get_custom_field_by_id($params['field-id']);
}
?>
<style>
    .mw-admin-custom-field-value-edit-inline-holder .delete-custom-fields {
        visibility: hidden;
    }

    .mw-admin-custom-field-value-edit-inline-holder:hover .delete-custom-fields {
        visibility: visible;
    }

    .mw-admin-custom-field-value-edit-inline-holder:not(.mw-admin-custom-field-checkbox) {
        min-width: 100px;
        position: relative;
        padding-right: 30px !important;
    }

    .mw-admin-custom-field-value-edit-inline:not(.mw-admin-custom-field-checkbox):empty:before {
        content: 'Edit here';
        display: block;
        position: absolute;
        top: 5px;
        padding-right: 10px !important;
        color: #919191;
    }

    .mw-admin-custom-field-value-edit-inline-holder:hover .mw-admin-custom-field-value-edit-inline:not(.mw-admin-custom-field-checkbox):after {
        position: absolute;
        top: 5px;
        display: inline-block;
        font: normal normal normal 24px/1 "Material Design Icons";
        text-rendering: auto;
        line-height: inherit;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        content: '\F064F';
        font-size: 14px;
        right: 6px;
        color: #919191;
    }
</style>
<script>
    mw.on.moduleReload('<?php print $params['id']; ?>', function () {
        mw.admin.custom_fields.initValues(mwd.getElementById('<?php print $params['id']; ?>').querySelectorAll('.mw-admin-custom-field-name-edit-inline, .mw-admin-custom-field-placeholder-edit-inline, .mw-admin-custom-field-value-edit-inline'));
    });
</script>

<div class="">
    <?php if (isset($field['type']) and ($field['type'] == 'select' or $field['type'] == 'dropdown' or $field['type'] == 'checkbox' or $field['type'] == 'radio')): ?>
        <?php
        if (isset($field['values']) and is_array($field['values'])) {
            $vals = $field['values'];
        } elseif (isset($field['value'])) {
            $vals = $field['value'];
        } else {
            $vals = '';
        }
        if (is_string($vals)) {
            $vals = array($vals);
        }
        ?>

        <span class="custom-fields-values-holder d-flex flex-wrap">
        <?php $i = 0; ?>
            <?php foreach ($vals as $val): ?>
                <?php $i++; ?>
                <span class="mw-admin-custom-field-value-edit-inline-holder mw-admin-custom-field-checkbox bg-primary-opacity-1 d-inline-flex mr-2 my-1 p-0">
                <small class="mw-admin-custom-field-value-edit-inline p-1 text-dark" data-id="<?php print $field['id']; ?>"><?php print $val; ?></small>
                <small class="delete-custom-fields bg-danger text-white p-1" onclick="mw.admin.custom_fields.deleteFieldValue(this);"><i class="mdi mdi-close"></i></small>
            </span>
            <?php endforeach; ?>
    </span>

        <span class="btn-create-custom-field-value btn btn-primary btn-sm py-2 px-0 d-inline-flex align-items-center justify-content-center show-on-hover" data-id="<?php print $field['id']; ?>">
        <i class="mdi mdi-plus mdi-16px"></i>
    </span>
    <?php elseif (isset($field['type']) and ($field['type'] == 'text' or $field['type'] == 'message' or $field['type'] == 'textarea' or $field['type'] == 'title')): ?>
        <textarea class="mw-admin-custom-field-value-edit-text form-control bg-primary-opacity-1 border-0 border-radius-0" style=" width:100%; overflow:hidden;" data-id="<?php print $field['id']; ?>"><?php print $field['value']; ?></textarea>
    <?php elseif (isset($field['type']) and (($field['type'] == 'address') or $field['type'] == 'upload')): ?>
        <div style="width:100%; display:block; min-height:20px;" onclick="mw.admin.custom_fields.edit_custom_field_item('#mw-custom-fields-list-settings-<?php print $field['id']; ?>',<?php print $field['id']; ?>);"><?php print $field['values_plain']; ?></div>

    <?php else: ?>
        <?php $vals = '';
        if ($field['values_plain'] != '') {
            $vals = $field['values_plain'];
        } elseif (is_string($field['value'])) {
            $vals = $field['value'];
        }
        ?>
        <span class="custom-fields-values-holder">
        <span class="mw-admin-custom-field-value-edit-inline-holder bg-primary-opacity-1 d-inline-block px-3 py-1">
            <small class="mw-admin-custom-field-value-edit-inline px-1 py-1" data-id="<?php print $field['id']; ?>"><?php print $vals; ?></small>
        </span>
    </span>
    <?php endif; ?>
</div>