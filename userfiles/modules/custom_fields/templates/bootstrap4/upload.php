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

                    <div class="custom-file custom-file-<?php echo($rand); ?>">
                        <input type="file" name="<?php echo $data["name_key"]; ?>" class="custom-file-input custom-file-input-<?php echo($rand); ?>" id="customFile<?php echo($rand); ?>">
                        <label class="custom-file-label custom-file-label-<?php echo($rand); ?>" for="customFile<?php echo($rand); ?>"><i class="mdi mdi-upload"></i> <?php _e("Browse"); ?></label>
                    </div>

                    <div class="alert alert-success mt-3" style="display: none;" id="val_<?php echo $rand; ?>"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-danger" id="upload_err<?php echo($rand); ?>" style="display:none;"></div>
</div>