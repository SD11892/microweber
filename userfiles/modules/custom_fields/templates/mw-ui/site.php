<?php
$rand = uniqid();
?>
<div class="mw-flex-col-md-<?php echo $settings['field_size']; ?>">
<div class="mw-ui-field-holder">

    <?php if($settings['show_label']): ?>
	<label class="mw-ui-label"> 
	<?php echo $data['name']; ?>
	<?php if ($settings['required']): ?>
	<span style="color: red;">*</span>
	<?php endif; ?>
	</label>
    <?php endif; ?>

	 <?php if ($data['help']): ?>
        <small class="mw-custom-field-help"><?php echo $data['help']; ?></small>
    <?php endif; ?>
	<div class="mw-ui-controls">
		<input type="url" class="mw-ui-field" id="custom_field_help_text<?php print $rand; ?>" <?php if ($settings['required']): ?>required="true"<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" name="<?php echo $data['name']; ?>" placeholder="<?php echo $data['placeholder']; ?>" />
	</div>
</div>
</div>