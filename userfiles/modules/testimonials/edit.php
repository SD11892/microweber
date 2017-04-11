<?php only_admin_access(); ?>
<script>
    $(document).ready(function () {
        $("#add-testimonial-form").submit(function (event) {
            event.preventDefault();
            var data = $(this).serialize();
            var url = "<?php print api_url('save_testimonial'); ?>";
            var post = $.post(url, data);
            post.done(function (data) {
                mw.reload_module_parent("testimonials");
                mw.reload_module("testimonials/list");
                mw.reload_module("#project-select-testimonials");
                $('.addNewButton').show();
                $('.saveButton').hide();

                $("#add-testimonial-form").find("input[type=text], textarea").val("");
                window.TTABS.set(0)
            });
        });
    });

    testimonialPicture = mw.uploader({
        filetypes: "images",
        element: "#client_img",
        multiple: false
    });


    $(testimonialPicture).bind("FileUploaded", function (a, b) {
        mw.$("#client_picture").val(b.src).trigger('change');

        //  mw.$("#openquote-preview img").attr("src",  b.src);
        // mw.$("[name='openquote']").val(b.src).trigger('change');
    });
</script>

<?php $data = false; ?>
<?php if (isset($params['edit-id'])): ?>
    <?php $data = get_testimonials("single=true&id=" . $params['edit-id']); ?>
<?php endif; ?>

<?php if (($data['id']) != 0): ?>
    <script>
        $(document).ready(function () {
            $('.addNewButton').hide();
            $('.saveButton').show();
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function () {
            $('.saveButton').hide();
            $('.addNewButton').show();
        });
    </script>
<?php endif; ?>

<?php

if (!isset($data['id'])) {
    $data['id'] = 0;
}
if (!isset($data['name'])) {
    $data['name'] = '';
}
if (!isset($data['content'])) {
    $data['content'] = '';
}
if (!isset($data['read_more_url'])) {
    $data['read_more_url'] = '';
}
if (!isset($data['project_name'])) {
    $data['project_name'] = '';
}
if (!isset($data['client_role'])) {
    $data['client_role'] = '';
}
if (!isset($data['client_picture'])) {
    $data['client_picture'] = '';
}
if (!isset($data['client_website'])) {
    $data['client_website'] = '';
}

if (!isset($data['client_company'])) {
    $data['client_company'] = '';
}


?>


<form id="add-testimonial-form">
    <?php if (($data['id']) == 0): ?>
        <h3><?php _e('Add new testimonial'); ?></h3>
    <?php else: ?>
        <h3><?php _e('Edit testimonial'); ?></h3>
    <?php endif; ?>
    <input type="hidden" name="id" value="<?php print $data['id'] ?>"/>
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e('Client Name'); ?></label>
        <input type="text" name="name" placeholder="Name" value="<?php print $data['name'] ?>" class="mw-ui-field w100">
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e('Client Picture'); ?></label>
        <input type="text" name="client_picture" id="client_picture" value="<?php print $data['client_picture'] ?>"
               class="mw-ui-field"><span class="mw-ui-btn" id="client_img"><span class="mw-icon-upload"></span><?php _e('Select Image'); ?></span>
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e('Client Testimonial'); ?></label>
        <textarea name="content" class="mw-ui-field w100"><?php print $data['content'] ?></textarea>
    </div>

    <span class="mw-ui-btn" onclick="$('#more-testimonial-settings').slideToggle()" style="background: #efecec;">
    <span class="mw-icon-app-gear"></span><?php _e('More Settings'); ?></span>
    <div id="more-testimonial-settings" style="display: none">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _e('Client Role'); ?></label>
            <input type="text" name="client_role" placeholder="CEO, CTO, etc"
                   value="<?php print $data['client_role'] ?>" class="mw-ui-field w100">
        </div>


        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _e('Client Company'); ?></label>
            <input type="text" name="client_company" placeholder="Awesome Co."
                   value="<?php print $data['client_company'] ?>" class="mw-ui-field w100">
        </div>


        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _e('Client Website'); ?></label>
            <input type="text" name="client_website" placeholder="http://www.example.com"
                   value="<?php print $data['client_website'] ?>" class="mw-ui-field w100">
        </div>


        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _e('"Read more" link'); ?></label>
            <input type="text" name="read_more_url" value="<?php print $data['read_more_url'] ?>"
                   class="mw-ui-field w100">
        </div>
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _e('Project name'); ?></label>
            <input type="text" name="project_name" value="<?php print $data['project_name'] ?>"
                   class="mw-ui-field w100">
        </div>
    </div>
    <div class="mw-popup-footer">
        <div class="mw-popup-footer-content">
            <input type="submit" name="submit" value="Save" class="mw-ui-btn mw-ui-btn-invert pull-right"/>
        </div>
    </div>
</form>
