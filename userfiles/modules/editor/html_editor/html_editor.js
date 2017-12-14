


html_editor_parent_document = window.opener || top


mw.html_editor = {};
mw.html_editor.map = {};
mw.html_editor.init = function () {

    var fields = mw.html_editor.get_edit_fields(true);
    mw.html_editor.build_dropdown(fields);
    mw.html_editor.populate_editor();

};


mw.html_editor.get_edit_fields = function (also_in_modules) {

    also_in_modules = typeof also_in_modules === 'undefined' ? false : also_in_modules;


    var fields_arr = [];
    var get_edit_fields = html_editor_parent_document.$('.edit').each(function () {
        var is_in_module = html_editor_parent_document.mw.tools.firstParentWithClass(this, 'module');
        if (!is_in_module || also_in_modules) {
            fields_arr.push(this);
        }
    });
    return fields_arr;
};

mw.html_editor.build_dropdown = function (fields_array) {
    var html_dd = {};
    $(fields_array).each(function () {
        var dd_grp = $(this).attr('rel');
        var dd_field = $(this).attr('field');
        if (dd_grp && dd_grp) {
            if (typeof(html_dd[dd_grp]) == 'undefined') {
                html_dd[dd_grp] = [];
            }
            var temp = {};
            temp.field = dd_field;
            temp.rel = dd_grp;
            temp.el = this;

            mw.html_editor.map[dd_grp + '/' + dd_field] = temp;
            html_dd[dd_grp].push(temp);
        }

    });


    var $select = $("<select>");
    $select.attr('id', 'select_edit_field');
    $select.attr('class', 'mw-ui-field');

    var has_selected = false;

    $select.appendTo("#select_edit_field_wrap");
    $.each(html_dd, function (groupName, options) {
        var $optgroup = $("<optgroup>", {label: groupName, rel: groupName});
        $optgroup.appendTo($select);
        $.each(options, function (j, option) {

            if (!option.field) {
                mw.log('Warning: Your editable region does not have a "field" attribute');
                mw.log(option);

            } else {


                var $option = $("<option>", {
                    text: option.field,
                    value: option.rel,
                    rel: option.rel,
                    field: option.field
                });

                if(!has_selected && option.rel == 'content'){
                    has_selected = true;
                     $($option).attr('selected', 'selected');

                }



                $option.appendTo($optgroup);
            }

        });
    });



    $($select).on('change',function(){
        mw.html_editor.populate_editor()
    });

};


mw.html_editor.populate_editor = function () {
    var value = $('select#select_edit_field option:selected');

    if (value.length == 0) {
        var value = $('select#select_edit_field option:first');
    }
    if (value.length == 0) {
        return;
    }

    $('#fragment-holder').remove();
    var ed_val = '';
    var dd_grp = value.attr('rel');
    var dd_field = value.attr('field');

    if (typeof(mw.html_editor.map[dd_grp + '/' + dd_field]) != 'undefined') {


        var ed_val = $(mw.html_editor.map[dd_grp + '/' + dd_field].el).html();


        html_editor_parent_document.mw.tools.scrollTo('[field="'+dd_field+'"][rel="'+dd_grp+'"]')

        html_editor_parent_document.$('.html-editor-selcted').removeClass('html-editor-selcted');
        html_editor_parent_document.$('[field="'+dd_field+'"][rel="'+dd_grp+'"]').addClass('html-editor-selcted');

        var frag = document.createDocumentFragment();
        var html = ed_val;
        var holder = document.createElement("div")
        holder.id = 'fragment-holder'
        holder.innerHTML = html
        frag.appendChild(holder)
        //var s = $('.module', $(frag)).html('[module]');
         var s = $('.module', $(frag)).html('[module]');

       // $('#divHtml').find("*").removeClass();



        var ed_val = $(holder).html();
    //    d(ed_val);
    } else {
        var ed_val = 'Select element to edit';
    }

    if (typeof html_code_area_editor != 'undefined') {

        if (typeof html_beautify != 'undefined') {
            //var formattedXML = js_beautify.beautify_html(ed_val, { indent_size: 2 });
            var formattedXML = (html_beautify(ed_val, {
                "indent_size": 4,
                "indent_char": "",
                "indent_with_tabs": false,
                "eol": "\n",
                "end_with_newline": true,
                "indent_level": 100,
                "preserve_newlines": false,
                "max_preserve_newlines": 100,
                "space_in_paren": false,
                "space_in_empty_paren": false,
                "jslint_happy": false,
                "space_after_anon_function": false,
                "brace_style": "none",
                "unindent_chained_methods": true,
                "break_chained_methods": false,
                "keep_array_indentation": false,
                "unescape_strings": true,
                 "wrap_line_length": 111110,
                "wrap_attributes": 'none',
                "e4x": false,
                "comma_first": false,
                "operator_position": "before-newline"
            }));
            ed_val = formattedXML;
       //     d(formattedXML);
        }



      //  d(ed_val);

        html_code_area_editor.setValue(ed_val);
        html_code_area_editor.refresh();
    }

    $('#custom_html_code_mirror').val(ed_val);
    $('#custom_html_code_mirror').attr('current', dd_grp + '/' + dd_field);


};

mw.html_editor.apply = function () {
    var cur = $('#custom_html_code_mirror').attr('current');
    var html = $('#custom_html_code_mirror').val();
    if (typeof(mw.html_editor.map[cur]) != 'undefined') {

        var el = mw.html_editor.map[cur].el;
        $(el).html(html);

        if ($(el).hasClass('edit')) {
            var master_edit_field_holder = el;

        } else {
            var master_edit_field_holder = html_editor_parent_document.mw.tools.firstParentWithClass(el, 'edit');

        }


        var modules_ids = {};
        var modules_list = $('.module', $(el));
        $(modules_list).each(function () {
            var id = $(this).attr('id');
            if (id) {
                id = '#' + id;
            } else {
                var id = $(this).attr('data-type');
            }
            if (!id) {
                var id = $(this).attr('type');
            }
            modules_ids[id] = true;
        });


        $.each(modules_ids, function (index, value) {
            html_editor_parent_document.mw.reload_module(index);
        });


        if (master_edit_field_holder) {
            $(master_edit_field_holder).addClass("changed");
            setTimeout(function () {
                html_editor_parent_document.mw.drag.fix_placeholders(true);
                html_editor_parent_document.mw.resizable_columns();
                html_editor_parent_document.mw.on.DOMChangePause = false;

            }, 200);
        }

        mw.html_editor.populate_editor();
    }

}


mw.html_editor.reset_content = function () {

    var value = $('select#select_edit_field option:selected');

    if (value.length == 0) {
        var value = $('select#select_edit_field option:first');
    }
    if (value.length == 0) {
        return;
    }
    var field = value.attr('field');
    var rel = value.attr('rel');
    var cur = rel + '/' + field;

//     mw.tools.confirm("Are you sure you want to Reset the content of this element?", function () {
//         var obj = {
//             rel: rel,
//             field: field
//         }
//         $.post(mw.settings.site_url + "api/content/reset_edit", obj, function (data) {
//
//             if (typeof(mw.edit_content) == 'object') {
//                 mw.edit_content.load_editor()
//             }
//
// mw.drag.save()
//             typeof callback === 'function' ? callback.call(data) : '';
//         });
//     });
//
//
//
    var html = '';

    if (typeof(mw.html_editor.map[cur]) != 'undefined') {


        var el = mw.html_editor.map[cur].el;
        if(field == 'title'){
           // var old_var =  $(el).html();
            var html = 'Untitled content';

        }

        $(el).html(html);

        if ($(el).hasClass('edit')) {
            var master_edit_field_holder = el;

        } else {
            var master_edit_field_holder = html_editor_parent_document.mw.tools.firstParentWithClass(el, 'edit');

        }



        if (master_edit_field_holder) {
            $(master_edit_field_holder).addClass("changed");
            html_editor_parent_document.mw.on.DOMChangePause = true;
            setTimeout(function () {
                html_editor_parent_document.mw.drag.fix_placeholders(true);
                html_editor_parent_document.mw.resizable_columns();
                html_editor_parent_document.mw.on.DOMChangePause = false;
                var saved = html_editor_parent_document.mw.drag.save();
                saved.success(function (saved_data) {

                    if (typeof saved_data[0] == 'undefined') {
                        return;
                    }
                    saved_data = saved_data[0];

                    html_editor_parent_document.window.location.reload();


                 /*   var get_edit_field = {};
                    if (typeof saved_data.rel_type != 'undefined') {
                        get_edit_field.rel_type = saved_data.rel_type;
                    }
                    if (typeof saved_data.rel_id != 'undefined') {
                        get_edit_field.rel_id = saved_data.rel_id;
                    }
                    if (typeof saved_data.field != 'undefined') {
                        get_edit_field.field = saved_data.field;
                    }

                    $.ajax({
                        type: 'POST',
                        url: mw.settings.site_url + "api/get_content_field",
                        data: get_edit_field,
                        dataType: "json",
                        success: function (data) {
                            mw.history.apply_fields(data)
                        }
                    })*/

                })


                //
                // if ($id != undefined) {
                //     $.ajax({
                //         type: 'POST',
                //         url: mw.settings.site_url + "api/get_content_field_draft",
                //         data: {
                //             id: $id
                //         },
                //         dataType: "json",
                //         success: function (data) {
                //             mw.history.apply_fields(data)
                //         }
                //     })
                // }


            }, 200);
        }
    }


}