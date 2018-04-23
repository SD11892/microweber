<?php
if (!defined("MODULE_DB_COMMENTS")){
	define('MODULE_DB_COMMENTS', 'comments');
}


require_once(__DIR__ . DS . 'vendor' . DS . 'autoload.php');



/**
 * mark_comments_as_old

 */
api_expose_admin('mark_comments_as_old');

function mark_comments_as_old($data) {


	if (isset($data['content_id'])){
		$table = MODULE_DB_COMMENTS;
		mw_var('FORCE_SAVE', $table);
		$data['is_new'] = 1;
		$get_comm       = get_comments($data);
		if (!empty($get_comm)){
			foreach ($get_comm as $get_com) {
				$upd           = array();
				$upd['is_new'] = 0;

				$upd['id']       = $get_com['id'];
				$upd['rel_type'] = 'content';
				$upd['rel_id']   = mw()->database_manager->escape_string($data['content_id']);
				mw()->database_manager->save($table, $upd);
			}
		}

		return $get_comm;

	}

}

/**
 * post_comment

 */
api_expose('post_comment');

function post_comment($data) {

	$adm = is_admin();

	$table = MODULE_DB_COMMENTS;
	mw_var('FORCE_SAVE', $table);

	if (isset($data['id'])){
		if ($adm==false){
			error('Error: Only admin can edit comments!');
		}
	}


	if (isset($data['action']) and isset($data['id'])){
		if ($adm==false){
			error('Error: Only admin can edit comments!');
		} else {
			$action = strtolower($data['action']);

			switch ($action) {
				case 'publish' :
					$data['is_moderated'] = 1;

					break;
				case 'unpublish' :
					$data['is_moderated'] = 0;

					break;
				case 'spam' :
					$data['is_moderated'] = 0;

					break;

				case 'delete' :
					$del = mw()->database_manager->delete_by_id($table, $id = intval($data['id']), $field_name = 'id');

					return array('success' => 'Deleted comment with id:' . $id);

					return $del;
					break;

				default :
					break;
			}

		}
	} else {
		if (isset($data['rel'])){
			$data['rel_type'] = $data['rel'];
		}
		if (!isset($data['rel_type'])){
			return array('error' => 'Error: invalid data');
		}
		if (!isset($data['rel_id'])){
			return array('error' => 'Error: invalid data');
		} else {
			if (trim($data['rel_id'])==''){
				return array('error' => 'Error: invalid data');
			}
		}

        $disable_captcha = get_option('disable_captcha', 'comments') == 'y';
        if(!$disable_captcha){
            if (isset($data['module_id'])) {
                $disable_captcha = get_option('disable_captcha', $data['module_id']) == 'y';
            }
        }
        if(!$disable_captcha){
            if (!isset($data['captcha'])){
                return array('error' => 'Please enter the captcha answer!');
            } else {
                if (isset($data['module_id'])){
                    $validate_captcha = mw()->captcha->validate($data['captcha'], $data['module_id']);
                } else {
                    $validate_captcha = mw()->captcha->validate($data['captcha']);
                }

                if (!$validate_captcha){
                    if ($adm==false){
                        return array('error' => 'Invalid captcha answer!', 'captcha_error' => true);
                    }
                }
            }
        }





	}
	if (!isset($data['id']) and isset($data['comment_body'])){

		if (!isset($data['comment_email']) and user_id()==0){
			return array('error' => 'You must type your email or be logged in order to comment.');
		}
		$ref = mw()->url_manager->current(1);
		if ($ref!=false and $ref!=''){
			$data['from_url'] = htmlentities(strip_tags(mw()->url_manager->current(1)));
		}
	}

	if ($adm==true and !isset($data['id']) and !isset($data['is_moderated'])){
		$data['is_moderated'] = '1';
	} else {
		$require_moderation = get_option('require_moderation', 'comments');
		if ($require_moderation!='y'){
			$data['is_moderated'] = '0';
		}
	}
	if (!isset($data['id'])){
		$data['is_new'] = '1';
	}

	$data['allow_html'] = true;
	$data               = mw()->format->clean_xss($data);

	$saved_data = mw()->database_manager->save($table, $data);


	if (!isset($data['id']) and isset($data['comment_body'])){


		$notif                = array();
		$notif['module']      = "comments";
		$notif['rel_type']    = $data['rel_type'];
		$notif['rel_id']      = $data['rel_id'];
		$notif['title']       = "You have new comment";
		$notif['description'] = "New comment is posted on " . mw()->url_manager->current(1);
		$notif['content']     = mw()->format->limit(strip_tags($data['comment_body']), 800);
		$notf_id              = mw()->notifications_manager->save($notif);
		$data['moderate']     = admin_url('view:modules/load_module:comments/mw_notif:' . $notf_id);
		$email_on_new_comment = get_option('email_on_new_comment', 'comments')=='y';
		$to                   = get_option('email_on_new_comment_value', 'comments');
		if ($email_on_new_comment==true){
			$subject = "You have new comment";
			$message = "Hi, <br/> You have new comment posted on " . mw()->url_manager->current(1) . ' <br /> ';
			$message .= "IP:" . MW_USER_IP . ' <br /> ';
			$message .= mw()->format->array_to_ul($data);
			$sender = new \Microweber\Utils\MailSender();
			$sender->send($to, $subject, $message);
		}
	}

	return $saved_data;
}

function get_comments($params) {
	$params2 = array();
	if (is_string($params)){
		$params = parse_str($params, $params2);
		$params = $params2;
	}
	if (isset($params['content_id'])){
		$params['rel_type'] = 'content';
		$params['rel_id']   = mw()->database_manager->escape_string($params['content_id']);

	}

	$table           = MODULE_DB_COMMENTS;
	$params['table'] = $table;

	$comments = db_get($params);

	$date_format = get_option('date_format', 'website');
	if ($date_format==false){
		$date_format = "Y-m-d H:i:s";
	}
	$aj = mw()->url_manager->is_ajax();

	if (is_array($comments)){
		$i = 0;
		foreach ($comments as $item) {
			if (isset($params['count'])){
				if (isset($item['qty'])){
					return $item['qty'];
				}
			}
			if (isset($item['created_by']) and intval($item['created_by']) > 0 and ($item['comment_name']==false or $item['comment_name']=='')){
				$comments[ $i ]['comment_name'] = user_name($item['created_by']);
			}
			if (isset($item['created_at']) and trim($item['created_at'])!=''){
				$comments[ $i ]['created_at'] = date($date_format, strtotime($item['created_at']));
			}
			if (isset($item['updated_at']) and trim($item['updated_at'])!=''){
				$comments[ $i ]['updated_at'] = date($date_format, strtotime($item['updated_at']));
			}
			if (isset($item['comment_body']) and ($item['comment_body']!='')){
				$surl                           = site_url();
				$item['comment_body']           = str_replace('{SITE_URL}', $surl, $item['comment_body']);
				$comments[ $i ]['comment_body'] = mw()->format->autolink($item['comment_body']);
			}

			if (isset($params['single'])){

				return $comments ;
			}

			$i ++;
		}
	}

	return $comments;
}


event_bind(
	'module.content.manager.item', function ($item) {

		if (isset($item['id'])){

			$new = get_comments('count=1&is_moderated=0&content_id=' . $item['id']);
			if ($new > 0){
				$have_new = 1;
			} else {
				$have_new = 0;
				$new      = get_comments('count=1&content_id=' . $item['id']);
			}
			$comments_link = admin_url('view:comments') . '/#content_id=' . $item['id'];

			if ($have_new){

			}
			$link = "<a class='comments-bubble' href='{$comments_link}'  title='{$new}'>";
			$link .= "<span class='mai-comment'></span><span class='comment-number'>{$new}</span>";
			$link .= "</a>";
			print $link;
		}
	}
);


event_bind(
	'module.content.edit.main', function ($item) {

		if (isset($item['id'])){
			$new = get_comments('count=1&rel_type=content&rel_id=' . $item['id']);
			if ($new > 0){
				$btn          = array();
				$btn['title'] = 'Comments';
				$btn['class'] = 'mw-icon-comment';
				 $btn['html']  = '<module type="comments/manage" no_post_head="true" content_id="' . $item['id'] . '"  />';
				 //$btn['html']  = '<module type="comments/comments_for_post" no_post_head="true" content_id="' . $item['id'] . '"  />';
			//	$btn['html']  = '<module type="comments/manage" no_post_head="true" content_id=' . $item['id'] . '  />';
				mw()->modules->ui('content.edit.tabs', $btn);
			}
		}
	}
);


event_bind(
	'mw.admin.dashboard.links', function () {

		$admin_dashboard_btn         = array();
		$admin_dashboard_btn['view'] = 'comments';

		$admin_dashboard_btn['icon_class'] = 'mai-comment';
		$notif_html                        = '';
		$notif_count                       = mw()->notifications_manager->get('module=comments&is_read=0&count=1');

		if ($notif_count > 0){
			$notif_html = '<sup class="mw-notification-count">' . $notif_count . '</sup>';
		}
		$admin_dashboard_btn['text'] = _e("Comments", true) . $notif_html;

		mw()->ui->module('admin.dashboard.menu', $admin_dashboard_btn);
	}
);
