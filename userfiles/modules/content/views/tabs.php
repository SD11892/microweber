<?php if(!isset($data)){
$data = $params;
}

$custom_tabs = mw()->modules->ui('content.edit.tabs');

?>

<div class="mw-admin-edit-content-holder-default">
  <div id="content-edit-settings-tabs-holder">
    <div id="content-edit-settings-tabs">
      <div id="quick-add-post-options-holder">
        <div id="quick-add-post-options">



        <div class="mw-ui-btn-nav more-inactive">
            <span class="mw-ui-abtn  tip" data-tip="<?php _e("Thumbnail (Main gallery)"); ?>">
                <span class="mai-image"></span>
                <?php _e("Images") ?>
            </span>
            <span class="mw-ui-abtn  tip" data-tip="<?php _e("Comments"); ?>">
                <span class="mai-comment2"></span>
                <?php _e("Comments") ?>
            </span>
            <span class="mw-ui-abtn postbtnmore">
                <?php _e("More") ?>
            </span>


            <?php if($data['content_type'] == 'page'): ?>
            <span class="mw-ui-abtn  tip" data-tip="<?php _e('Add to navigation'); ?>" > <span class="mw-icon-menuadd"></span> <span>
            <?php _e('Add to navigation'); ?>
            </span> </span>
            <?php endif; ?>
            <?php  if($data['content_type'] == 'product'): ?>
            <span class="mw-ui-abtn  tip" data-tip="<?php _e("Price & Fields"); ?>">
                <span class="mw-icon-pricefields"></span> <span>
            <?php _e("Price & Fields"); ?>
            </span> </span>
            <span class="mw-ui-abtn  tip" data-tip="<?php _e("Shipping & Options"); ?>"> <span class="mw-icon-truck"></span> <span>
            <?php _e("Shipping & Options"); ?>
            </span> </span>
            <?php else: ?>
            <span class="mw-ui-abtn  tip" data-tip="<?php _e("Custom Fields"); ?>"> <span class="mw-icon-pricefields"></span> <span>
            <?php _e("Custom Fields"); ?>
            </span> </span>
            <?php endif; ?>

            <?php event_trigger('mw_admin_edit_page_tabs_nav', $data); ?>
 <span class="mw-ui-abtn mw-ui-abtn tip" data-tip="<?php _e("Advanced"); ?>"> <span class="mw-icon-gear"></span> <span>
            <?php _e("Advanced"); ?>
            </span> </span>
            <?php if($data['content_type'] == 'old_page'):  ?>
            <span id="quick-add-post-options-item-template-btn" class="mw-ui-abtn tip" data-tip="<?php _e("Template"); ?>"> <span class="mw-icon-template"></span> <span>
            <?php _e("Template"); ?>
            </span> </span>
            <?php endif; ?>

          <?php if(!empty($custom_tabs)): ?>
          <?php foreach($custom_tabs as $item): ?>
          <?php $title = ( isset( $item['title']))? ($item['title']) : false ; ?>
          <?php $class = ( isset( $item['class']))? ($item['class']) : false ; ?>
          <?php $html = ( isset( $item['html']))? ($item['html']) : false ; ?>
  <span class="mw-ui-abtn  tip" data-tip="<?php print $title; ?>"> <span class="<?php print $class; ?>"></span> <span> <?php print $title; ?> </span> </span>
          <?php endforeach; ?>
          <?php endif; ?>
          </div>
        </div>
      </div>
      <div id="quick-add-post-options-items-holder" class="tip-box">
        <div id="quick-add-post-options-items-holder-container">

          <div  id="post-gallery-manager" class="quick-add-post-options-item">
              <div id="edit-post-gallery-main" type="pictures/admin" for="content" for-id="<?php print $data['id']; ?>"></div>
            </div>
          <div class="quick-add-post-options-item">
              <?php _e("Comments") ?>
          </div>
          <?php if($data['content_type'] == 'page'): ?>
          <div class="quick-add-post-options-item">
            <?php event_trigger('mw_edit_page_admin_menus', $data); ?>



              <?php if(isset($data['add_to_menu'])): ?>

                  <module type="menu" view="edit_page_menus" content_id="<?php print $data['id']; ?>" add_to_menu="<?php print $data['add_to_menu']; ?>"  />


                  <?php else: ?>
                  <module type="menu" view="edit_page_menus" content_id="<?php print $data['id']; ?>"  />


              <?php endif; ?>

            <?php event_trigger('mw_admin_edit_page_after_menus', $data); ?>
            <?php event_trigger('mw_admin_edit_page_tab_2', $data); ?>
          </div>
          <?php endif; ?>
          <div class="quick-add-post-options-item">
            <module
                    type="custom_fields/admin"
                    <?php if( trim($data['content_type']) == 'product' ): ?> default-fields="price" <?php endif; ?>
                    content-id="<?php print $data['id'] ?>"
                    suggest-from-related="true"
                    list-preview="true"
                    id="fields_for_post_<?php print $data['id']; ?>" 	 />
            <?php event_trigger('mw_admin_edit_page_tab_3', $data); ?>
          </div>
          <?php  if(trim($data['content_type']) == 'product'): ?>
          <div class="quick-add-post-options-item">
            <?php event_trigger('mw_edit_product_admin', $data); ?>
          </div>
          <?php endif; ?>
          <div class="quick-add-post-options-item" id="quick-add-post-options-item-advanced">
            <?php event_trigger('mw_admin_edit_page_tab_4', $data); ?>
            <module type="content/views/advanced_settings" content-id="<?php print $data['id']; ?>"  content-type="<?php print $data['content_type']; ?>" subtype="<?php print $data['subtype']; ?>"    />
          </div>
          <?php if($data['content_type'] == 'old_page'):  ?>

          <?php
		  $no_content_type_setup_from_layout = false;
		  if($data['content_type'] != 'page' and $data['content_type'] != 'post' and $data['content_type'] != 'product'){
			$no_content_type_setup_from_layout = true;
		  } else if(isset($data['subtype']) and $data['subtype'] != 'static'  and $data['subtype'] != 'dynamic' and $data['subtype'] != 'post' and $data['subtype'] != 'product'){
			$no_content_type_setup_from_layout = true;
		  }

		  if($no_content_type_setup_from_layout  != false){
			$no_content_type_setup_from_layout  = ' no_content_type_setup="true" '  ;
		  }

		  ?>


          <div class="quick-add-post-options-item quick-add-content-template" id="quick-add-post-options-item-template">



            <div type="content/views/layout_selector" id="mw-quick-add-choose-layout" template-selector-position="bottom" content-id="<?php print $data['id']; ?>" inherit_from="<?php print $data['parent']; ?>" <?php print $no_content_type_setup_from_layout ?> ></div>






          </div>
          <?php endif; ?>
          <?php if(!empty($custom_tabs)): ?>
          <?php foreach($custom_tabs as $item): ?>
          <?php $title = ( isset( $item['title']))? ($item['title']) : false ; ?>
          <?php $class = ( isset( $item['class']))? ($item['class']) : false ; ?>
          <?php $html = ( isset( $item['html']))? ($item['html']) : false ; ?>
          <div class="quick-add-post-options-item"><?php print $html; ?></div>
          <?php endforeach; ?>
          <?php endif; ?>
          <?php event_trigger('mw_admin_edit_page_tabs_end', $data); ?>
        </div>
      </div>
    </div>
  </div>
</div>
