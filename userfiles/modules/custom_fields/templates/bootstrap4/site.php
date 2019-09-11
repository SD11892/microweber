<?php
$rand = uniqid();
?>
<div class="col-<?php echo $settings['field_size']; ?>">
    <div class="form-group">
        <label class="col-form-label">
            <?php echo $data['name']; ?>
            <?php if ($settings['required']): ?>
                <span style="color: red;">*</span>
            <?php endif; ?>
        </label>

        <input type="url" class="form-control" id="custom_field_help_text<?php print $rand; ?>" <?php if ($settings['required']): ?>required="true"<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" name="<?php echo $data['name']; ?>" placeholder="<?php echo $data['placeholder']; ?>"/>

        <?php if ($data['help']): ?>
            <small class="form-text text-muted"><?php echo $data['help']; ?></small>
        <?php endif; ?>
    </div>
</div>
