<?php
    $comment_id = (int) $params['id'];
    $comment = get_comments('single=1&id=' . $comment_id);
    if (empty($comment)) {
        return;
    }
?>
<script>
$(document).ready(function () {
    mw.Editor({ element: '#mw-comment-edit-textarea' });
    mw.dialog.get('#mw-comment-edit-textarea').title('<?php _e('Edit comment') ?>')
	$('.js-save-comment-btn').click(function() {
 		$.ajax({
 			  type: "POST",
 			  url: mw.settings.api_url + 'save_comment_user',
 			  data: "comment_id=<?php echo $comment_id; ?>&comment_body="+ $('#mw-comment-edit-textarea').val(),
 			  success: function() {
 				mw.reload_module('comments');
 				mw.notification.success('Comment saved!');
                  mw.dialog.get('#mw-comment-edit-textarea').title('<?php _e('Edit comment') ?>')
 			  }
 		});
	});
});
</script>
<div class="comment_body" style="padding: 15px 0">
    <textarea id="mw-comment-edit-textarea" class="mw-ui-field" name="comment_body"><?php echo $comment['comment_body']; ?></textarea>
</div>
<span class="js-save-comment-btn mw-ui-btn mw-ui-btn-info pull-right"><?php _e('Save'); ?></span>
