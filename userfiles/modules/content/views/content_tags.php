<?php
if (!function_exists('content_tags')) {
    return;
}
$tags_str = array();

must_have_access();

if (!isset($params['content-id'])) {
    return;
}

if ($params['content-id']) {
    $tags_str = content_tags($params['content-id']);
}

if (!$tags_str) {
    $tags_str = array();
}


$all_existing_tags = json_encode(content_tags());
if ($all_existing_tags == null) {
    $all_existing_tags = '[]';
}
?>


<script type="text/javascript">
    mw.lib.require('bootstrap_tags');

    $(document).ready(function () {
        var data = <?php print $all_existing_tags; ?>;

        var tags = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local: data
        });
        tags.initialize();

        $('input[name="tag_names"]').tagsinput({
            allowDuplicates: false,
            typeaheadjs: {
                name: "tags",
                source: tags.ttAdapter()
            },
            freeInput: true
        });
    });
</script>

<div class="row">
    <div class="col-12">
        <input type="text" name="tag_names" data-role="tagsinput" value="<?php print implode(',', $tags_str); ?>" placeholder="<?php _e("Separate options with a comma"); ?>" id="tags"/>
    </div>
</div>
