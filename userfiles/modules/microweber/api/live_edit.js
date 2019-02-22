mw.live_edit = mw.live_edit || {};

mw.live_edit.registry = mw.live_edit.registry || {};





mw.live_edit.showSettings = function (a, opts) {






    var liveedit =     opts.liveedit || false;
    var mode =     opts.mode ||  'modal';

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

            a = mod_sel[0]

        }
    }

    var curr = a || $("#mw_handle_module").data("curr");
    if(!curr){
        return;
    }
    if(typeof(curr) == 'undefined'){
        return;
    }
    var attributes = {};
    if (curr && curr.id && mw.$('#module-settings-' + curr.id).length > 0) {
        var m = mw.$('#module-settings-' + curr.id)[0];
        m.scrollIntoView();
        mw.tools.highlight(m);
    }
    if (curr && curr.attributes) {
        $.each(curr.attributes, function (index, attr) {
            attributes[attr.name] = attr.value;
        });
    }

    var iframe_id_sidebar = 'js-iframe-module-settings-' + curr.id;
    var iframe_id_sidebar_wrapper_id = 'sidebar-frame-wrapper-' + iframe_id_sidebar;

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
    //data1.live_edit_sidebar = true;

    var src = mw.settings.site_url + "api/module?" + json2url(data1);




    /*if(!mw.settings.live_edit_open_module_settings_in_sidebar){

    // //close sidebar
    // if(mw.liveEditSettings && mw.liveEditSettings.active){
    //      //mw.liveEditSettings.hide();
    // }


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



    }*/




    if (self != top || /*!mw.liveEditSettings.active || */ mode === 'modal') {
        //remove from sidebar
        $("#" + iframe_id_sidebar).remove();

        //close sidebar
        if(mw.liveEditSettings && mw.liveEditSettings.active){
             mw.liveEditSettings.hide();
        }

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


        if(!mw.liveEditSettings.active){
            mw.liveEditSettings.show();
        }

        if(typeof(mw.sidebarSettingsTabs) != 'undefined'){
            mw.sidebarSettingsTabs.set(2);
        }

        $('.mw-live-edit-component-options')
            .hide()
            .filter('#js-live-edit-module-settings-holder')
            .show();



        //
        //
        // if(typeof(mw.sidebarSettingsTabs) != 'undefined'){
        //     mw.sidebarSettingsTabs.set(2);
        // }




        data1.live_edit_sidebar = true;

        var src = mw.settings.site_url + "api/module?" + json2url(data1);


        var mod_settings_iframe_html_fr = '' +
            '<div class="js-module-settings-edit-item-group-frame loading" id="' + iframe_id_sidebar_wrapper_id + '">' +
            '<iframe src="' + src + '" frameborder="0" onload="this.parentNode.classList.remove(\'loading\')">' +
            '</div>';


        var sidebar_title_box = mw.live_edit.getModuleTitleBar(module_type, curr.id);



         var mod_settings_iframe_html = '<div  id="' + iframe_id_sidebar + '" class="js-module-settings-edit-item-group">'
            + sidebar_title_box
            + mod_settings_iframe_html_fr
            + '</div>';


        if (!$("#" + iframe_id_sidebar).length) {
            $("#js-live-edit-module-settings-items").append(mod_settings_iframe_html);
        }

        if ($("#" + iframe_id_sidebar).length) {
            $('.js-module-settings-edit-item-group').hide();
            $("#" + iframe_id_sidebar).attr('data-settings-for-module', curr.id);

            $("#" + iframe_id_sidebar).show();
        }
    }

}




mw.live_edit.getModuleTitleBar = function (module_type, module_id) {

    var mod_icon = mw.live_edit.getModuleIcon(module_type);
    var mod_title = mw.live_edit.getModuleTitle(module_type);
    var mod_descr = mw.live_edit.getModuleDescription(module_type);

    var sidebar_title_box = "<div class='mw_module_settings_sidebar_title_wrapper js-module-titlebar-"+module_id+"'>" + mod_icon;
    sidebar_title_box = sidebar_title_box + "<div class='js-module-sidebar-settings-menu-holder'>" + "</div>";
    sidebar_title_box = sidebar_title_box + "<div class='mw_module_settings_sidebar_title'>" + mod_title + "</div>";

    if (mod_title != mod_descr) {
        //  sidebar_title_box = sidebar_title_box + "<div class='mw_module_settings_sidebar_description'>" + mod_descr + "</div>";
    }
    sidebar_title_box = sidebar_title_box + "</div>";
    return sidebar_title_box;
}

mw.live_edit.getModuleIcon = function (module_type) {
    if (mw.live_edit.registry[module_type] && mw.live_edit.registry[module_type].icon) {
        return '<span class="mw_module_settings_sidebar_icon" style="background-image: url(' + mw.live_edit.registry[module_type].icon + ')"></span>';
    }
    else {
        return '<span class="mw-icon-gear"></span>&nbsp;&nbsp;';
    }
};
mw.live_edit.getModuleTitle = function (module_type) {
    if (mw.live_edit.registry[module_type] && mw.live_edit.registry[module_type].title) {
        return mw.live_edit.registry[module_type].title;
    } else if (mw.live_edit.registry[module_type] && mw.live_edit.registry[module_type].name) {
        return mw.live_edit.registry[module_type].name;
    }
    else {
        return '';
    }
};
mw.live_edit.getModuleDescription = function (module_type) {
    if (mw.live_edit.registry[module_type] && typeof(mw.live_edit.registry[module_type].description) != 'undefined') {
        return mw.live_edit.registry[module_type].description;
    }
    else {
        return '';
    }
};


