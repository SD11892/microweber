<script  type="text/javascript">
    mw.require('<?php print $config['url_to_module']; ?>pictures.js', true);
</script>
<?php




if(!isset($for_id)){
$for_id = 0;
}
if(isset($params['for'])){
	 $for = $params['for'];
} else {
 $for = 'modules';
}

if(!isset($for)){
$for = 'content';

}

if(isset($params['content_id'])){
	 $for = $params['for'] = 'content';
	  $for_id = $for_module_id = $params['content_id'];
} elseif(isset($params['content-id'])){
	$for_id = $for_module_id = $params['content-id'];
	 $for = 'content';
}

$for =  mw()->database_manager->assoc_table_name($for);

if(!isset($params['for-id'])){
	$params['for-id'] = $params['id'];
}

if(isset($params['for-id'])){
	$for_id = $params['for-id'];
}if(isset($params['rel-id'])){
	$for_id = $params['rel-id'];
}if(isset($params['rel_id'])){
	$for_id = $params['rel_id'];
}



 
$rand = uniqid();  
 
if(trim($for_id)  != '' and trim($for_id)  != '0'){
    $media = get_pictures("rel_id={$for_id}&rel_type={$for}");
} else {
	 $sid = mw()->user_manager->session_id();
	 if($sid == ''){
 		$sid = mw()->user_manager->session_id();
	 }
 
	$media = get_pictures("rel_id=0&rel_type={$for}&session_id={$sid}");
}


?>

<script  type="text/javascript">
    after_upld = window.after_upld || function (a, e, f, id, module_id){
    	if(e != 'done' ){
    			 var data = {};
    			 data['for'] = f;
    			 data.src = a;
    			 data.media_type = 'picture';
				 
				 if(id == undefined || id == ''){
					 data.for_id = 0; 
				 } else {
				 data.for_id = (id);

				 }
				 
				
				 
    			 mw.module_pictures.after_upload(data);
    	}
    	if(e == 'done' ){
    		setTimeout(function(){
    			mw.tools.modal.remove('mw_rte_image');
    			if(typeof load_iframe_editor === 'function'){
    				load_iframe_editor();
    			}
    			mw.reload_module('#'+module_id);
				mw.reload_module_parent('pictures');
    			if(self !== top && typeof parent.mw === 'object'){
    				 parent.mw.reload_module('pictures');
					  mw.reload_module_parent("pictures/admin");
    				 if(self !== top && typeof parent.mw === 'object'){
    				   parent.mw.reload_module('posts');
    				   parent.mw.reload_module('shop/products');
    				   parent.mw.reload_module('content', function(){
    						mw.reload_module('#'+module_id);
    						parent.mw.reload_module('pictures');
    				   });
    				}
    			}
    		},300);
    	}
    }
</script>

<script  type="text/javascript">
$(document).ready(function(){
   mw.module_pictures.init('#admin-thumbs-holder-sort-<?php print $rand; ?>');
});
</script>
<script  type="text/javascript">
mw_admin_puctires_upload_browse_existing = function(){
	
 
   mw_admin_puctires_upload_browse_existing_modal = window.top.mw.modalFrame({
        url: '<?php print site_url() ?>module/?type=files/admin&live_edit=true&remeber_path=true&ui=basic&start_path=media_host_base&from_admin=true&file_types=images&id=mw_admin_puctires_upload_browse_existing_modal<?php print $params['id'] ?>&from_url=<?php print site_url() ?>',
		title: "Browse pictures",
		id: 'mw_admin_puctires_upload_browse_existing_modal<?php print $params['id'] ?>',
       	onload:function(){

            this.iframe.contentWindow.mw.on.hashParam('select-file', function(){
               after_upld(this, 'save', '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');

			   after_upld(this, 'done', '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');
			   mw.notification.success('<?php _e('The image is added to the gallery') ?>');

            })
        },
        height: 400
    })
  
}
</script>
<?php
   if(!isset($data["thumbnail"])){
	   $data['thumbnail'] = '';
   }
?>




<input name="thumbnail"  type="hidden" value="<?php print ($data['thumbnail'])?>" />


<label class="mw-ui-label"><?php _e("Add Images"); ?> <?php _e('or'); ?>
  <a href="javascript:mw_admin_puctires_upload_browse_existing()" class="mw-ui-link mw-ui-btn-small"> <?php _e('browse uploaded'); ?></a> <small>(<?php _e("The first image will be cover photo"); ?>)</small> </label>

<div class="select_actions_holder">
  <div class="select_actions">
    <div class="mw-ui-btn-nav select_actions">
      <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-important mw-ui-btn-icon" onclick="deleteSelected()">
          <span class="mw-icon-bin tip" data-tip="<?php _e('Delete') ?> <?php _e('selected') ?>" data-tipposition="top-right"></span>
      </a>
      <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-icon" onclick="downloadSelected('none')">
        <span class="mw-icon-download tip" data-tip="<?php _e('Download') ?> <?php _e('selected') ?>" data-tipposition="top-right"></span>
      </a>
    </div>
  </div>
  <span class="btnnv-label">Select</span>
  <div class="mw-ui-btn-nav">
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small" onclick="selectItems('all')">All</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small" onclick="selectItems('none')">None</a>
  </div>
</div>


<div class="admin-thumbs-holder left" id="admin-thumbs-holder-sort-<?php print $rand; ?>">

<div class="relative post-thumb-uploader" id="backend_image_uploader"><small id="backend_image_uploader_label"><?php _e("Upload"); ?></small></div>

  <?php if(is_array( $media)): ?>
  <?php $default_title = _e("Image title", true); ?>
  <?php foreach( $media as $item): ?>
  <div class="admin-thumb-item admin-thumb-item-<?php print $item['id'] ?>" id="admin-thumb-item-<?php print $item['id'] ?>">
    <?php $tn = thumbnail($item['filename'], 200, 200); ?>
    <span class="mw-post-media-img" style="background-image: url('<?php print $tn; ?>');"></span>
    <label class="mw-ui-check">
        <input type="checkbox" onchange="doselect()" data-url="<?php print $item['filename']; ?>" value="<?php print $item['id'] ?>"><span></span>
    </label>
    <div class="mw-post-media-img-edit">
      <input
            placeholder="<?php _e("Image Description"); ?>"
            autocomplete="off"
            value="<?php if ($item['title'] !== ''){print $item['title'];} else{ print $default_title; }  ?>"
            onkeyup="mw.on.stopWriting(this, function(){mw.module_pictures.save_title('<?php print $item['id'] ?>', this.value);});"
            onfocus="$(this.parentNode).addClass('active');"
            onblur="$(this.parentNode).removeClass('active');"
            name="media-description-<?php print $tn; ?>"
      />

      

	
	<?php
$tags_str = picture_tags($item['id']);
if(!$tags_str){
	 $tags_str = array();
 }
 
	?>
	<input
            placeholder="<?php _e("Image Tags"); ?>"
            autocomplete="off"
            class="image-tags"
            value="<?php print implode(',',$tags_str); ?>"
            onkeyup="mw.on.stopWriting(this, function(){mw.module_pictures.save_tags('<?php print $item['id'] ?>', this.value);});"
            onfocus="$(this.parentNode).addClass('active');"
            onblur="$(this).hide().parent().removeClass('active');"
            name="media-tags-<?php print $tn; ?>"
      />
	
	
	
	  
	  
	  
	  
	  
      <a title="<?php _e("Delete"); ?>" class="mw-icon-close" href="javascript:;" onclick="mw.module_pictures.del('<?php print $item['id'] ?>');"></a> </div>
  </div>
  <?php endforeach; ?>
  <?php endif;?>
  <script>mw.require("files.js", true);</script>
  <script>
    deleteSelected = function(){
      mw.module_pictures.del(doselect());
    }
    downloadSelected = function(){
       mw.$(".admin-thumb-item .mw-ui-check input:checked").each(function(){
          var a = $("<a>")
              .attr("href", $(this).dataset('url'))
              .attr("download", $(this).dataset('url'))
              .appendTo("body");

          a[0].click();
          a.remove();
      })

    }
    doselect = function(){
      var final = []
      mw.$(".admin-thumb-item .mw-ui-check input:checked").each(function(){
        final.push(this.value);
      })
      $(".select_actions")[final.length === 0 ? 'removeClass' : 'addClass']('active');
      $(".select_actions_holder").stop()[final.length === 0 ? 'hide' : 'show']();
      return final;
    }
      editImageTags = function(event){
          var parent = null;
          mw.tools.foreachParents(event.target, function(loop){

              if(mw.tools.hasClass(this, 'admin-thumb-item')){
                parent = this;
                mw.tools.stopLoop(loop);
              }

          });
          if(parent !== null){
            $(".image-tags", parent).show() 
          }

      }
      var uploader = mw.files.uploader({
             filetypes:"images",
             name:'basic-images-uploader'
      });

      selectItems = function(val){

          if(val == 'all'){
            mw.$(".admin-thumb-item .mw-ui-check input").each(function(){
              this.checked = true;
            })
          }
          else if(val == 'none'){
            mw.$(".admin-thumb-item .mw-ui-check input").each(function(){
              this.checked = false;
            })
          }
          doselect()

      }


      $(document).ready(function(){

         mw.$("#backend_image_uploader").append(uploader);
         $(uploader).bind("FilesAdded", function(a,b){
            var i=0, l=b.length;
             for( ; i<l; i++){
               if(mw.$(".admin-thumbs-holder .admin-thumb-item").length > 0){
                 mw.$(".admin-thumbs-holder .admin-thumb-item:last").after('<div class="admin-thumb-item admin-thumb-item-loading" id="im-'+b[i].id+'"><span class="mw-post-media-img"><i class="uimprogress"></i></span><div class="mw-post-media-img-edit mw-post-media-img-edit-temp">'+b[i].name+'</div></div>');
               }
               else{
                 mw.$(".admin-thumbs-holder").append('<div class="admin-thumb-item admin-thumb-item-loading" id="im-'+b[i].id+'"><span class="mw-post-media-img"><i class="uimprogress"></i></span><div class="mw-post-media-img-edit mw-post-media-img-edit-temp">'+b[i].name+'</div></div>');
               }
             }
         });
         $(uploader).bind("progress", function(a,b){
            mw.$("#im-"+b.id+" .uimprogress").width(b.percent + "%").html(b.percent + "%");
         });
         $(uploader).bind("FileUploaded done" ,function(e, a){
    	    setTimeout(function(){
    	        after_upld(a.src, e.type, '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');
        	},300);
         });
         $(".image-tag-view").remove();
         $(".image-tags").each(function(){
             $(".mw-post-media-img", mw.tools.firstParentWithClass(this, 'admin-thumb-item'))
                .append('<span class="image-tag-view tip" onclick="editImageTags(event)" data-tip="Tags: '+this.value+'" ><span class="mw-icon-app-pricetag"></span></span>');
                $(this).on('change', function(){
                    $(".image-tag-view", mw.tools.firstParentWithClass(this, 'admin-thumb-item')).attr('data-tip', 'Tags: '+this.value);
                });

         });

         doselect()
      });
  </script>
</div>
