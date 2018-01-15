<?php

/*

  type: layout
  content_type: dynamic
  name: Blog
  position: 5
  description: Blog
  tag: blog

*/

?>
<?php include TEMPLATE_DIR . "header.php"; ?>

<div class="edit" rel="content" field="liteness_content">
    <div class="container nodrop safe-mode" id="page-container-<?php print PAGE_ID; ?>">
        <div class="box-container">
            <div class="row">
                <div class="col-md-9">
                    <div class="content-header">
                        <h2 class="page-title">Page Title</h2>
                    </div>

                    <div class="masonry-gallery">
                        <module content-id="<?php print PAGE_ID; ?>" type="posts"/>
                    </div>
                </div>

                <div class="col-md-3" id="blog-sidebar">
                    <?php include "blog_sidebar.php"; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include TEMPLATE_DIR . "footer.php"; ?>
