<?php only_admin_access(); ?>

<script src="<?php print modules_url()?>editor/html_editor/html_editor.js"></script>

<script type="text/javascript">
    mw.require('options.js');
   // mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.min.css');
</script>





<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.min.css">

<link rel="stylesheet" href="https://codemirror.net/theme/material.css">








<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.min.js"></script>




<!--
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/htmlmixed/htmlmixed.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/css/css.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/xml/xml.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/javascript/javascript.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/css/css.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/vbscript/vbscript.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/php/php.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/addon/display/autorefresh.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/addon/selection/selection-pointer.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/addon/fold/xml-fold.js"></script>
-->




<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/addon/fold/foldgutter.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/addon/fold/foldcode.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/addon/fold/foldgutter.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/addon/fold/brace-fold.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/addon/fold/xml-fold.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/addon/fold/indent-fold.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/addon/fold/markdown-fold.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/addon/fold/comment-fold.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/javascript/javascript.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/xml/xml.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/css/css.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/htmlmixed/htmlmixed.js"></script>








<script src="//cdnjs.cloudflare.com/ajax/libs/js-beautify/1.7.4/beautify.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/js-beautify/1.7.4/beautify-css.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/js-beautify/1.7.4/beautify-html.min.js"></script>




<style>
    .CodeMirror, #select_edit_field_wrap { height: 100%; }
    html,body{
        direction: initial;
    }


    #save{
      margin: 10px 0 0 0;
    }

    .mw-ui-row > .mw-ui-col:last-child > .mw-ui-col-container, .mw-ui-row-nodrop > .mw-ui-col:last-child{
      padding-right: 0;
    }

    .liframe{
        width:100%;
        height: 120px;
        overflow: hidden;
        position: relative;
    }

    .liframe:after{
        position: absolute;
        content: '';
        display: block;
        z-index: 1;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        top:0;
        left: 0;
    }

    .liframe iframe{
        overflow: hidden;
        width:300%;
        height: 360px;
        transform: scale(.33333);
        transform-origin: 0 0;
        pointer-events: none;
    }

</style>

<script type="text/javascript">
    mw.require('<?php print modules_url()?>editor/selector.css');
</script>


<script type="text/javascript">
    $time_out_handle = 0;
    $(document).ready(function () {






        mw.tools.loading(document.body, true);




        html_code_area_editor = CodeMirror.fromTextArea(document.getElementById("custom_html_code_mirror"), {
            lineNumbers: true,
            lineWrapping: true,

            indentWithTabs: true,
            mode: "text/html",
        //    mode : "htmlmixed",
            htmlMode: true,

            matchBrackets: true,
            extraKeys: {"Ctrl-Space": "autocomplete", "Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
            xxxxmode: {
                name: "htmlmixed",
                scriptTypes: [{matches: /\/x-handlebars-template|\/x-mustache/i,
                    mode: null},
                    {matches: /(text|application)\/(x-)?vb(a|script)/i,
                        mode: "vbscript"}]
            },
            foldGutter: true,
            gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
        });

         html_code_area_editor.setOption("theme", 'material');
        html_code_area_editor.setSize("100%", "100%");
        html_code_area_editor.on("change", function (cm, change) {
            var custom_html_code_mirror = document.getElementById("custom_html_code_mirror")
            custom_html_code_mirror.value = cm.getValue();
            window.clearTimeout($time_out_handle);
            $time_out_handle = window.setTimeout(function () {
                $(custom_html_code_mirror).change();
            }, 2000);
        });
        mw.tools.loading(false);


        /*mw.getScripts([
            '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.min.js',
            '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/css/css.min.js',
            '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/xml/xml.js',
            '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/javascript/javascript.js',
            '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/css/css.js',
            '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/vbscript/vbscript.js',
            '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/htmlmixed/htmlmixed.min.js',
            '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/php/php.min.js',
            '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/addon/display/autorefresh.js',
            '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/addon/selection/selection-pointer.js',
            '//cdnjs.cloudflare.com/ajax/libs/js-beautify/1.7.4/beautify.min.js',
            '//cdnjs.cloudflare.com/ajax/libs/js-beautify/1.7.4/beautify-css.min.js',
            '//cdnjs.cloudflare.com/ajax/libs/js-beautify/1.7.4/beautify-html.min.js'
            ], function(){




          });*/
    })


</script>
<script>

   function format_code() {
       html_code_area_editor.setSelection({
               'line':html_code_area_editor.firstLine(),
               'ch':0,
               'sticky':null
           },{
               'line':html_code_area_editor.lastLine(),
               'ch':0,
               'sticky':null
           },
           {scroll: false});
       //auto indent the selection
       html_code_area_editor.indentSelection("smart");

       html_code_area_editor.setSelection({
               'line':html_code_area_editor.firstLine(),
               'ch':0,
               'sticky':null
           },{
               'line':html_code_area_editor.firstLine(),
               'ch':0,
               'sticky':null
           },
           {scroll: false});



       //I tried to fire a mousdown event on the code to unselect everything but it does not work.
       //$('.CodeMirror-code', $codemirror).trigger('mousedown');
   }

</script>
<script>





    $(document).ready(function () {

        mw.html_editor.init();

    })


</script>

<div class="mw-ui-row">
<div class="mw-ui-btn-nav pull-right" id="save">

    <span onclick="format_code();" class="mw-ui-btn" ><?php _e('Format code'); ?></span>
    <span onclick="mw.html_editor.apply();" class="mw-ui-btn mw-ui-btn-invert" ><?php _e('Update'); ?></span>

<?php

/*    <span onclick="mw.html_editor.apply_and_save();" class="mw-ui-btn mw-ui-btn-invert"><?php _e('Update'); ?> <?php _e('and'); ?> <?php _e('save'); ?></span>
*/

?>
</div>
</div>
<hr>

<div class="mw-ui-row">
  <div class="mw-ui-col" style="width: 200px;">
    <div class="mw-ui-col-container">
      <div class="mw-ui-box">
        <div class="mw-ui-box-header">
          <span class="mw-icon-gear"></span><span><?php _e('Sections'); ?></span>
        </div>
        <div class="mw-ui-box-content selector-box"><div id="select_edit_field_wrap"></div></div>
      </div>
    </div>
  </div>
    <div class="mw-ui-col">
      <div class="mw-ui-col-container">
        <textarea class="mw-ui-field w100" name="custom_html" id="custom_html_code_mirror" rows="30"
            option-group="template" placeholder="<?php _e('Type your HTML code here'); ?>"></textarea>
      </div>
  </div>
</div>



