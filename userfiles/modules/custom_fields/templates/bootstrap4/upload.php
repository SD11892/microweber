<?php $up = 'up' . uniqid() . rand() . rand() . crc32($data['id']); ?>
<?php $rand = uniqid(); ?>

<div class="col-md-<?php echo $settings['field_size']; ?>">
    <div class="form-group">

        <?php if ($settings['show_label']): ?>
            <label class="control-label"><?php echo $data["name"]; ?>
                <?php if ($settings['required']): ?>
                    <span style="color:red;">*</span>
                <?php endif; ?>
            </label>
        <?php endif; ?>

        <div class="relative mw-custom-field-upload" id="upload_<?php echo($rand); ?>">
            <div class="row">
                <div class="col">
                    <button type="button" class="btn btn-primary w-auto" id="upload_button_<?php echo($rand); ?>"><i class="mdi mdi-upload"></i> <?php _e("Browse"); ?></button>
                    <input type="hidden" class="mw-ui-invisible-field" id="file_name<?php echo $data["name"]; ?>" autocomplete="off"/>

                    <input type="hidden" <?php if ($settings['required']) { ?> required <?php } ?> class="mw-ui-invisible-field" id="uploaded_file_src<?php echo($rand); ?>" name="<?php echo $data["name"]; ?>" autocomplete="off"/>

                    <div class="alert alert-success mt-3" style="display: none;" id="val_<?php echo $rand; ?>"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-danger" id="upload_err<?php echo($rand); ?>" style="display:none;"></div>
</div>

<script>
    // mw.require('tools.js');
    mw.require('files.js');
</script>
<script>
    formHasUploader = true;

    $(document).ready(function () {
        <?php echo $up; ?> = mw.files.uploader({
            multiple: false,
            name: '<?php echo $data["name"]; ?>',
            autostart: true,
            filetypes: '<?php if ($settings['options']['file_types']): ?><?php echo implode(",", $settings['options']['file_types']); ?> <?php endif ?>'
        });

        var local_id = '<?php echo($rand); ?>';


        $(<?php echo $up; ?>).bind('FilesAdded', function (frame, file) {
            mwd.getElementById('file_name<?php echo $data["name"]; ?>').value = file[0].name;
        });

        $(<?php echo $up; ?>).bind('progress', function (frame, file) {
            mw.$("#upload_progress_" + local_id + " .bar").width(file.percent + '%')
            mw.$("#upload_progress_" + local_id).show();

            mw.log(file)
        });

        $(<?php echo $up; ?>).bind('FileUploaded', function (frame, file) {
            mw.$("#uploaded_file_src<?php echo($rand); ?>").val(file.src);
            mw.$("#upload_<?php echo($rand); ?> input[type='text']").val(file.src);
            mw.$("#upload_progress_" + local_id).hide();
            mw.$("#upload_progress_" + local_id + " .bar").width(0);
            mw.$("#upload_err" + local_id).hide();
            mw.$("#val_<?php echo $rand; ?>").html(file.src.split('/').pop());
            mw.$("#val_<?php echo $rand; ?>").show();
        });


        $(<?php echo $up; ?>).bind('error', function (frame, file) {
            mw.$("#upload_progress_" + local_id).hide();
            mw.$("#upload_err" + local_id).show().html("<strong>" + file.name + "</strong> - Invalid filetype!");
            mw.$("#upload_progress_" + local_id + " .bar").width(0);
            mw.$("#val_<?php echo $rand; ?>").empty();
            mw.$("#val_<?php echo $rand; ?>").hide();

        });


        $(<?php echo $up; ?>).bind('responseError', function (frame, json) {
            mw.$("#upload_progress_" + local_id).hide();
            mw.$("#upload_err" + local_id).show().html("<strong>Error " + json.error.code + "</strong> - " + json.error.message);
            mw.$("#upload_progress_" + local_id + " .bar").width(0);

            mw.$("#val_<?php echo $rand; ?>").empty();
            mw.$("#val_<?php echo $rand; ?>").hide();
        });

        mwd.getElementById('upload_button_<?php echo($rand); ?>').appendChild(<?php echo $up; ?>);

        <?php if (($settings['rel_type'] == 'module' || $settings['rel_type'] == 'modules') && $settings['rel_id']) : ?>
        <?php echo $up; ?>.
        contentWindow.onload = function () {
            mw.postMsg(<?php echo $up; ?>.contentWindow, {
                rel: "<?php echo($settings['rel_type']); ?>",
                custom_field_id: "<?php echo($data['id']); ?>",
                rel_id: "<?php echo($settings['rel_id']); ?>"
            });
        }
        <?php endif; ?>
    });
</script>