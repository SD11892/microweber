<?php must_have_access(); ?>

<script>
    function delete_testimonial(id) {
        var are_you_sure = confirm('<?php _e('Are you sure?'); ?>');
        if (are_you_sure == true) {
            var data = {}
            data.id = id;
            var url = "<?php print api_url('delete_testimonial'); ?>";
            var post = $.post(url, data);
            post.done(function (data) {
                mw.reload_module("testimonials");
                mw.reload_module("testimonials/list");

            });
        }
    }

    add_testimonial = function () {
        $('.js-add-new-button').hide();
        $("#edit-testimonials").attr("edit-id", "0");
        mw.reload_module("#edit-testimonials");
    }

    add_new_testimonial = function () {
        $('.js-add-new-testimonials').trigger('click');
    }

    list_testimonial = function () {
        $('.js-list-testimonials').trigger('click');
    }

    edit_testimonial = function (id) {
        $('.js-add-new-button').show();
        $("#edit-testimonials").attr("edit-id", id);
        mw.reload_module("#edit-testimonials");
        $('.js-add-new-testimonials').trigger('click');
    }

    $(document).ready(function () {
        mw.$("#testimonials-list tbody").sortable({
            change: function () {

            },
            axis: 'y',
            start: function () {
                mw.$("#testimonials-list").addClass('dragging')
            },
            stop: function () {
                mw.$("#testimonials-list").removeClass('dragging');

                var data = {
                    ids: []
                }
                mw.$("#testimonials-list tbody tr").each(function () {
                    data.ids.push($(this).dataset('id'));
                });

                $.post("<?php print api_url(); ?>reorder_testimonials", data, function () {
                    parent.mw.reload_module("testimonials");
                });

            }
        });

        mw.$("#AddNew").click(function () {
            mw.$("#add-testimonial-form").show();
            mw.$(this).hide();
        });
    });
</script>
<script>mw.lib.require('mwui_init');</script>

<style>
    .testimonial-client-image {
        -webkit-border-radius: 100%;
        -moz-border-radius: 100%;
        border-radius: 100%;
        width: 75px;
        height: 75px;
        -webkit-background-size: cover;
        background-size: cover;
        margin: 0 auto 10px auto;
    }

    .text-danger.position-absolute {
        right: -10px;
        top: -20px;
    }

    .testimonial-holder .text-danger.position-absolute {
        display: none;
    }

    .testimonial-holder:hover .text-danger.position-absolute {
        display: block;
    }
</style>

<?php $data = get_testimonials(); ?>
<?php if ($data): ?>
    <div class="muted-cards-3">
        <?php foreach ($data as $item): ?>
            <div class="card style-1 testimonial-holder mb-3" data-id="<?php print $item['id'] ?>">
                <div class="card-body">
                    <div class="row">
                        <div class="col-auto d-flex flex-column align-items-center">
                            <div class="img-circle-holder img-absolute">
                                <img src="<?php print thumbnail($item['client_picture'], 75, 75) ?>"/>
                            </div>

                            <a class="btn btn-outline-primary btn-sm mt-2" href="javascript:;" onclick="edit_testimonial('<?php print $item['id'] ?>');">Edit</a>
                        </div>

                        <div class="col">
                            <a href="javascript:delete_testimonial('<?php print $item['id'] ?>');" class="btn btn-link text-danger btn-sm position-absolute" data-toggle="tooltip" data-title="Delete item"><i class="mdi mdi-close-thick"></i></a>

                            <h6 class="font-weight-bold"><?php print $item['name'] ?> </h6>

                            <p><?php print character_limiter($item['content'], 400); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <h2 class="text-center"><?php _e('You have no testimonials'); ?></h2>
    <div class="text-center"><a href="javascript:;" onclick="add_new_testimonial()" class="mw-ui-btn"><?php _e('Create new'); ?></a></div>
<?php endif; ?>
