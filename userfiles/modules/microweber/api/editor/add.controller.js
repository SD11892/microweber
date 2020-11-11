
/*************************************************************
 *
        MWEditor.addController(
            'underline',
            function () {

            }, function () {

            }
        );

        MWEditor.addController({
            name: 'underline',
            render: function () {

            },
            checkSelection: function () {

            }
        })

 **************************************************************/

MWEditor.controllers.insertEmailVariable = function (scope, api, rootScope) {
    this.checkSelection = function (opt) {
        opt.controller.element.disabled = !opt.api.isSelectionEditable();
    };
    this.render = function () {
        var dropdown = new MWEditor.core.dropdown({
            data: [
                { label: 'User Name', value: '{user_name}' },
                { label: '...', value: '{...}' },
            ],
            placeholder: rootScope.lang('Insert variable')
        });
        dropdown.select.on('change', function (e, val) {
            api.insertHTML(val.value);
        });
        return dropdown.root;
    };
    this.element = this.render();
};

MWEditor.controllers.editSource = function (scope, api, rootScope) {
    this.render = function () {

        var scope = this;
        var el = MWEditor.core.button({
            props: {
                className: 'mdi mdi-xml',
                tooltip:  'Edit source'
            }
        });
        el.on('mousedown touchstart', function (e) {

            var ok = mw.element('<span class="mw-ui-btn mw-ui-btn-info">'+mw.lang('OK')+'</span>');
            var cancel = mw.element('<span class="mw-ui-btn">'+mw.lang('Cancel')+'</span>');
            var area = mw.element({ tag: 'textarea', props: {
                    className: 'mw-ui-field',
                }});
            area.css({
                height: 400
            })
            area.val(rootScope.$editArea.html());
            var footer = mw.element();
            footer.append(cancel).append(ok)
            var dialog = mw.dialog({
                overlay: true,
                content: area.get(0),
                footer: footer.get(0),
                title: mw.lang('Edit source')
            });

            cancel.on('click', function (){
                dialog.remove()
            });
            ok.on('click', function (){
                rootScope.setContent(area.val(), true);
                dialog.remove();
            });

        });
        return el;
    };
    this.checkSelection = function () {
        return true;
    };
};


MWEditor.addController = function (name, render, checkSelection, dependencies) {
    if (MWEditor.controllers[name]) {
        console.warn(name + ' already defined');
        return;
    }
    if (typeof name === 'object') {
        var obj = name;
        name = obj.name;
        render = obj.render;
        checkSelection = obj.checkSelection;
        dependencies = obj.dependencies;
    }
    if(MWEditor.controllers[name]) {
        console.warn(name + ' controller is already registered in the editor');
        return;
    }
    MWEditor.controllers[name] = function () {
        this.render = render;
        if(checkSelection) {
            this.checkSelection = checkSelection;
        }
        this.element = this.render();
        this.dependencies = dependencies;
    };
};


MWEditor.addInteractionController = function (name, render, interact, dependencies) {
    if (MWEditor.controllers[name]) {
        console.warn(name + ' already defined');
        return;
    }
    if (typeof name === 'object') {
        var obj = name;
        name = obj.name;
        render = obj.render;
        interact = obj.interact;
        dependencies = obj.dependencies;
    }
    if(MWEditor.interactionControls[name]) {
        console.warn(name + ' controller is already registered in the editor')
        return;
    }
    MWEditor.interactionControls[name] = function () {
        this.render = render;
        if(interact) {
            this.interact = interact;
        }
        this.element = this.render();
        this.dependencies = dependencies;
    };
};
