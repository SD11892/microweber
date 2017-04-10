mw.iconSelector = mw.iconSelector || {
    _string: '',
    _activeElement: null,

    iconFontClasses: [],

    init: function () {
        if (mw.iconSelector.iconFontClasses.length == 0) {
            try {
                var uicss = mwd.querySelector('link[href*="/ui.css"]'), icons;
                if(uicss === null){
                    var def = mwd.querySelector('link[href*="/default.css"]');
                    if(def !== null){
                        for(var i=0; i<def.sheet.cssRules.length; i++){
                            var item = def.sheet.cssRules[i];
                            if( item.cssText.indexOf('ui.css') != -1){
                                icons = item.styleSheet.rules;
                            }
                        }
                    }
                }
                else{
                    icons = uicss.sheet.cssRules;
                }

                var l = icons.length, i = 0, html = '';
                for (; i < l; i++) {
                    var sel = icons[i].selectorText;
                    if (!!sel && sel.indexOf('.mw-icon-') === 0) {
                        var cls = sel.replace(".", '').split(':')[0];
                        if (mw.iconSelector.iconFontClasses.indexOf(cls) === -1) {
                            mw.iconSelector.iconFontClasses.push(cls);
                        }
                    }
                }
            } catch (e) {
            }


            //check font awesome
            var faicons = mwd.querySelector('link[href*="/font-awesome.min.css"]');
            if (faicons != null && faicons.length == 0) {
                var faicons = mwd.querySelector('link[href*="/font-awesome.css"]');
            }

            if (faicons != null && faicons.length != 0 && typeof(faicons.sheet) != 'undefined' && typeof(faicons.sheet) != 'null') {
                try {
                    var icons = faicons.sheet.cssRules;
                    var l = icons.length, i = 0, html = '';
                    for (; i < l; i++) {
                        var sel = icons[i].selectorText;
                        if (!!sel && sel.indexOf('.fa-') === 0) {
                            var cls = sel.replace(".", '').split(':')[0];
                            if (mw.iconSelector.iconFontClasses.indexOf('fa ' + cls) === -1) {
                                mw.iconSelector.iconFontClasses.push('fa ' + cls);
                            }
                        }
                    }
                } catch (e) {
                }


            }

            //check semantic ui
            var faicons = mwd.querySelector('link[href*="/semantic.min.css"]');
            if (faicons != null && faicons.length == 0) {
                var faicons = mwd.querySelector('link[href*="/semantic.css"]');
            }

            if (faicons != null && faicons.length != 0 && typeof(faicons.sheet) != 'undefined' && typeof(faicons.sheet) != 'null') {
                try {
                    var icons = faicons.sheet.cssRules;

                    var l = icons.length, i = 0, html = '';
                    for (; i < l; i++) {
                        var sel = icons[i].selectorText;
                        if (!!sel && sel.indexOf('i.icon') === 0) {
                            var cls = sel.replace("i.", '').split(':')[0];

                            cls = cls.split('.').join(' ');


                            if (mw.iconSelector.iconFontClasses.indexOf(cls) === -1) {
                                mw.iconSelector.iconFontClasses.push(cls);
                            }
                        }
                    }
                } catch (e) {
                }
            }

        }


        try {
                var icons = mwd.querySelector('link[data-iconset]').sheet.cssRules;

                var l = icons.length, i = 0, html = '';
                for (; i < l; i++) {
                    var sel = icons[i].selectorText;
                    if (!!sel) {
                        var cls = sel.replace(".", '').split(':')[0];
                        if (mw.iconSelector.iconFontClasses.indexOf(cls) === -1) {
                            mw.iconSelector.iconFontClasses.push(cls);
                        }
                    }
                }
            } catch (e) {
            }

    },

    popup: function () {

        if (mw.iconSelector.iconFontClasses.length == 0) {
            mw.iconSelector.init();
        }

        if (mw.iconSelector.iconFontClasses.length == 0) {
            // if no icon sets, disable the icon editor
            return;
        }


        if (mw.iconSelector._string == '') {


            var uicss = mw.iconSelector.iconFontClasses;
            var l = uicss.length, i = 0, html = '';
            for (; i < l; i++) {
                var sel = uicss[i];
                html += '<li onclick="mw.iconSelector.select(\'' + sel + '\')" title="' + sel + '"><i class="' + sel + '"></i></li>';

            }

            mw.iconSelector._string = '<ul class="mw-icon-selector">' + html + '</ul>';
            mw.iconSelector._string += '<input class="mw-icon-selector-set-icon-size" type="range" name="mw-icon-selector-set-icon-size"  min="10" max="120" onchange="mw.iconSelector.set_icon_size(this.value)"  />';
            mw.iconSelectorToolTip = mw.tooltip({
                content: mw.iconSelector._string,
                element: mw.iconSelector._activeElement,
                position: 'top-center'
            });


        }
        else {
            $(mw.iconSelectorToolTip).show();
            //if(toggle != true){
            //
            //
            //} else if (toggle == true){
            //    $(mw.iconSelectorToolTip).toggle();
            //}

            mw.tools.tooltip.setPosition(mw.iconSelectorToolTip, mw.iconSelector._activeElement, 'top-center');
        }
        var icons_size_val = $(mw.iconSelector._activeElement).css("fontSize");
        var a = parseInt(icons_size_val);

        if (a > 0) {
            $('.mw-icon-selector-set-icon-size').val(a);
        }



    },
    select: function (icon) {
        if (mw.iconSelector._activeElement !== null && typeof mw.iconSelector._activeElement !== 'undefined') {
            mw.tools.removeClass(mw.iconSelector._activeElement, mw.iconSelector.iconFontClasses);
            mw.wysiwyg.elementRemoveFontIconClasses(mw.iconSelector._activeElement);
            mw.tools.classNamespaceDelete(mw.iconSelector._activeElement, 'mw-icon-');
            mw.$(mw.iconSelector._activeElement).addClass(icon + ' mw-wysiwyg-custom-icon ');


            if(typeof(mw.iconSelector._activeElement) != 'undefined' && typeof(mw.iconSelector._activeElement.nodeName) != 'undefined'){
                if(mw.iconSelector._activeElement.nodeName == "INPUT"){
                    $(mw.iconSelector._activeElement).val(icon).trigger( "change");
                }
            }

        }
        $(mw.tools.firstParentWithClass(mw.iconSelector._activeElement, 'edit')).addClass('changed');
        mw.iconSelector._activeElement = null;


        $(mw.iconSelectorToolTip).hide();
    },
    hide: function () {
        if (mw.iconSelector._string != '') {
            $(mw.iconSelectorToolTip).hide();
        }
    },
    iconDropdown:function(selector, options){
        var el = $(selector)[0];
        if(!el) return;
        options = options || {}
        if (mw.iconSelector.iconFontClasses.length == 0) {
            mw.iconSelector.init();
        }

        if (mw.iconSelector.iconFontClasses.length == 0) {
            // if no icon sets, disable the icon editor
            return;
        }
        var uicss = mw.iconSelector.iconFontClasses;
        var l = uicss.length, i = 0, html = '';
        for (; i < l; i++) {
            var sel = uicss[i];
            html += '<li data-value="'+sel+'"><i class="' + sel + '"></i></li>';

        }

        html = '<ul class="mw-icon-selector mw-icon-selector-dropdown">' + html + '</ul>';

        var input = document.createElement('input');
        input.className = options.className || 'mw-ui-field';

        $(selector).addClass('mw-icon-selector-dropdown-wrapper').append(input).append(html)

        $(input).on('focus', function(){
            $(this).parent().addClass('focused')
        });
         $(input).on('input', function(){
             var val = $.trim(this.value);
            if(!this.value){
                $('.mw-icon-selector li', el).show()
            }
            else{
                $('.mw-icon-selector li', el).hide().filter('[data-value*="'+val+'"]').show()
            }
        });
        el.__time = null;
        $(input).on('blur', function(){
            (function(el){
                clearTimeout(el.__time)
                el.__time = setTimeout(function(){
                    $(el).parent().removeClass('focused')
                }, 200)
            })(this)
        });
        $('.mw-icon-selector li', el).on('mousedown touchstart', function(){
             $(input).val($(this).attr('data-value')).trigger('change');
             if(typeof options.onchange == 'function'){
                 options.onchange.call(undefined, input.value)
             }
        });



    },
    set_icon_size: function (val) {

        var a = parseInt(val);

        if (a > 5) {
            $(mw.iconSelector._activeElement).css("fontSize", a + "px");
        } else {
            $(mw.iconSelector._activeElement).css("fontSize", "inherit");
        }


    }
}