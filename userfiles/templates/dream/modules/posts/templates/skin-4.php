<?php

/*

type: layout

name: Blog 3 - Posts

description: Skin 4

*/
?>

<section class="space--0">
    <div class="container">
        <div class="row">
            <div class=" masonry-blog">
                <div class="masonry__container masonry--animate">
                    <div class="row">
                        <?php if (!empty($data)): ?>
                            <?php foreach ($data as $item): ?>
                                <div class="col-sm-12 masonry__item" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                                    <a href="<?php print $item['link'] ?>" itemprop="url">
                                        <div class="card card--horizontal card-6">
                                            <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                                                <div class="card__image col-sm-7 col-md-8">
                                                    <div class="background-image-holder" style="background-image: url('<?php print thumbnail($item['image'], 1200); ?>');">
                                                        <img src="<?php print thumbnail($item['image'], 1200); ?>" alt="" />
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <div class="card__body col-sm-5 col-md-4 boxed boxed--lg bg--white">
                                                <h6>
                                                    <?php $categories = content_categories($item['id']);
                                                    if ($categories):
                                                        foreach ($categories as $key => $category): ?>
                                                            <?php if ($key < 2): ?>
                                                                <?php print $category['title']; ?>
                                                            <?php endif; ?>
                                                            <?php if ($key == 2): ?>...<?php endif; ?>
                                                        <?php endforeach; endif; ?>
                                                </h6>


                                                    <div class="card__title">
                                                        <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                                                        <h4><?php print $item['title'] ?></h4>
                                                        <?php endif; ?>

                                                        <?php if(!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>
                                                            <small class="date"><?php print $item['created_at'] ?></small>
                                                        <?php endif; ?>


                                                    </div>



                                                <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                                                    <p itemprop="description"><?php print $item['description'] ?></p>
                                                <?php endif; ?>

                                                <hr>
                                                <div class="card__lower">
                                                    <span>by</span>
                                                    <span class="h6"><?php print user_name($item['created_by']) ?></span>
                                                    <?php if($show_fields != false and ($show_fields != false and  in_array('read_more', $show_fields))): ?>

                                                        <a href="<?php print $item['link'] ?>" class="mw-more pull-right"><?php $read_more_text ? print $read_more_text : print _e('Read More', true); ?></a>

                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
<?php endif; ?>

<script>
    $(document).ready(function () {
        mr.sliders.documentReady($)
    })
</script>
