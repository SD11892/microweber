<h5><?php _e('Select the language to export:');?></h5>

<script>
    function exportTheSelectedLanguage(namespace) {

        var locale = $('.js-export-selected-locale-val').val();

        $('.js-export-selected-locale-btn').attr('disabled','disabled');

        $.ajax({
            type: "POST",
            url: "<?php echo route('admin.language.export'); ?>",
            data: "namespace=" + namespace + "&locale=" + locale,
            success: function (data) {
                if (data.files[0].download) {
                    window.location = data.files[0].download;
                } else {
                    mw.notification.error("<?php _e("Can't export the language pack."); ?>");
                }
                $('.js-export-selected-locale-btn').removeAttr('disabled');
            }
        });
    }
</script>
<?php
$supportedLanguages = [];
if (function_exists('get_supported_languages')) {
    $supportedLanguages = get_supported_languages(true);
}

if(empty($supportedLanguages)){
    $currentLanguageAbr = mw()->lang_helper->default_lang();

    $supportedLanguages[] = [
        'icon'=>$currentLanguageAbr,
        'locale'=>$currentLanguageAbr
    ];
}
?>
<select class="form-control js-export-selected-locale-val">
    <?php
    foreach ($supportedLanguages as $supportedLanguage):
    ?>
    <option value="<?php echo $supportedLanguage['locale'];?>"><?php echo strtoupper($supportedLanguage['locale']); ?></option>
    <?php
    endforeach;
    ?>
</select>
<br />
<button type="button" onclick="exportTheSelectedLanguage('<?php echo $params['namespace'];?>');" class="btn btn-success js-export-selected-locale-btn"><i class="mdi mdi-download"></i> <?php _e('Export & Download');?> </button>