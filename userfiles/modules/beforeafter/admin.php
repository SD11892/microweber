<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <?php
        $before = get_option('before', $params['id']);
        $after = get_option('after', $params['id']);

        if ($before == false) {
            $before = module_url() . 'img/white-car.jpg';
        }

        if ($after == false) {
            $after = module_url() . 'img/blue-car.jpg';
        }
        ?>

        <style>
            .module-before-after-settings img {
                max-width: 100%;
            }
        </style>

        <div class="module-live-edit-settings module-before-after-settings">
            <input type="hidden" class="mw_option_field" name="before" id="beforeval" value="<?php print $before; ?>"/>
            <input type="hidden" class="mw_option_field" name="after" id="afterval" value="<?php print $after; ?>"/>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="control-label"><?php print _e('Upload Before Image'); ?></label>
                        <img src="<?php print $before; ?>" alt="before" class="js-before-image"/>

                        <div class="text-center mt-3">
                            <span class="btn btn-primary" id="before"><span class="fas fa-upload"></span> &nbsp; <?php _e('Choose Before Image'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label class="control-label"><?php print _e('Upload After Image'); ?></label>
                        <img src="<?php print $after; ?>" alt="after" class="js-after-image"/>

                        <div class="text-center mt-3">
                            <span class="btn btn-primary" id="after"><span class="fas fa-upload"></span> &nbsp; <?php _e('Choose After Image'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                var before = mw.uploader({
                    filetypes: "images,videos",
                    multiple: false,
                    element: "#before"
                });
                $(before).bind('FileUploaded', function (a, b) {
                    preload_image(b.src)

                    mw.$("#beforeval").val(b.src).trigger('change');
                    mw.$(".js-before-image").attr('src', b.src);
                });

                var after = mw.uploader({
                    filetypes: "images,videos",
                    multiple: false,
                    element: "#after"
                });
                $(after).bind('FileUploaded', function (a, b) {
                    preload_image(b.src)
                    mw.$("#afterval").val(b.src).trigger('change');
                    mw.$(".js-after-image").attr('src', b.src);
                });
            });

            preload_image = function (src) {
                var elem = document.createElement("img");
                elem.setAttribute("src", src);
            }
        </script>
    </div>
</div>