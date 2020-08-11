<?php

only_admin_access();


$for = $for_id = $sess_id = false;



if (isset($params['for'])) {
    $for = $params['for'];
}
if (isset($params['for_id'])) {
    $for_id = $params['for_id'];
}

if (isset($params['session_id'])) {
    $sess_id = $params['session_id'];
}

$media = false;

if ($for_id != false) {
    $media = get_pictures("rel_id={$for_id}&rel_type={$for}");
} else {
    $sid = mw()->user_manager->session_id();
    $media = get_pictures("rel_id=0&rel_type={$for}&session_id={$sid}");
}



$init_image_options = array();
$default_image_options = 'Link, Caption, Author, Source, Tags';
$image_options = (isset($params['image-options']) ? $params['image-options'] : (isset($params['data-image-options']) ? $params['data-image-options'] : $default_image_options));


$temp = explode(',', $image_options);
foreach ($temp as $i) {
    array_push($init_image_options, trim($i));
}



$rand = 'pic-sorter-'.uniqid();

?>

<style>
    .admin-thumb-item-uploader-holder{
        display: block;
        position: relative;
        float: left;
        width: 18%;
        height: 110px;
        margin: 0 1% 1%;
    }
    .admin-thumb-item-uploader-holder:hover .dropable-zone.small-zone button{
        text-decoration: underline;
    }
    .admin-thumb-item-uploader-holder:hover .dropable-zone.small-zone{
        border-color: #4592ff;
        background-color: rgba(69, 146, 255, 0.1);
    }
</style>


<div class="mw-drop-zone" id="admin-thumbs-drop-zone-<?php print $rand; ?>" style="display: none">Drop here to upload</div>



<script>
    $(document).ready(function () {
        mw.module_pictures.init('#admin-thumbs-holder-sort-<?php print $rand; ?>');

        var uploadHolder =  mw.$('#admin-thumb-item-uploader<?php print $rand; ?>');
        mw.require('uploader.js');

        mw._postsImageUploaderSmall = mw.upload({
            element: uploadHolder,
            accept: 'image/*',
            multiple:true,
            dropZone: '#admin-thumbs-drop-zone-<?php print $rand; ?>',
        });
        mw._postsImageUploaderSmall.$holder = uploadHolder.parent();
        $(mw._postsImageUploaderSmall).on('FileAdded', function (e, res) {
            mw._postsImageUploader._thumbpreload()
        })
        $(mw._postsImageUploaderSmall).on('FileUploaded', function (e, res) {
            var url = res.src ? res.src : res;
            if(window.after_upld) {
                after_upld(url, 'Result', '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');
                after_upld(url, 'done');
                mw._postsImageUploader.hide()
            }
        });

        if (!mw.$('#admin-thumbs-holder-sort-<?php print $rand; ?> .admin-thumb-item').length) {
            uploadHolder.hide();
            if(mw._postsImageUploader) {
                mw._postsImageUploader.show();
            }

        }

        var dropdownUploader;

        mw.$('#mw-admin-post-media-type')
            .selectpicker()
            .on('changed.bs.select', function () {
                mw._postsImageUploader.displayControllerByType($(this).selectpicker('val'))
                setTimeout(function () {
                    $(this).selectpicker('val', null);
                }, 200)

            })
            .on('show.bs.select', function () {
                if(!!dropdownUploader) return;
                var item = mw.$('#mw-admin-post-media-type').parent().find('li:last');
                dropdownUploader = mw.upload({
                    element: item,
                    accept: 'image/*',
                    multiple:true
                });
                $(dropdownUploader).on('FileAdded', function (e, res) {
                    mw._postsImageUploader._thumbpreload()
                })
                $(dropdownUploader).on('FileUploaded', function (e, res) {
                    var url = res.src ? res.src : res;
                    if(window.after_upld) {
                        after_upld(url, 'Result', '<?php print $for ?>', '<?php print $for_id ?>', '<?php print $params['id'] ?>');
                        after_upld(url, 'done');
                        mw._postsImageUploader.hide()
                    }
                });
            })

        var dragTimer;
        $(document).on('dragover', function(e) {
            var dt = e.originalEvent.dataTransfer;
            if (dt.types && (dt.types.indexOf ? dt.types.indexOf('Files') !== -1 : dt.types.contains('Files'))) {
                $("#admin-thumbs-drop-zone-<?php print $rand; ?>").show();
                clearTimeout(dragTimer);
            }
        });
        $(document).on('dragleave', function(e) {
            dragTimer = setTimeout(function() {
                $("#admin-thumbs-drop-zone-<?php print $rand; ?>").hide();
            }, 25);
        });

        $("#admin-thumbs-drop-zone-<?php print $rand; ?>").on('drop', function () {
            $("#admin-thumbs-drop-zone-<?php print $rand; ?>").hide();
        });

        setInterval(function () {
            $('.admin-thumb-item, .admin-thumb-item-placeholder, .admin-thumb-item-uploader-holder').each(function(){
                $(this).height( $(this).width())
            })
        }, 78)


    });


</script>

<div class="admin-thumbs-holder" id="admin-thumbs-holder-sort-<?php print $rand; ?>">
<?php if (is_array($media)): ?>
    <?php $default_title = _e("Image title", true); ?>
    <?php foreach ($media as $key => $item): ?>
        <div class="admin-thumb-item admin-thumb-item-<?php print $item['id'] ?>"
             id="admin-thumb-item-<?php print $item['id'] ?>">


            <?php

            $tn = thumbnail($item['filename'], 200, 200, true); ?>
            <span class="mw-post-media-img" style="background-image: url('<?php print $tn; ?>');"></span>
            <?php  if ($key == 0): ?>

           <div class="featured-image"><?php print _e('featured image'); ?></div>

            <?php  endif; ?>
            <span class="mdi mdi-cog image-settings tip" data-tip="Image Settings"
                  onclick="imageConfigDialog(<?php print $item['id'] ?>)"></span>
            <span class="mw-icon-close image-settings remove-image tip" data-tip="Delete Image"
                  onclick="mw.module_pictures.del('<?php print $item['id'] ?>');"></span>
            <label class="mw-ui-check">
                <input type="checkbox" onchange="doselect()" data-url="<?php print $item['filename']; ?>"
                       value="<?php print $item['id'] ?>"><span></span>
            </label>
            <div class="mw-post-media-img-edit">

                <div class="image-options">
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label"><?php _e("Alt text"); ?></label>
                        <input class="mw-ui-field w100" autocomplete="off" value="<?php if ($item['title'] !== '') {
                            print $item['title'];
                        } else {
                            print $default_title;
                        } ?>"
                               onkeyup="mw.on.stopWriting(this, function(){mw.module_pictures.save_title('<?php print $item['id'] ?>', this.value);});"
                               onfocus="$(this.parentNode).addClass('active');"
                               onblur="$(this.parentNode).removeClass('active');"
                               name="media-description-<?php print $tn; ?>"/>

                    </div>

                    <div id="image-json-options-<?php print  $item['id']; ?>">
                        <div class="image-json-options">
                            <?php
                            $curr = isset($item['image_options']) ? $item['image_options'] : array();
                            foreach ($init_image_options as $name) {
                                $ok = url_title(strtolower($name));
                                ?>
                                <div class="mw-ui-field-holder">
                                    <label class="mw-ui-label"><?php print $name ?></label>
                                    <input type="text" class="mw-ui-field w100" name="<?php print $ok; ?>"
                                           value="<?php print isset($curr[$ok]) ? $curr[$ok] : ''; ?>"/>
                                </div>
                            <?php } ?>

                            <hr>

                            <span class="mw-ui-btn pull-left" onclick="imageConfigDialogInstance.remove()">Cancel</span>
                            <span class="mw-ui-btn mw-ui-btn-notification pull-right"
                                  onclick="saveOptions(<?php print $item['id'] ?>);imageConfigDialogInstance.remove()">Update</span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<div class="admin-thumb-item-uploader-holder">
    <div class="dropable-zone small-zone square-zone">
        <div class="holder"> <button type="button" class="btn btn-link">Add file</button> <p>or drop file to upload</p> </div>
    </div>
    <div class="admin-thumb-item-uploader" id="admin-thumb-item-uploader<?php print $rand; ?>">

    </div>
</div>
<?php endif; ?>



</div>
