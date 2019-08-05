<?php only_admin_access(); ?>

<?php 
$template_id = (isset($params['data_template_id']) ? $params['data_template_id'] : '');
if (!empty($template_id)) {
$template = get_mail_template_by_id($template_id);
} else {
	$template = array();
	$template['name'] = '';
	$template['type'] = 'new_comment';
	$template['from_name'] = get_option('email_from_name','email');
	$template['from_email'] = get_option('email_from','email');
	$template['copy_to'] = '';
	$template['subject'] = '';
	$template['message'] = '';
	$template['id'] = '';
}
?>

<script>
	$("#edit-mail-template-form").submit(function (event) {
	    event.preventDefault();
	    var data = $(this).serialize();
	    var url = "<?php print api_url('save_mail_template'); ?>";
	    var post = $.post(url, data);
	    post.done(function (data) {
	        mw.reload_module("admin/mail_templates");
	        mw.reload_module("admin/mail_templates/list");

	     // Reload popup modal
	        mw.load_module('admin/mail_templates/admin', '#mw_admin_mail_templates_manage', null, null);
	        mw.reload_module('admin/mail_templates/select_template');
	        
	    }); 
	});

    NewMailEditor = mw.editor({
        element:mwd.getElementById('editorAM'),
        hideControls:['format', 'fontsize', 'justifyfull'],
        height:400,
        addControls: mwd.getElementById('editorctrls').innerHTML,
        ready: function (content) {
            content.defaultView.mw.dropdown();
            mw.$("#email_content_dynamic_vals li", content).bind('click', function () {
            	NewMailEditor.api.insert_html($(this).attr('value'));
            });
        }
   });
    $(NewMailEditor).bind('change', function () {
		<?php if ($template['id'] == ''): ?>
		<?php endif; ?>
    });

    function cancelTemplateEdit() {
    	mw.reload_module('admin/mail_templates');

    	// Reload popup modal
    	mw.load_module('admin/mail_templates/admin', '#mw_admin_mail_templates_manage', null, null);
    	mw.reload_module('admin/mail_templates/select_template');
    }
</script>

<div id="editorctrls" style="display: none">

    <span class="mw_dlm"></span>
    <div style="width: 112px;" data-value="" title="<?php _e("These values will be replaced with the actual content"); ?>" id="email_content_dynamic_vals" class="mw-dropdown mw-dropdown-type-wysiwyg mw-dropdown-type-wysiwyg_blue mw_dropdown_action_dynamic_values">
        <span class="mw-dropdown-value">
            <span class="mw-dropdown-arrow"></span>
            <span class="mw-dropdown-val"><?php _e("E-mail Values"); ?></span>
        </span>
        <div class="mw-dropdown-content">
            <ul>
	            <?php 
	            $mailTypes = get_mail_template_fields($template['type']);
	            if (!empty($mailTypes)):
	            ?>
				<?php foreach($mailTypes as $field): ?>
                <li value="<?php echo $field['tag']; ?>"><a href="javascript:;"><?php _e($field['name']); ?></a></li>
               <?php endforeach; ?>
               <?php endif; ?>
            </ul>
        </div>
    </div>

</div>

<form id="edit-mail-template-form">

  <h3>Edit mail template</h3>
  <br />
  <div class="mw-flex-row">
  
   <div class="mw-flex-col-md-5">
  <label class="mw-ui-label">Template Name</label> 
  <input type="text" name="name" value="<?php echo $template['name']; ?>" class="mw-ui-field" style="width:100%;">
  </div>
  
   <div class="mw-flex-col-md-12"><br /></div>
   
   <div class="mw-flex-col-md-5">
   <label class="mw-ui-label">Template Type</label> 
  	<select name="type" class="mw-ui-field js-template-type" style="width:100%;">  
  	<?php foreach(get_mail_template_types() as $type):?>
  	<option value="<?php echo $type; ?>" <?php if($type==$template['type']):?>selected="selected"<?php endif; ?>><?php echo $type; ?></option>
  	<?php endforeach; ?>
  	</select>	
  </div>
  
   <div class="mw-flex-col-md-12"><br /></div>
  
  <div class="mw-flex-col-md-2">
  <label class="mw-ui-label">From Name</label>
  <input type="text" name="from_name" value="<?php echo $template['from_name']; ?>" class="mw-ui-field" style="width:100%;">
  </div>
  
  <div class="mw-flex-col-md-3">
  <label class="mw-ui-label">From Email</label>
  <input type="text" name="from_email" value="<?php echo $template['from_email']; ?>" class="mw-ui-field" style="width:100%;">
  </div>
  
  <div class="mw-flex-col-md-12"></div>
  
  <div class="mw-flex-col-md-5">
  <br />
  <label class="mw-ui-label">Copy To</label>
   <input type="text" name="copy_to" class="mw-ui-field" value="<?php echo $template['copy_to']; ?>" style="width:100%;">
  </div>
  
  <div class="mw-flex-col-md-12"></div>
   
	 <div class="mw-flex-col-md-5">
	 	<br />
		<label class="mw-ui-label"><?php _e("Email attachments"); ?></label>  
		<?php 
		$template_id_attachment = '';
		if (is_int($template_id)) {
			$template_id_attachment = $template_id;
		}
		?>
		<module type="admin/components/file_append" option_group="mail_template_id_<?php echo $template_id_attachment; ?>" />
	</div>
  
  <div class="mw-flex-col-md-12"></div>
  
   <div class="mw-flex-col-md-5">
  <br />
  <label class="mw-ui-label">Subject</label>
   <input type="text" name="subject" value="<?php echo $template['subject']; ?>" class="mw-ui-field" style="width:100%;">
  </div>
  
  <div class="mw-flex-col-md-12">
  <br />
  <textarea id="editorAM" name="message" class="mw-ui-field" style="width:100%;"><?php echo $template['message']; ?></textarea>
  </div>
  
	<div class="mw-flex-col-md-12">
  	 <br />
  	  <input type="hidden" name="id" value="<?php echo $template['id']; ?>">
  	  
	  <input type="submit" name="submit" value="Save changes" class="mw-ui-btn"/>
	  &nbsp;&nbsp;
	  <input name="submit" value="Cancel" onClick="cancelTemplateEdit();" class="mw-ui-btn"/>
    </div>
  
  </div>
</form>
<br />
<br />
