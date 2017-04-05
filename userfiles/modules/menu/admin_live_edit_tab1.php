
<script  type="text/javascript">
  mw.require('forms.js', true);
  mw.require('url.js', true);
</script>
<?php if(!isset($rand )) {$rand  = uniqid();} ?>
<script type="text/javascript">



<?php include mw_includes_path() . 'api/treerenderer.php'; ?>


  mw.menu_add_new = function(){
      var obj = {};
	 obj.title = $('#new_menu_name').val();

      $.post("<?php print api_link('content/menu_create') ?>",  obj, function(data){
	    window.location.href = window.location.href;
      });
 }

  mw.menu_save = function($selector){
      var obj = mw.form.serialize($selector);
      $.post("<?php print api_link('content/menu_create') ?>",  obj, function(data){
	    window.location.href = window.location.href;

		 menuSelectorInit();

 	   /* mw.$('#<?php print $params['id'] ?>').attr('new-menu-id',data);
		mw.reload_module('#<?php print $params['id'] ?>');
		menuSelectorInit();*/
      });
 }




 requestLink = function(){
   // mw.$(".menu_item_edit").remove();

    mw.$("#menu-selector").toggle();
    mw.$("#custom_link_controller").hide();
 }

 requestCustomLink = function(){
   // mw.$(".menu_item_edit").remove();

    mw.$("#custom_link_controller").toggle();
    mw.$("#menu-selector").hide();

 }

 add_new_menu = function(){
   mw.$("#create-menu-holder").toggle();
   var btn = mw.$('#create-menu-btn');
   btn.toggleClass('active');
   if(btn.hasClass('active')){
     mw.$('#new_menu_name').focus()
   }

 }

mw.menu_delete = function($id){
var data = {}
data.id = $id
    if (confirm('<?php _e('Are you sure you want to delete this menu?'); ?>') === true){
       $.post("<?php print api_link('content/menu_delete') ?>",  data, function(resp){
          window.location.href = window.location.href;
       });
    }
}



mw.menu_edit_items = function($menu_name, $selector){

   mw.$($selector).attr('menu-name',$menu_name);

 
   mw.load_module('menu/edit_items',$selector);
    menuSelectorInit();


 }

 menuSelectorInit = function(selector){

 
     var selector = selector ||  "#menu-selector";
     mw.treeRenderer.appendUI(selector);

	 var items =  mw.$(selector + ' input[type="radio"]');

	 if(items == null){
		return;
	 }
	  if(items.commuter == undefined){
		  return;

	  }
    items.commuter(function(){
		
		
			var data = {};
		
		
	var save_selector = '#custom_link_inline_controller_edit_0';
        var content_id =  mw.$("[name='content_id']:checked");
        var categories_id =  mw.$("[name='category_id']:checked");
		
		
		if(typeof(mw.menu_curenlty_editing_item_id) != 'undefined' && mw.menu_curenlty_editing_item_id != false){
				//	data.id = mw.menu_curenlty_editing_item_id;
				var save_selector = '#custom_link_inline_controller_edit_'+mw.menu_curenlty_editing_item_id;
					var title_for_item =  mw.$("[name='title']", save_selector);
				   var tree_content_id =  mw.$("[name='tree_content_id']:checked", save_selector);
        		var tree_cat_id =  mw.$("[name='tree_cat_id']:checked", save_selector);
				if(title_for_item){
				var title_for_item_val = 	 title_for_item.val()
				if(title_for_item_val){
					data.title = title_for_item_val;
				}
				 
				}
		
			if(tree_content_id){
				 var content_id =  tree_content_id;
        		var categories_id =  tree_cat_id;
			}
			data.id = mw.menu_curenlty_editing_item_id;
			data.url = null;
			 

		} else {
			
			var get_parent_id = $('#add-custom-link-parent-id').val();
			if(get_parent_id){
                data.parent_id = get_parent_id;
            }

		}
		
		
		
		d(save_selector);
				
	
		data.content_id = content_id.val();
		data.categories_id = categories_id.val();
		
		

        var el = this;

        content_id.val('');
        categories_id.val('');

//        if(mw.tools.hasParentsWithClass(el, 'category_element')){
//           categories_id.val(el.value);
//        }
//        else if(mw.tools.hasParentsWithClass(el, 'pages_tree_item')){
//            content_id.val(el.value);
//        }
//
//
		
		
		
		$.post( "<?php print api_link('content/menu_item_save'); ?>",data, function( msg ) {
		  		 // mw.reload_module('menu');
        			parent.mw.reload_module('menu');

		  mw.reload_module('menu/edit_items');


		});
 
  
      //  mw.menu_save_new_item(save_selector);
		//mw.reload_module('menu/edit_items');

        mw.$(selector).hide();
     });





 }

 view_all_subs = function(){
    var master = mwd.querySelector('.mw-modules-admin');
    $(master.querySelectorAll('.menu_nested_controll_arrow')).each(function(){
      $(this).addClass('toggler-active');
      $(this.parentNode.parentNode.querySelector('ul')).addClass('toggle-active').show();
    });

    $(".view_all_subs").addClass('active');
    $(".hide_all_subs").removeClass('active');
 }

 hide_all_subs = function(){
    var master = mwd.querySelector('.mw-modules-admin');
    $(master.querySelectorAll('.menu_nested_controll_arrow')).each(function(){
      $(this).removeClass('toggler-active');
      $(this.parentNode.parentNode.querySelector('ul')).removeClass('toggle-active').hide();
    });
    $(".view_all_subs").removeClass('active');
    $(".hide_all_subs").addClass('active');
 }

 cancel_editing_menu = function(id){
   $("#menu-item-"+id).removeClass('active');
   $("#edit-menu_item_edit_wrap-"+id).remove();
 }


$(document).ready(function(){

   menuSelectorInit();


});



 </script>
<script  type="text/javascript">
    if(typeof mw.menu_save_new_item !== 'function'){
        mw.menu_save_new_item = function(selector,no_reload){

 
        	mw.form.post(selector, '<?php print api_link('content/menu_item_save'); ?>', function(){

				mw.$('#<?php print $params['id'] ?>').removeAttr('new-menu-id');
				if(no_reload === undefined){
        		mw.reload_module('menu/edit_items');
				}
				
				 

        		if(self!==parent && typeof parent.mw === 'object'){
        			parent.mw.reload_module('menu');
        		}
				 menuSelectorInit();
        	});
        }
    }
</script>
<?php $menus = get_menus(); ?>
<?php
 if(!isset($menu_name )) {
     $menu_name = get_option('menu_name', $params['id']);

     if ($menu_name == false and isset($params['menu_name'])) {
         $menu_name = $params['menu_name'];
     } elseif ($menu_name == false and isset($params['name'])) {

         $menu_name = $params['name'];
     } else {


     }
 }




	$active_menu = $menu_name;
  $menu_id = false;




  if($menu_id == false and $menu_name != false){
  $menu_id = get_menus('one=1&title='.$menu_name);
 
	  if($menu_id == false and isset($params['title'])){
	  mw()->menu_manager->menu_create('id=0&title=' . $params['title']);
	    $menu_id = get_menus('one=1&title='.$menu_name);
	  }

  }
 
 if(isset($menu_id['title'])){
	 $active_menu =   $menu_id['title'];
 }
 $menu_id = get_menus('one=1&title='.$menu_name);
 if($menu_id == false){
	 $active_menu = $menu_name = 'header_menu';
	  $menu_id = get_menus('one=1&title='.$menu_name);

 }
   
 ?>

<?php  print $active_menu ?>
<?php if(is_array($menus) == true): ?>
<?php if(is_array($menus )): ?>

<div class="control-group form-group">
  <label class="mw-ui-label">
    <?php _e("Select the Menu you want to edit"); ?>

   </label>
  <div id="quick_new_menu_holder">

  <a href="javascript:add_new_menu();" class="mw-ui-btn pull-right" id="create-menu-btn">
    <span class="mw-icon-plus"></span><?php _e("Create new menu"); ?>
  </a>


  <div class="mw-ui-box mw-ui-box-content pull-right" id="create-menu-holder" style="display: none;margin: 5px 0 12px;">

  <input name="new_menu_name" class="mw-ui-field" id="new_menu_name" placeholder="<?php _e("Menu name"); ?>" type="text" style="margin-right: 12px;"  />

  <button type="button" class="mw-ui-btn mw-ui-btn-invert" onclick="mw.menu_add_new()"><?php _e("Save"); ?></button>

  </div>



  </div>
    <select  id="menu_selector_<?php  print $params['id'] ?>" name="menu_name" class="mw-ui-field mw_option_field"   type="radio"  onchange="mw.menu_edit_items(this.value, '#items_list_<?php  print $rand ?>');" onblur="mw.menu_edit_items(this.value, '#items_list_<?php  print $rand ?>');" >
      <?php foreach($menus  as $item): ?>
      <?php if($active_menu == false){
		$active_menu =   $item['title'];
	  }?>
      <option <?php  if($menu_name == $item['title'] or $menu_id == $item['id']): ?> <?php  $active_menu = $item['title'] ?> selected="selected" <?php endif; ?> value="<?php print $item['title'] ?>"><?php print ucwords(str_replace('_', ' ', $item['title'])) ?></option>
      <?php endforeach ; ?>
    </select>


  <hr>
  <label class="mw-ui-label">
    <?php _e("Select from"); ?>
    :</label>





  <a href="javascript:requestLink();" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert"><span class="mw-icon-plus"></span><span>
  <?php _e("Add Page to Menu"); ?>
  </span></a> <a href="javascript:requestCustomLink();" class="mw-ui-btn mw-ui-btn-medium"><span class="mw-icon-plus"></span><span>
  <?php _e("Add Custom Link"); ?>
  </span></a>






  <hr>
</div>
<?php endif; ?>
<?php else : ?>
<?php _e("You have no exising menus. Please create one."); ?>
<?php endif; ?>
<?php
if(isset($menu_id) and is_array($menu_id) and isset($menu_id['id'])){
  $menu_id = $menu_id['id'];
}

 ?>
<div id="menu-selector" class="mw-ui mw-ui-category-selector mw-tree">
<div id="custom_link_inline_controller_edit_0">
  <input type="hidden" name="parent_id" id="add-custom-link-parent-id" value="<?php  print   $menu_id ?>" />

  <microweber module="categories/selector"  for="content" rel_id="<?php print 0 ?>" input-type-categories="radio" input-name-categories="category_id" input-name="content_id"  />
  
  </div>
</div>
<div id="custom_link_controller" class="mw-ui-gbox">

  <div class="mw-ui-row-nodrop">
      <div class="mw-ui-col">
      <div class="mw-ui-col-container">
        <input type="text" class="mw-ui-field w100" placeholder="<?php _e("Title"); ?>" name="title" />
      </div>
      </div>
      <div class="mw-ui-col">
      <div class="mw-ui-col-container">
         <input type="text" class="mw-ui-field w100" placeholder="<?php _e("URL"); ?>" name="url"  />
      </div>
      </div>
  </div>

  <br>
 
  <input type="hidden" name="parent_id" id="add-custom-link-parent-id" class="add-custom-link-parent-id" value="<?php  print   $menu_id ?>" />
  <button class="mw-ui-btn mw-ui-btn-info pull-right" onclick="mw.menu_save_new_item('#custom_link_controller');">
  <?php _e("Add to menu"); ?>
  </button>
</div>

<div class="<?php print $config['module_class']; ?> menu_items order-has-link"   id="items_list_<?php  print $rand ?>">
  <?php if($active_menu != false): ?>
  <h2><?php print $menu_name; ?> <?php _e("Links"); ?></h2>

  <label class="mw-ui-label"><small>
      <?php _e("Here you can edit your menu links. You can also drag and drop to reorder them."); ?>
      </small></label>
  <label class="mw-ui-label">
    <?php _e("Edit existing links/buttons"); ?>
  </label>

  <module data-type="menu/edit_items"  id="items_list_<?php  print $rand ?>" menu-name="<?php  print $active_menu ?>"  menu-id="<?php  print $menu_id ?>" />
  <?php endif; ?>
</div>

