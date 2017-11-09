<?php

/*

type: layout

name: Default

description: Default - 3 Columns

*/
?>
<?php


$tn = $tn_size;
if(!isset($tn[0]) or ($tn[0]) == 150){
     $tn[0] = 350;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}


?>
<script>mw.moduleCSS("<?php print $config['url_to_module']; ?>css/style.css"); </script>
<?php if (!empty($data)): ?>
<?php


    $count = 0;
    $len =  count($data);

    $helpclass = '';

    if($len%3 !=0 ){
        if((($len-1)%3)==0 or $len==1){
             $helpclass = 'last-row-single';
        }
        elseif((($len-2)%3)== 0  or $len==2){
            $helpclass = 'last-row-twoitems';
        }
    } ?>

<div class="clearfix module-products-template-columns-3 <?php print $helpclass; ?>">
  <?php
    foreach ($data as $item):

    $count++;
    ?>
  <?php if($count == 1 or ($count-1) % 3 == 0) { ?>
  <div class="mw-ui-row">
    <?php } ?>
    <div class="mw-ui-col">
      <div class="mw-ui-col-container" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
        <?php if($show_fields == false or in_array('thumbnail', $show_fields)): ?>
        <a class="img-polaroid img-rounded" href="<?php print $item['link'] ?>"> <span class="valign"> <span class="valign-cell"> <img <?php if($item['image']==false){ ?>class="pixum"<?php } ?> src="<?php print thumbnail($item['image'], 600, 600, true); ?>" alt="<?php print $item['title'] ?>" title="<?php print $item['title'] ?>"  /> </span> </span> </a>
        <?php endif; ?>
        <?php if($show_fields == false or in_array('title', $show_fields)): ?>
        <h3 itemprop="name"><a  class="lead" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h3>
        <?php endif; ?>
        <?php if($show_fields != false and in_array('created_at', $show_fields)): ?>
        <span class="date" itemprop="dateCreated"><?php print $item['created_at'] ?></span>
        <?php endif; ?>
        <?php if($show_fields == false or ($show_fields != false and  is_array($show_fields) and  in_array('description', $show_fields))): ?>
        <p class="description" itemprop="description"><?php print $item['description']; ?> </p>
        <?php endif; ?>
        <?php if($show_fields != false and ($show_fields != false and  in_array('read_more', $show_fields))): ?>
        <a href="<?php print $item['link'] ?>" itemprop="url" class="mw-more">
        <?php $read_more_text ? print $read_more_text : print _e('Read More', true); ?>
        </a>
        <?php endif; ?>
        <div class="product-price-holder clearfix">
          <?php if($show_fields == false or in_array('price', $show_fields)): ?>
          <?php if(isset($item['prices']) and is_array($item['prices'])){  ?>
          <?php 
                $vals2 = array_values($item['prices']);
                $val1 = array_shift($vals2); ?>
          <span class="price"><?php print currency_format($val1); ?></span>
          <?php } else{ ?>
          <?php } ?>
          <?php endif; ?>
          <?php if($show_fields == false or in_array('add_to_cart', $show_fields)): ?>
          <?php
                $add_cart_text = get_option('data-add-to-cart-text', $params['id']);
			  if( $add_cart_text == false or $add_cart_text == "Add to cart"){ 
			    $add_cart_text =  _e("Add to cart", true);
			   }

         ?>
          <?php if(is_array( $item['prices'])): ?>
          <button class="btn btn-default" type="button" onclick="mw.cart.add('.mw-add-to-cart-<?php print $item['id'].$count ?>');"><i class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>&nbsp;<?php print $add_cart_text ?></button>
          <?php endif; ?>
          <?php endif; ?>
        </div>
        <?php if(is_array( $item['prices']) ): ?>
        <?php foreach( $item['prices']  as $k  => $v): ?>
        <div class="clear products-list-proceholder mw-add-to-cart-<?php print $item['id'].$count ?>">
          <input type="hidden"  name="price" value="<?php print $v ?>" />
          <input type="hidden"  name="content_id" value="<?php print $item['id'] ?>" />
        </div>
        <?php break; endforeach ; ?>
        <?php  endif; ?>
      </div>
    </div>
    <?php if($count % 3 == 0 or $count == $len){ ?>
  </div>
  <?php  } ?>
  <?php  endforeach; ?>
</div>
<?php endif; ?>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
<?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}&limit=7") ?>
<?php endif; ?>
