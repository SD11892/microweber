<?php if (is_array($data) and !empty($data)): ?>
    <script>
        $(document).ready(function () {
            $('body > #mw-admin-container > .main').addClass('show-sidebar-tree');
        });
    </script>

    <div class="manage-posts-holder" id="mw_admin_posts_sortable">
        <div class="manage-posts-holder-inner muted-cards">
            <?php if (is_array($data)): ?>
                <?php foreach ($data as $item): ?>
                    <?php if (isset($item['id'])): ?>
                        <?php
                        $pub_class = '';
                        $append = '';
                        if (isset($item['is_active']) and $item['is_active'] == '0') {
                            $pub_class = ' content-unpublished';
                            $append = '<div class="post-un-publish"><span class="mw-ui-btn mw-ui-btn-yellow disabled unpublished-status">' . _e("Unpublished", true) . '</span><span class="mw-ui-btn mw-ui-btn-green publish-btn" onclick="mw.post.set(' . $item['id'] . ', \'publish\');">' . _e("Publish", true) . '</span></div>';
                        }
                        ?>
                        <?php $pic = get_picture($item['id']); ?>
                        <div class="card card-product-holder mb-2 post-has-image-<?php print ($pic == true ? 'true' : 'false'); ?> manage-post-item-type-<?php print $item['content_type']; ?> manage-post-item manage-post-item-<?php print ($item['id']) ?> <?php print $pub_class ?>">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col text-center manage-post-item-col-1" style="max-width: 40px;">
                                        <label class="mw-ui-check"><input name="select_posts_for_action" class="select_posts_for_action" type="checkbox" value="<?php print ($item['id']) ?>"><span></span></label>
                                        <span class="btn btn-link text-muted px-0 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()"><i class="mdi mdi-cursor-move"></i></span>
                                    </div>

                                    <div class="col manage-post-item-col-2" style="max-width: 120px;">
                                        <?php if ($pic == true): ?>
                                            <div class="position-absolute text-muted" style="z-index: 1; right: 0; top: -10px;">
                                                <?php if (isset($item['content_type']) and $item['content_type'] == 'page'): ?>
                                                    <?php if (isset($item['is_shop']) and $item['is_shop'] == 1): ?>
                                                        <i class="mdi mdi-shopping mdi-18px" data-toggle="tooltip" title="Shop"></i>
                                                    <?php else : ?>
                                                        <i class="mdi mdi-post-outline mdi-18px" data-toggle="tooltip" title="Page"></i>
                                                    <?php endif; ?>
                                                <?php elseif (isset($item['content_type']) and ($item['content_type'] == 'post' or $item['content_type'] == 'product')): ?>
                                                    <?php if (isset($item['content_type']) and $item['content_type'] == 'product'): ?>
                                                        <i class="mdi mdi-shopping mdi-18px" data-toggle="tooltip" title="Product"></i>
                                                    <?php else : ?>
                                                        <i class="mdi mdi-text  mdi-18px" data-toggle="tooltip" title="Post"></i>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="img-circle-holder border-radius-0 border-0">
                                            <?php if ($pic == true): ?>
                                                <a href="javascript:;" onClick="mw.url.windowHashParam('action','editpage:<?php print ($item['id']) ?>');return false;">
                                                    <img src="<?php print thumbnail($pic, 108) ?>"/>
                                                </a>
                                            <?php else : ?>
                                                <a href="javascript:;" onclick="mw.url.windowHashParam('action','editpage:<?php print ($item['id']) ?>');return false;">
                                                    <?php if (isset($item['content_type']) and $item['content_type'] == 'page'): ?>
                                                        <?php if (isset($item['is_shop']) and $item['is_shop'] == 1): ?>
                                                            <i class="mdi mdi-shopping mdi-48px text-muted"></i>
                                                        <?php else : ?>
                                                            <i class="mdi mdi-shopping mdi-48px text-muted"></i>
                                                        <?php endif; ?>
                                                    <?php elseif (isset($item['content_type']) and ($item['content_type'] == 'post' or $item['content_type'] == 'product')): ?>
                                                        <?php if (isset($item['content_type']) and $item['content_type'] == 'product'): ?>
                                                            <i class="mdi mdi-shopping mdi-48px text-muted"></i>
                                                        <?php else : ?>
                                                            <i class="mdi mdi-text mdi-48px text-muted"></i>
                                                        <?php endif; ?>
                                                    <?php else : ?>
                                                    <?php endif; ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <?php $edit_link = admin_url('view:content#action=editpage:' . $item['id']); ?>
                                        <?php $edit_link_front = content_link($item['id']) . '?editmode:y'; ?>
                                    </div>

                                    <div class="col item-title manage-post-item-col-3 manage-post-main">
                                        <div class="manage-item-main-top">
                                            <a target="_top" href="<?php print $edit_link_front; ?>" class="btn btn-link p-0">
                                                <h5 class="text-dark text-break-line-1 mb-0 manage-post-item-title"><?php print strip_tags($item['title']) ?></h5>
                                            </a>
                                            <?php mw()->event_manager->trigger('module.content.manager.item.title', $item) ?>

                                            <?php $cats = content_categories($item['id']); ?>
                                            <?php $tags = content_tags($item['id'], false); ?>
                                            <?php if ($cats): ?>
                                                <span class="manage-post-item-cats-inline-list">
                                                    <?php foreach ($cats as $ck => $cat): ?>
                                                        <a href="#action=showpostscat:<?php print ($cat['id']); ?>" class="btn btn-link p-0 text-muted"><?php print $cat['title']; ?></a><?php if (isset($cats[$ck + 1])): ?>,<?php endif; ?>
                                                    <?php endforeach; ?>
                                              </span>
                                            <?php endif; ?>

                                            <?php if ($tags): ?>
                                                <br/>
                                                <?php foreach ($tags as $tag): ?>
                                                    <small class="bg-secondary rounded-lg px-2">#<?php echo $tag; ?></small>
                                                <?php endforeach; ?>
                                            <?php endif; ?>


                                            <a class="manage-post-item-link-small mw-medium" target="_top" href="<?php print content_link($item['id']); ?>?editmode:y">
                                                <small class="text-muted"><?php print content_link($item['id']); ?></small>
                                            </a>
                                        </div>

                                        <div class="manage-post-item-links">
                                            <a target="_top" class="btn btn-outline-primary btn-sm" href="<?php print $edit_link ?>" onclick="javascript:mw.url.windowHashParam('action','editpage:<?php print ($item['id']) ?>'); return false;">
                                                <?php _e("Edit"); ?>
                                            </a>

                                            <a target="_top" class="btn btn-outline-success btn-sm" href="<?php print content_link($item['id']); ?>?editmode:y">
                                                <?php _e("Live Edit"); ?>
                                            </a>

                                            <a class="btn btn-outline-danger btn-sm" href="javascript:mw.delete_single_post('<?php print ($item['id']) ?>');">
                                                <?php _e("Delete"); ?>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col item-author manage-post-item-col-4">
                                        <span class="text-muted" title="<?php print user_name($item['created_by']); ?>"><?php print user_name($item['created_by'], 'username') ?></span>
                                    </div>

                                    <div class="col item-comments manage-post-item-col-5" style="max-width: 100px;">
                                        <?php mw()->event_manager->trigger('module.content.manager.item', $item) ?>
                                        <?php print $append; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php
        $numactive = 1;

        if (isset($params['data-page-number'])) {
            $numactive = intval($params['data-page-number']);
        } else if (isset($params['current_page'])) {
            $numactive = intval($params['current_page']);
        }
        ?>

        <?php if (isset($paging_links) and is_array($paging_links)): ?>
            <div class="mw-paging" style="display: none">
                <?php $i = 1; ?>
                <?php foreach ($paging_links as $item): ?>
                    <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>" href="#<?php print $paging_param ?>=<?php print $i ?>" onclick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $i ?>');return false;"><?php print $i; ?></a>
                    <?php $i++; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($paging_links) and is_array($paging_links)): ?>
            <div class="mw-paging pull-right">
                <?php $count = count($paging_links); ?>
                <?php if ($count < 6): ?>
                    <?php $i = 1; ?>
                    <?php foreach ($paging_links as $item): ?>
                        <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>" href="#<?php print $paging_param ?>=<?php print $i ?>" onclick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $i ?>');return false;"><?php print $i; ?></a>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php if ($numactive > 2): ?>
                        <a class="page-1" href="#<?php print $paging_param ?>=1" onclick="mw.url.windowHashParam('<?php print $paging_param ?>','1');return false;">First</a>

                        <?php for ($i = $numactive - 2; $i <= $numactive + 2; $i++): ?>
                            <?php if ($i < $count): ?>
                                <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>" href="#<?php print $paging_param ?>=<?php print $i ?>" onclick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $i ?>');return false;"><?php print $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                    <?php else: ?>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>" href="#<?php print $paging_param ?>=<?php print $i ?>" onclick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $i ?>');return false;"><?php print $i; ?></a>
                        <?php endfor; ?>
                    <?php endif; ?>

                    <a class="page-<?php print $count; ?>" href="#<?php print $paging_param . '=' . ($count - 1); ?>" onclick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $count - 1; ?>');return false;"><?php _e("Last"); ?></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
<?php else: ?>
    <?php
    $page_is_shop = false;
    if (isset($post_params["page-id"])) {
        $page_is_shop_check = get_content_by_id($post_params["page-id"]);
        if (isset($page_is_shop_check['is_shop']) and $page_is_shop_check['is_shop'] == 1) {
            $page_is_shop = true;
        }
    }

    if ((isset($post_params['content_type']) and $post_params['content_type'] == 'product') or (isset($params['content_type']) and $params['content_type'] == 'product') or $page_is_shop) : ?>
        <div class="no-items-found products">
            <?php
            /*  if (isset($post_params['category-id'])) {
                  $url = "#action=new:product&amp;category_id=" . $post_params['category-id'];
              } elseif (isset($post_params['category'])) {
                  $url = "#action=new:product&amp;category_id=" . $post_params['category'];
              } else if (isset($post_params['parent'])) {
                  $url = "#action=new:product&amp;parent_page=" . $post_params['parent'];
              } else {
                  $url = "#action=new:product";
              }*/
            $url = "#action=new:product";

            ?>

            <div class="row">
                <div class="col-12">
                    <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_products.svg'); ">
                        <h4>You don’t have any products yet</h4>
                        <p>Create your first post right now.<br/>
                            You are able to do that in very easy way!</p>
                        <br/>
                        <a href="<?php print$url; ?>" class="btn btn-primary btn-rounded">Create a Product</a>
                    </div>
                </div>
            </div>


            <script>
                $(document).ready(function () {
                    $('body > #mw-admin-container > .main').removeClass('show-sidebar-tree');
                });
            </script>
            <script>
                $(document).ready(function () {
                    $('.manage-toobar').hide();
                    $('.top-search').hide();
                });
            </script>
        </div>
    <?php else: ?>
        <div class="no-items-found posts">
            <?php
            //                if (isset($post_params['category-id'])) {
            //                    $url = "#action=new:post&amp;category_id=" . $post_params['category-id'];
            //
            //                } elseif (isset($post_params['category'])) {
            //                    $url = "#action=new:post&amp;category_id=" . $post_params['category'];
            //
            //                } else if (isset($post_params['parent'])) {
            //                    $url = "#action=new:post&amp;parent_page=" . $post_params['parent'];
            //
            //                }

            $url = "#action=new:post"
            ?>

            <div class="row">
                <div class="col-12">
                    <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_content.svg'); ">
                        <h4>You don’t have any posts yet</h4>
                        <p>Create your first post right now.<br/>
                            You are able to do that in very easy way!</p>
                        <br/>
                        <a href="<?php print$url; ?>" class="btn btn-primary btn-rounded">Create a Post</a>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function () {
                    $('body > #mw-admin-container > .main').removeClass('show-sidebar-tree');
                });
            </script>

            <script>
                $(document).ready(function () {
                    $('.manage-toobar').hide();
                    $('.top-search').hide();
                });
            </script>
        </div>
    <?php endif; ?>

<?php endif; ?>
