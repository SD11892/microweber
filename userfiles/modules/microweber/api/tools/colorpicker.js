mw._colorPickerDefaults = {
    skin: 'mw-tooltip-default',
    position: 'bottom-center',
    onchange: false
}
mw._colorPicker = function (options) {
    if (!mw.tools.colorPickerColors) {
        mw.tools.colorPickerColors = [];
        var w = window;
        if(self != top){
            w = top;
        }
        var colorpicker_els = w.mw.$("body *");
        if(typeof colorpicker_els != 'undefined' && colorpicker_els.length > 0){
            colorpicker_els.each(function () {
                var css = parent.getComputedStyle(this, null);
                if (css !== null) {
                    if (mw.tools.colorPickerColors.indexOf(css.color) === -1) {
                        mw.tools.colorPickerColors.push(mw.color.rgbToHex(css.color))
                    }
                    if (mw.tools.colorPickerColors.indexOf(css.backgroundColor) === -1) {
                        mw.tools.colorPickerColors.push(mw.color.rgbToHex(css.backgroundColor))
                    }
                }
            });
        }

    }
    var proto = this;
    if (!options) {
        return false;
    }
    var settings = $.extend({}, mw._colorPickerDefaults, options);
    if (settings.element === undefined || settings.element === null) {
        return false;
    }

    var $el = mw.$(settings.element);
    if ($el[0] === undefined) {
        return false;
    }
    this.element = $el[0];
    if ($el[0].mwToolTipBinded !== undefined) {
        return false;
    }
    if (!settings.method) {
        if (this.element.nodeName == 'DIV') {
            settings.method = 'inline';
        }
    }
    this.settings = settings;
    $el[0].mwToolTipBinded = true;

    var sett = {
        showAlpha: true,
        showHSL: false,
        showRGB: false,
        showHEX: true,
        palette: mw.tools.colorPickerColors
    };

    if(settings.value) {
        sett.color = settings.value
    }
    if(typeof settings.showRGB !== 'undefined') {
        sett.showRGB = settings.showRGB
    }
    if(typeof settings.showHEX !== 'undefined') {
        sett.showHEX = settings.showHEX
    }

    if(typeof settings.showHSL !== 'undefined') {
        sett.showHSL = settings.showHSL
    }

    var frame;
    if (settings.method == 'inline') {

        sett.attachTo = $el[0];

        frame = AColorPicker.createPicker(sett);
        frame.onchange = function (data) {

            if (proto.settings.onchange) {
                proto.settings.onchange(data.color);
            }

            if ($el[0].nodeName == 'INPUT') {
                var val = val == 'transparent' ? val : '#' + val;
                $el.val(val);
            }
        }

    }
    else {

        var tip = mw.tooltip(settings), $tip = mw.$(tip).hide();
        this.tip = tip;

        mw.$('.mw-tooltip-content', tip).empty();
        sett.attachTo = mw.$('.mw-tooltip-content', tip)[0]

        frame = AColorPicker.createPicker(sett);

        frame.onchange = function (data) {

            if (proto.settings.onchange) {
                proto.settings.onchange(data.color);
            }

            if ($el[0].nodeName === 'INPUT') {
                $el.val(data.color);
            }
        };

        if ($el[0].nodeName === 'INPUT') {
            $el.on('focus', function (e) {
                if(this.value){
                    frame.color = this.value;
                }
                mw.$(tip).show();

                mw.tools.tooltip.setPosition(tip, $el[0], settings.position)
            });
        }
        else {
            $el.on('click', function (e) {
                mw.$(tip).toggle();
                mw.tools.tooltip.setPosition(tip, $el[0], settings.position)
            });
        }
        var documents = [document];
        if (self !== top){
            documents.push(top.document);
        }
        mw.$(documents).on('click', function (e) {
            if (!mw.tools.hasParentsWithClass(e.target, 'mw-tooltip') && e.target !== $el[0]) {
                mw.$(tip).hide();
            }
        });
        if ($el[0].nodeName === 'INPUT') {
            $el.bind('blur', function () {
                //$(tip).hide();
            });
        }
    }
    if (this.tip) {
        this.show = function () {
            mw.$(this.tip).show();
            mw.tools.tooltip.setPosition(this.tip, this.settings.element, this.settings.position)
        };
        this.hide = function () {
            mw.$(this.tip).hide()
        };
        this.toggle = function () {
            var tip = mw.$(this.tip);
            if (tip.is(':visible')) {
                this.hide()
            }
            else {
                $el.focus();
                this.show()
            }
        }
    }

};
mw.colorPicker = mw.colourPicker = function (o) {
    return new mw._colorPicker(o);
};