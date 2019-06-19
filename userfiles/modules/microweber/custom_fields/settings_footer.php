
<?php 
$field_size = get_default_field_size_option();
if (isset($data['options']['field_size'][0])) {
	$field_size = $data['options']['field_size'][0];
}
if (isset($data['options']['field_size']) && is_string($data['options']['field_size'])) {
	$field_size = $data['options']['field_size'];
}
?>

<?php
if($data['type'] !== 'breakline'):
?>
<div class="mw-custom-field-group">
    <label class="mw-custom-field-label" for="custom_field_width_size<?php print $rand; ?>"><b><?php _e('Organaize in columns'); ?></b></label>
    <div class="mw-custom-field-form-controls">
       <select class="mw-ui-field" name="options[field_size]">
       
       	<?php foreach(get_field_size_options() as $optionKey=>$optionValue): ?> 
        <option <?php if($field_size == $optionKey):?>selected="selected"<?php endif; ?> value="<?php echo $optionKey; ?>"><?php echo $optionValue; ?></option> 
        <?php endforeach; ?>
        
       </select>
    </div>
</div>

<div class="mw-custom-field-group<?php print $hidden_class ?>">
    <label class="mw-custom-field-label" for="custom_field_required<?php print $rand; ?>"><?php _e('Required'); ?></label>
    <div class="mw-custom-field-form-controls">
        <label class="mw-ui-check">


              <input type="checkbox" class="mw-ui-field"  name="custom_field_required" id="custom_field_required<?php print $rand; ?>" value="y" <?php if (trim($data['custom_field_required']) == 'y'): ?> checked="checked"  <?php endif; ?> >
              <span></span>
            </label>

            <?php _e('Is this field Required?'); ?>
    </div>
</div>


<div class="mw-custom-field-group<?php print $hidden_class ?>">
    <label class="mw-custom-field-label"><?php _e('Active'); ?></label>
    <div class="mw-custom-field-form-controls">
        <label class="radio">
            <input type="radio" class="mw-ui-field" name="custom_field_is_active"   <?php if (trim($data['custom_field_is_active']) == 'y'): ?> checked="checked"  <?php endif; ?>  value="y">
            <?php _e('Yes'); ?> </label>
        <label class="radio">
            <input type="radio" class="mw-ui-field" name="custom_field_is_active" <?php if (trim($data['custom_field_is_active']) == 'n'): ?> checked="checked"  <?php endif; ?>   value="n">
            <?php _e('No'); ?> </label>
    </div>
</div>
<div class="mw-custom-field-group<?php print $hidden_class ?>">
    <label class="mw-custom-field-label" ><?php _e('Help text'); ?></label>
    <div class="mw-custom-field-form-controls">
        <input type="text"  name="custom_field_help_text" class="mw-ui-field"   value="<?php print ($data['custom_field_help_text']) ?>"  id="custom_field_help_text<?php print $rand; ?>">
    </div>
</div>
<div class="form-actions custom-fields-form-actions">

    <script>

         __save__global_id = '#custom_fields_edit<?php print $rand; ?>';
         $(document).ready(function(){
           if(typeof __custom_fields_editor_binded == 'undefined'){
                __custom_fields_editor_binded = true;
                mw.$("#custom-field-editor").keyup(function(e){
                  if(e.target.name == 'name'){
                      $(this).find('.custom-field-edit-title strong').html(e.target.value);
                  }
                });
           }

        });


    </script>

</div>
<?php endif; ?> 

</div>
