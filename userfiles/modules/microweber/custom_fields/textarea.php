<?php
if(!isset($data['input_class'])){
    $data['input_class'] = '';
}


?>
<div class="control-group form-group">
    <label class="mw-ui-label">
    <?php if(isset($data['name']) == true and $data['name'] != ''): ?>
    <?php print $data['name'] ?>
    <?php elseif(isset($data['name']) == true and $data['name'] != ''): ?>
    <?php print $data['name'] ?>
    <?php else : ?>
    <?php endif; ?>
  <?php if (isset($data['options']) == true and isset($data['options']["required"]) == true): ?>  
  <span style="color:red;">*</span>
  <?php endif; ?>
  </label>
  <?php if(isset($data['help']) == true and $data['help'] != ''): ?>
  <br />
  <small  class="mw-custom-field-help"><?php print $data['help'] ?></small>
  <?php endif; ?>
   <div class="controls">
    <textarea  <?php if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <?php endif; ?> <?php if (isset($data['input_class'])): ?> class="<?php print $data['input_class'] ?>"  <?php endif; ?>   data-custom-field-id="<?php print $data["id"]; ?>"  name="<?php print $data["name"]; ?>" ><?php print $data["value"]; ?></textarea>
  </div>
</div>
