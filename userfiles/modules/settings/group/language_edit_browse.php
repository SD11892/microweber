<?php
$filter = [];
if (isset($params['search'])) {
    $filter['search'] = $params['search'];
}
if (isset($params['page'])) {
    $filter['page'] = $params['page'];
}

if (isset($params['translation_namespace'])) {
    $filter['translation_namespace'] = $params['translation_namespace'];
} else {
    $filter['translation_namespace'] = 'global';
}

$namespace = $filter['translation_namespace'];
$namespaceMd5 = md5($namespace);

\Config::set('microweber.disable_model_cache', true);
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


$getTranslations = \MicroweberPackages\Translation\Models\Translation::getGroupedTranslations($filter);


?>
<script>

    <?php
    if (empty($getTranslations['results'])):
    ?>
    $('.js-language-edit-browse-<?php echo $namespaceMd5;?>').fadeOut();
    <?php
   else:
    ?>
    $('.js-language-edit-browse-<?php echo $namespaceMd5;?>').fadeIn();
    <?php
    endif;
    ?>

    $('#language-edit-<?php echo $namespaceMd5;?>').collapse('show');
</script>

<script>

    /**
     * jQuery serializeObject
     * @copyright 2014, macek <paulmacek@gmail.com>
     * @link https://github.com/macek/jquery-serialize-object
     * @license BSD
     * @version 2.5.0
     */
    !function(e,i){if("function"==typeof define&&define.amd)define(["exports","jquery"],function(e,r){return i(e,r)});else if("undefined"!=typeof exports){var r=require("jquery");i(exports,r)}else i(e,e.jQuery||e.Zepto||e.ender||e.$)}(this,function(e,i){function r(e,r){function n(e,i,r){return e[i]=r,e}function a(e,i){for(var r,a=e.match(t.key);void 0!==(r=a.pop());)if(t.push.test(r)){var u=s(e.replace(/\[\]$/,""));i=n([],u,i)}else t.fixed.test(r)?i=n([],r,i):t.named.test(r)&&(i=n({},r,i));return i}function s(e){return void 0===h[e]&&(h[e]=0),h[e]++}function u(e){switch(i('[name="'+e.name+'"]',r).attr("type")){case"checkbox":return"on"===e.value?!0:e.value;default:return e.value}}function f(i){if(!t.validate.test(i.name))return this;var r=a(i.name,u(i));return l=e.extend(!0,l,r),this}function d(i){if(!e.isArray(i))throw new Error("formSerializer.addPairs expects an Array");for(var r=0,t=i.length;t>r;r++)this.addPair(i[r]);return this}function o(){return l}function c(){return JSON.stringify(o())}var l={},h={};this.addPair=f,this.addPairs=d,this.serialize=o,this.serializeJSON=c}var t={validate:/^[a-z_][a-z0-9_]*(?:\[(?:\d*|[a-z0-9_]+)\])*$/i,key:/[a-z0-9_]+|(?=\[\])/gi,push:/^$/,fixed:/^\d+$/,named:/^[a-z0-9_]+$/i};return r.patterns=t,r.serializeObject=function(){return new r(i,this).addPairs(this.serializeArray()).serialize()},r.serializeJSON=function(){return new r(i,this).addPairs(this.serializeArray()).serializeJSON()},"undefined"!=typeof i.fn&&(i.fn.serializeObject=r.serializeObject,i.fn.serializeJSON=r.serializeJSON),e.FormSerializer=r,r});


    $(document).ready(function () {

        $('.js-search-lang-text').on('input', function () {
            var searchText = $(this).val();
            $('.js-language-edit-browse-<?php echo $namespaceMd5;?>').attr('page', 1);
            $('.js-language-edit-browse-<?php echo $namespaceMd5;?>').attr('search', searchText);
            mw.reload_module('.js-language-edit-browse-<?php echo $namespaceMd5;?>');


            setTimeout(function() {
                $('.js-lang-edit-form-messages').html('');
                if ($('.js-language-edit-browse-module:hidden').size() == $('.js-language-edit-browse-module').size()) {
                    $('.js-lang-edit-form-messages').html('<div class="alert alert-warning"><?php _e('No results found');?></div>');
                }
            }, 2000);

        });

        $('.mw_lang_item_textarea_edit').on('input', function () {
            mw.on.stopWriting(this,function(){

               var  saveTranslations = JSON.stringify($('.js-translate-changed-fields').find('input,textarea,select').serializeObject());
               saveTranslations = btoa(encodeURIComponent(saveTranslations).replace(/%([0-9A-F]{2})/g,
                function toSolidBytes(match, p1) {
                    return String.fromCharCode('0x' + p1);
                }));

                $.ajax({
                    type: "POST",
                    url: "<?php echo route('admin.language.save'); ?>",
                    data: {translations:saveTranslations}
                }).done(function (resp) {
                    mw.notification.success('<?php _e('Settings are saved'); ?>');
                });
            });
        });

    });
</script>

<div class="card bg-light style-1 mb-3">
    <div class="card-body py-2">
        <div class="row">
            <div class="col-12">
                <div class="form-group mb-0">
                    <label class="control-label mb-0"><?php _e('Language file'); ?>:
                        <button type="button" class="btn btn-link px-0 js-lang-file-position" type="button" data-toggle="collapse" data-target="#language-edit-<?php echo $namespaceMd5;?>">
                            <?php
                            if ($namespace == '*') {
                                echo 'Global';
                            } else {
                                echo $namespace;
                            }
                            ?>
                            <i class="mdi mdi-menu-down mdi-rotate-270"></i>
                        </button>
                    </label>
                </div>
            </div>
        </div>
        <div class="collapse" id="language-edit-<?php echo $namespaceMd5;?>">
        <hr class="thin my-2"/>

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <label class="control-label m-0"><?php _e('Translate the fields to different languages'); ?></label>
            </div>

           <div>
                <button type="button" onClick="exportTranslation('<?php echo $namespace;?>')" class="btn btn-outline-primary btn-sm"><?php _e('Export to Excel'); ?></button>
                <button type="button" onClick="importTranslation('<?php echo $namespaceMd5;?>')" class="btn btn-outline-primary btn-sm"><?php _e('Import Excel file'); ?></button>
            </div>
        </div>

        <div class="js-language-pagination-<?php echo $namespaceMd5;?> text-center mt-5">
        <?php
        echo $getTranslations['pagination'];
        ?>
        </div>

        <table width="100%" class="table js-table-lang">
            <thead>
            <tr>
                <th scope="col" style="vertical-align: middle; width: 30%; max-width: 200px; overflow: hidden;"><?php _e('Key'); ?></th>
                <th scope="col"><?php _e('Value'); ?></th>
            </tr>
            </thead>
            <tbody>

            <?php
            foreach ($getTranslations['results'] as $translationKey=>$translationByLocales):
                $translationKeyMd5 = md5($translationKey . $namespaceMd5);
                ?>
                <tr style="border-bottom: 1px solid #cfcfcf">
                    <td style="vertical-align: middle; width: 30%; max-width: 200px; overflow: hidden;">
                        <div class="lang-key-holder">
                            <small><?php echo $translationKey;?></small>
                        </div>
                    </td>
                    <td style="vertical-align: middle;">
                        <?php
                        foreach ($supportedLanguages as $supportedLanguage):
                            ?>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                 <span class="flag-icon flag-icon-<?php echo $supportedLanguage['icon']; ?> m-r-10"></span>
                                </span>
                                </div>
                                <input type="hidden" name="translations[<?php echo $translationKeyMd5; ?>][<?php echo $supportedLanguage['locale'];?>][translation_group]" value="*">
                                <input type="hidden" name="translations[<?php echo $translationKeyMd5; ?>][<?php echo $supportedLanguage['locale'];?>][translation_namespace]" value="<?php echo $namespace;?>">
                                <textarea name="translations[<?php echo $translationKeyMd5; ?>][<?php echo $supportedLanguage['locale'];?>][translation_key]" style="display:none;"><?php echo $translationKey;?></textarea>
                                <textarea oninput="$(this).parent().addClass('js-translate-changed-fields');" name="translations[<?php echo $translationKeyMd5; ?>][<?php echo $supportedLanguage['locale'];?>][translation_text]" class="mw_lang_item_textarea_edit form-control form-control-sm" aria-label="" aria-describedby="basic-addon1" wrap="soft" rows="2"><?php if(isset($translationByLocales[$supportedLanguage['locale']])): echo $translationByLocales[$supportedLanguage['locale']]; else: echo $translationKey; endif; ?></textarea>
                            </div>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

            <div class="js-language-pagination-<?php echo $namespaceMd5;?>">
                <?php
                echo $getTranslations['pagination'];
                ?>
            </div>
        </div>


        <script>
            // Laravel Pagination
            $(document).on('click', '.js-language-pagination-<?php echo $namespaceMd5;?> .pagination a', function(event){
                event.preventDefault();

                var page = $(this).attr('href').split('page=')[1];

                $('.js-language-edit-browse-<?php echo $namespaceMd5;?>').attr('page', page);
                mw.reload_module('.js-language-edit-browse-<?php echo $namespaceMd5;?>');

            });
        </script>


    </div>
</div>

