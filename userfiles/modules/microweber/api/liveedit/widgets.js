mw.liveedit = mw.liveedit || {};
mw.liveedit.widgets = {
    htmlEditorDialog: function () {
        var src = mw.settings.site_url + 'api/module?id=mw_global_html_editor&live_edit=true&module_settings=true&type=editor/code_editor&autosize=true';
        window.open(src, "Code editor", "toolbar=no, menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=yes");
    },
    cssEditorDialog: function () {
        var src = mw.settings.site_url + 'api/module?id=mw_global_css_editor&live_edit=true&module_settings=true&type=editor/css_editor&autosize=true';
        return mw.dialogIframe({
            url: src,
            // width: 500,
            height:'auto',
            autoHeight: true,
            name: 'mw-css-editor-front',
            title: 'CSS Editor',
            template: 'default',
            center: false,
            resize: true,
            draggable: true
        });
    },
    _iconEditor: null,
    _iconEditorTarget: null,
    iconEditor: function (target) {
        if(!target) return;
        this._iconEditorTarget = target;

        if(!this._iconEditor) {
            this._iconEditor = mw.icons.tooltip({element: mw.liveedit.widgets._iconEditorTarget, position: 'bottom-center', width:320}, true);

            this._iconEditor.$e.on('Result', function(e, res){
                res.render(res.icon, mw.liveedit.widgets._iconEditorTarget);
                mw.wysiwyg.change(mw.liveedit.widgets._iconEditorTarget);
            });
            this._iconEditor.$e.on('sizeChange', function(e, size){
                mw.liveedit.widgets._iconEditorTarget.style.fontSize = size + 'px';
                mw.tools.tooltip.setPosition(mw.liveedit.widgets._iconEditor.tooltip, mw.liveedit.widgets._iconEditorTarget, 'bottom-center');
                mw.wysiwyg.change(mw.liveedit.widgets._iconEditorTarget);
            });
            this._iconEditor.$e.on('colorChange', function(e, color){
                mw.liveedit.widgets._iconEditorTarget.style.color = color;
                mw.wysiwyg.change(mw.liveedit.widgets._iconEditorTarget);
            });
        }
        target.style.fontSize = getComputedStyle(target).fontSize;
        mw.$('.mw-field [type="number"]', mw.liveedit.widgets._iconEditor.content).val(parseFloat(target.style.fontSize))
        $(mw.liveedit.widgets._iconEditor.tooltip).show();
        mw.tools.tooltip.setPosition(this._iconEditor.tooltip, mw.liveedit.widgets._iconEditorTarget, 'bottom-center');
    }
};
