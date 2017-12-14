<?php $rand = rand(); ?>

<?php

if(!isset($data['input_class'])){
    $data['input_class'] = '';
}


?>
<?php if(!isset($data['make_select'])) : ?> 
<div class="mw-custom-field-group mw-custom-field-price">
  <label class="mw-custom-field-label" ><?php print $data["name"]; ?></label>
  <div class="mw-custom-field-form-controls">
  
  <?php print $data["value"]; ?>
    <input type="hidden"   <?php if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <?php endif; ?>  data-custom-field-id="<?php print $data["id"]; ?>"  name="<?php print $data["type"]; ?>" id="custom_field_help_text<?php print $rand; ?>" value="<?php print $data["value"]; ?>">
   
    <?php if(isset($data['options']) == true and isset($data['options']["old_price"]) == true): ?> <span style="text-decoration: line-through"><?php print $data['options']["old_price"][0]; ?></span>  <?php endif; ?>

  </div>
</div>
<?php else: ?>

 <option   <?php if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <?php endif; ?> <?php if (isset($data['input_class'])): ?> class="<?php print $data['input_class'] ?>"  <?php endif; ?>  data-custom-field-id="<?php print $data["id"]; ?>"  name="<?php print $data["type"]; ?>"   value="<?php print $data["value"]; ?>"><?php print $data["name"]; ?></option>
<?php endif; ?>
