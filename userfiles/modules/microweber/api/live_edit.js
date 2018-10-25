mw.live_edit = mw.live_edit || {}

mw.live_edit.registry = mw.live_edit.registry || {}


mw.live_edit.showHandle = function (element) {
    var el = $(element);
    var title = el.dataset("mw-title");
    var id = el.attr("id");
    var module_type = el.dataset("type") || el.attr("type");

    var mod_icon = mw.live_edit.getModuleIcon(module_type);

    title = mod_icon + (title ? title : mw.msg.settings);

    mw.$(".mw-element-name-handle", mw.handle_module).html(title);


    var mw_edit_settings_multiple_holder_id = 'mw_edit_settings_multiple_holder-' + id;


    mw.$(".mw_edit_settings_multiple_holder:visible", mw.handle_module).not("#" + mw_edit_settings_multiple_holder_id).hide();
    if (mw.live_edit_module_settings_array && mw.live_edit_module_settings_array[module_type]) {

        mw.$(".mw_edit_settings", mw.handle_module).hide();

        if (document.getElementsByTagName('iframe').length > 90) {
            mw.$(".mw_edit_settings_multiple_holder").remove();
        }

        if (mw.$('#' + mw_edit_settings_multiple_holder_id).length == 0) {
            var new_el = mwd.createElement('div');
            new_el.className = 'mw_edit_settings_multiple_holder';
            new_el.id = mw_edit_settings_multiple_holder_id;
            $('.mw_edit_settings', mw.handle_module).after(new_el);

            // mw.$('#' + mw_edit_settings_multiple_holder_id).html(make_module_settings_handle_html);
            var settings = mw.live_edit_module_settings_array[module_type];


            mw.$(settings).each(function () {
                if (typeof(this.view) != 'undefined' && typeof(this.title) != 'undefined') {
                    var new_el = mwd.createElement('a');
                    new_el.className = 'mw_edit_settings_multiple';
                    new_el.title = this.title;
                    new_el.draggable = 'false';
                    var btn_id = 'mw_edit_settings_multiple_btn_' + mw.random();
                    new_el.id = btn_id;

                    if (typeof(this.type) != 'undefined' && (this.type) == 'tooltip') {
                        new_el.href = 'javascript:mw.drag.current_module_settings_tooltip_show_on_element("' + btn_id + '","' + this.view + '", "tooltip"); void(0);';

                    } else {
                        new_el.href = 'javascript:mw.drag.module_settings(undefined,"' + this.view + '"); void(0);';

                    }

                    var icon = '';
                    if (typeof(this.icon) != 'undefined') {
                        icon = '<i class="mw-edit-module-settings-tooltip-icon ' + this.icon + '"></i>'
                    }
                    new_el.innerHTML = '' +
                        icon +
                        '<span class="mw-edit-module-settings-tooltip-btn-title">' +
                        this.title +
                        '</span>' +
                        '';
                    mw.$('#' + mw_edit_settings_multiple_holder_id).append(new_el);
                }

            });
        }
        $('#' + mw_edit_settings_multiple_holder_id + ':hidden').show();
    } else {
        mw.$(".mw_edit_settings", mw.handle_module).show();

    }


    if (mod_icon) {
        var sorthandle_main = mw.$(".mw-element-name-handle", mw.handle_module).parent().parent();
        if (sorthandle_main) {
            mw.$(sorthandle_main).addClass('mw-element-name-handle-no-fallback-icon');

        }
    }

};


mw.live_edit.showSettings = function (a, opts) {


    var liveedit = opts.liveedit = opts.liveedit || false;

    var view = opts.view || 'admin';


    if (typeof a === 'string') {
        var module_type = a;
        var module_id = a;
        var mod_sel = mw.$(a + ':first');
        if (mod_sel.length > 0) {
            var attr = $(mod_sel).attr('id');
            if (typeof attr !== typeof undefined && attr !== false) {
                var attr = !attr.contains("#") ? attr : attr.replace("#", '');
                module_id = attr;
            }
            var attr2 = $(mod_sel).attr('type');
            var attr = $(mod_sel).attr('data-type');
            if (typeof attr !== typeof undefined && attr !== false) {
                module_type = attr;
            } else if (typeof attr2 !== typeof undefined && attr2 !== false) {
                module_type = attr2;
            }
        }
        var src = mw.settings.site_url + "api/module?id=" + module_id + "&live_edit=" + liveedit + "&module_settings=true&type=" + module_type;
        return mw.tools.modal.frame({
            url: src,
            width: 532,
            height: 250,
            name: 'module-settings-' + a.replace(/\//g, '_'),
            title: '',
            callback: function () {

            }
        });
    }

    var curr = a || $("#mw_handle_module").data("curr");

    var attributes = {};
    if (curr && curr.id && mw.$('#module-settings-' + curr.id).length > 0) {
        var m = mw.$('#module-settings-' + curr.id)[0];
        m.scrollIntoView();
        mw.tools.highlight(m);
        return false;
    }
    if (curr && curr.attributes) {
        $.each(curr.attributes, function (index, attr) {
            attributes[attr.name] = attr.value;
        });
    }

    var data1 = attributes;
    var module_type = null
    if (data1['data-type'] != undefined) {
        module_type = data1['data-type'];
        data1['data-type'] = data1['data-type'] + '/admin';
    }
    if (data1['data-module-name'] != undefined) {
        delete(data1['data-module-name']);
    }
    if (data1['type'] != undefined) {
        module_type = data1['type'];
        data1['type'] = data1['type'] + '/admin';
    }
    if (module_type != null && view != undefined) {
        data1['data-type'] = data1['type'] = module_type + '/' + view;
    }
    if (typeof data1['class'] != 'undefined') {
        delete(data1['class']);
    }
    if (typeof data1['style'] != 'undefined') {
        delete(data1['style']);
    }
    if (typeof data1.contenteditable != 'undefined') {
        delete(data1.contenteditable);
    }
    data1.live_edit = liveedit;
    data1.module_settings = 'true';
    if (view != undefined) {
        data1.view = view;
    }
    else {
        data1.view = 'admin';
    }
    if (data1.from_url == undefined) {
        //data1.from_url = window.top.location;
        data1.from_url = window.parent.location;
    }
    var modal_name = 'module-settings-' + curr.id;
    if (typeof(data1.view.hash) == 'function') {
        //var modal_name = 'module-settings-' + curr.id +(data1.view.hash());
    }
    var src = mw.settings.site_url + "api/module?" + json2url(data1);

    if (self != top || !mw.liveEditSettings.active || opts.mode === 'modal') {
        var modal = top.mw.tools.modal.frame({
            url: src,
            width: 532,
            height: 150,
            name: modal_name,
            title: '',
            callback: function () {
                $(this.container).attr('data-settings-for-module', curr.id);
            }
        });
        return modal;
    } else {


        data1.live_edit_sidebar = true;

        var src = mw.settings.site_url + "api/module?" + json2url(data1);


        var iframe_id = 'js-iframe-module-settings-' + curr.id;


        var mod_icon = mw.live_edit.getModuleIcon(module_type);
        var mod_title = mw.live_edit.getModuleTitle(module_type);
        var mod_descr = mw.live_edit.getModuleDescription(module_type);


        var mod_settings_iframe_html_fr = '<iframe src="' + src + '" class="js-module-settings-edit-item-group-frame" id="fr-' + iframe_id + '"  style="width:100%;height: 90vh;position: absolute" frameborder="0">';


        var sidebar_title_box = "<div class='mw_module_settings_sidebar_title_wrapper' >" + mod_icon ;
        var sidebar_title_box = sidebar_title_box + "<div class='mw_module_settings_sidebar_title'>" + mod_title + "</div>";

        if(mod_title != mod_descr){
          //  var sidebar_title_box = sidebar_title_box + "<div class='mw_module_settings_sidebar_description'>" + mod_descr + "</div>";
        }


        var sidebar_title_box = sidebar_title_box + "</div>";


        var mod_settings_iframe_html = '<div  id="' + iframe_id + '" class="js-module-settings-edit-item-group">'
            + sidebar_title_box
            + mod_settings_iframe_html_fr
            + '</div>';




      //  mod_settings_iframe_html =  sidebar_title_box + mod_settings_iframe_html ;





        if (!$("#" + iframe_id).length) {
            $("#js-live-edit-module-settings-items").append(mod_settings_iframe_html);
        }

        if ($("#" + iframe_id).length) {
            $('.js-module-settings-edit-item-group').hide();

            $("#" + iframe_id).show();
        }
    }

}


mw.live_edit.getModuleIcon = function (module_type) {
    if (mw.live_edit.registry[module_type] && typeof(mw.live_edit.registry[module_type].icon) != 'undefined' ) {
        return '<span class="mw_module_settings_sidebar_icon" style="background-image: url(' + mw.live_edit.registry[module_type].icon + ')"></span>';
    }
    else {
        return '<span class="mw-icon-gear"></span>&nbsp;&nbsp;'
    }
};
mw.live_edit.getModuleTitle = function (module_type) {
    if (mw.live_edit.registry[module_type] && typeof(mw.live_edit.registry[module_type].title) != 'undefined' ) {
        return mw.live_edit.registry[module_type].title
    } else  if (mw.live_edit.registry[module_type] && typeof(mw.live_edit.registry[module_type].name) != 'undefined' ) {
        return mw.live_edit.registry[module_type].name
    }
    else {
        return ''
    }
};
mw.live_edit.getModuleDescription = function (module_type) {


    if (mw.live_edit.registry[module_type] && typeof(mw.live_edit.registry[module_type].description) != 'undefined' ) {
        return mw.live_edit.registry[module_type].description
    }
    else {
        return ''
    }
};
