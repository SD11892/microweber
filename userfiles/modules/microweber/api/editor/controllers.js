MWEditor.controllers = {
    alignLeft: function (scope, api, rootScope) {
        var el;
        this.render = function () {
            var scope = this;
            el = MWEditor.core.button({
                props: {
                    tooltip: rootScope.lang('Align left'),
                    innerHTML: '<svg  viewBox="0 0 24 24"><path fill="currentColor" d="M3,3H21V5H3V3M3,7H15V9H3V7M3,11H21V13H3V11M3,15H15V17H3V15M3,19H21V21H3V19Z" /></svg>'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('justifyLeft');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element, !opt.api.isSelectionEditable()|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)))

            rootScope.controllerActive(el.get(0), 'left' === opt.css.alignNormalize());
        };
        this.element = this.render();
    },
    alignCenter: function (scope, api, rootScope) {
        var el
        this.render = function () {
            var scope = this;
            el = MWEditor.core.button({
                props: {
                    tooltip: rootScope.lang('Align center'),
                    innerHTML: '<svg  viewBox="0 0 24 24"><path fill="currentColor" d="M3,3H21V5H3V3M7,7H17V9H7V7M3,11H21V13H3V11M7,15H17V17H7V15M3,19H21V21H3V19Z" /></svg>'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('justifyCenter');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element, !opt.api.isSelectionEditable()|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)))

            rootScope.controllerActive(el.get(0), 'center' === opt.css.alignNormalize());
        };
        this.element = this.render();
    },
    alignRight: function (scope, api, rootScope) {
        var el
        this.render = function () {
            var scope = this;
            el = MWEditor.core.button({
                props: {
                    tooltip: rootScope.lang('Align center'),
                    innerHTML: '<svg  viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M3,3H21V5H3V3M9,7H21V9H9V7M3,11H21V13H3V11M9,15H21V17H9V15M3,19H21V21H3V19Z" />\n' +
                        '</svg>'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('justifyRight');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element, !opt.api.isSelectionEditable()|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)))

            rootScope.controllerActive(el.get(0), 'right' === opt.css.alignNormalize());
        };
        this.element = this.render();
    },
    alignJustify: function (scope, api, rootScope) {
        var el;
        this.render = function () {
            var scope = this;
            el = MWEditor.core.button({
                props: {
                    tooltip: rootScope.lang('Justify'),
                    innerHTML: '<svg   viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M3,3H21V5H3V3M3,7H21V9H3V7M3,11H21V13H3V11M3,15H21V17H3V15M3,19H21V21H3V19Z" />\n' +
                        '</svg>'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('justifyFull');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element, !opt.api.isSelectionEditable()|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)))

            rootScope.controllerActive(el.get(0), 'justify' === opt.css.alignNormalize());
        };
        this.element = this.render();
    },
    align: function (scope, api, rootScope) {
        this.root = MWEditor.core.element();
        this.root.$node.addClass('mw-editor-state-component mw-editor-state-component-align');
        this.buttons = [];

        var arr = [
            {align: 'left', icon: '<svg  viewBox="0 0 24 24"><path fill="currentColor" d="M3,3H21V5H3V3M3,7H15V9H3V7M3,11H21V13H3V11M3,15H15V17H3V15M3,19H21V21H3V19Z" /></svg>', action: 'justifyLeft'},
            {align: 'center', icon: '<svg  viewBox="0 0 24 24">\n' +
                    '    <path fill="currentColor" d="M3,3H21V5H3V3M7,7H17V9H7V7M3,11H21V13H3V11M7,15H17V17H7V15M3,19H21V21H3V19Z" />\n' +
                    '</svg>', action: 'justifyCenter'},
            {align: 'right', icon: '<svg  viewBox="0 0 24 24">\n' +
                    '    <path fill="currentColor" d="M3,3H21V5H3V3M9,7H21V9H9V7M3,11H21V13H3V11M9,15H21V17H9V15M3,19H21V21H3V19Z" />\n' +
                    '</svg>', action: 'justifyRight'},
            {align: 'justify', icon: '<svg   viewBox="0 0 24 24">\n' +
                    '    <path fill="currentColor" d="M3,3H21V5H3V3M3,7H21V9H3V7M3,11H21V13H3V11M3,15H21V17H3V15M3,19H21V21H3V19Z" />\n' +
                    '</svg>', action: 'justifyFull'}
        ];
        this.render = function () {
            var scope = this;
            arr.forEach(function (item) {
                var el = MWEditor.core.button({
                    props: {
                        innerHTML:  item.icon
                    }
                });
                el.on('mousedown touchstart', function (e) {
                    api.execCommand(item.action);
                });
                scope.root.append(el);
                scope.buttons.push(el);
            });
            return scope.root;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element, !opt.api.isSelectionEditable()|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)))

            var align = opt.css.alignNormalize();
            for (var i = 0; i< this.buttons.length; i++) {
                var state = arr[i].align === align;
                rootScope.controllerActive(this.buttons[i].get(0), state);
            }
        };
        this.element = this.render();
    },
    bold: function (scope, api, rootScope) {
        this.render = function () {
            var scope = this;
            var el = MWEditor.core.button({
                props: {

                    tooltip: rootScope.lang('Bold'),
                    innerHTML: '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">\n' +
                        '<path d="m13.5 15.5h-3.5v-3h3.5a1.5 1.5 0 0 1 1.5 1.5 1.5 1.5 0 0 1-1.5 1.5m-3.5-9h3a1.5 1.5 0 0 1 1.5 1.5 1.5 1.5 0 0 1-1.5 1.5h-3m5.6 1.29c0.97-0.68 1.65-1.79 1.65-2.79 0-2.26-1.75-4-4-4h-6.25v14h7.04c2.1 0 3.71-1.7 3.71-3.79 0-1.52-0.86-2.82-2.15-3.42z" fill="currentColor"/>\n' +
                        '</svg>'
                }
            });
            el.on('mousedown touchstart', function (e) {
                var sel = api.getSelection();

                if(sel.getRangeAt(0).collapsed) {
                    var node = api.elementNode(sel.focusNode);
                    var actionTarget = mw.tools.firstBlockLevel(node);
                    api.action(actionTarget.parentNode, function () {
                        var isBold = Number(rootScope.actionWindow.getComputedStyle(actionTarget).fontWeight) > 400;
                        if(isBold) {
                            actionTarget.style.fontWeight = 400;
                        } else {
                            actionTarget.style.fontWeight = 700;
                        }
                    });
                } else {
                    api.execCommand('bold');
                }

            });
            return el;
        };
        this.checkSelection = function (opt, ee, tt) {
            if(opt.css.is().bold) {
                rootScope.controllerActive(opt.controller.element.get(0), true);
            } else {
                rootScope.controllerActive(opt.controller.element.get(0), false);
            }
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection));
        };
        this.element = this.render();
    },
    strikeThrough: function (scope, api, rootScope) {
        this.render = function () {
            var scope = this;
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M23,12V14H18.61C19.61,16.14 19.56,22 12.38,22C4.05,22.05 4.37,15.5 4.37,15.5L8.34,15.55C8.37,18.92 11.5,18.92 12.12,18.88C12.76,18.83 15.15,18.84 15.34,16.5C15.42,15.41 14.32,14.58 13.12,14H1V12H23M19.41,7.89L15.43,7.86C15.43,7.86 15.6,5.09 12.15,5.08C8.7,5.06 9,7.28 9,7.56C9.04,7.84 9.34,9.22 12,9.88H5.71C5.71,9.88 2.22,3.15 10.74,2C19.45,0.8 19.43,7.91 19.41,7.89Z" /></svg>',
                    tooltip: rootScope.lang('Strike through')
                }
            });

            el.on('mousedown touchstart', function (e) {

                var sel = api.getSelection();

                if(sel.getRangeAt(0).collapsed) {
                    var node = api.elementNode(sel.focusNode);
                    var actionTarget = mw.tools.firstBlockLevel(node);
                    api.action(actionTarget.parentNode, function () {
                        var isStrike =  (rootScope.actionWindow.getComputedStyle(actionTarget).textDecoration).includes('line-through');

                        if(isStrike) {
                            actionTarget.style.textDecoration = 'none';
                        } else {
                            actionTarget.style.textDecoration = 'line-through';
                        }
                    });
                } else {
                    api.execCommand('strikeThrough');
                }
            });
            return el;
        };
        this.checkSelection = function (opt) {
            if(opt.css.is().striked) {
                rootScope.controllerActive(opt.controller.element.get(0), true);
            } else {
                rootScope.controllerActive(opt.controller.element.get(0), false);
            }
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection));
        };
        this.element = this.render();
    },
    italic: function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M10,4V7H12.21L8.79,15H6V18H14V15H11.79L15.21,7H18V4H10Z" />\n' +
                        '</svg>',
                    tooltip: rootScope.lang('Italic')
                }
            });
            el.on('mousedown touchstart', function (e) {
                var sel = api.getSelection();

                if(sel.getRangeAt(0).collapsed) {


                    var node = api.elementNode(sel.focusNode);
                    var actionTarget = mw.tools.firstBlockLevel(node);
                    api.action(actionTarget.parentNode, function () {
                        var isItalic = rootScope.actionWindow.getComputedStyle(actionTarget).fontStyle !== 'normal';
                        if(isItalic) {
                            actionTarget.style.fontStyle = 'normal';
                        } else {
                            actionTarget.style.fontStyle = 'italic';
                        }
                    });
                } else {
                    api.execCommand('italic');
                }
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection));
            if(opt.css.is().italic) {
                rootScope.controllerActive(opt.controller.element.get(0), true);
            } else {
                rootScope.controllerActive(opt.controller.element.get(0), false);
            }
        };
        this.element = this.render();
    },
    'underline': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M5,21H19V19H5V21M12,17A6,6 0 0,0 18,11V3H15.5V11A3.5,3.5 0 0,1 12,14.5A3.5,3.5 0 0,1 8.5,11V3H6V11A6,6 0 0,0 12,17Z" />\n' +
                        '</svg>',
                    tooltip: rootScope.lang('Underline')
                }
            });
            el.on('mousedown touchstart', function (e) {
                var sel = api.getSelection();
                if(sel.getRangeAt(0).collapsed) {


                    var node = api.elementNode(sel.focusNode);
                    var actionTarget = mw.tools.firstBlockLevel(node)
                    api.action(actionTarget.parentNode, function () {
                        var isUnderline = rootScope.actionWindow.getComputedStyle(actionTarget).textDecoration.indexOf('underline') === 0;
                        if(isUnderline) {
                            actionTarget.style.textDecoration = 'none';
                        } else {
                            actionTarget.style.textDecoration = 'underline';
                        }
                    });
                } else {
                    api.execCommand('underline');
                }
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection));

            if(opt.css.is().underlined) {
                rootScope.controllerActive(opt.controller.element.get(0), true);
            } else {
                rootScope.controllerActive(opt.controller.element.get(0), false);
            }
        };
        this.element = this.render();
    },
    'image': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M19,19H5V5H19M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M13.96,12.29L11.21,15.83L9.25,13.47L6.5,17H17.5L13.96,12.29Z" />\n' +
                        '</svg>',
                    tooltip: rootScope.lang('Insert Image')
                }
            });
            el.on('click', function (e) {
                e.preventDefault();
                api.saveSelection();
                var dialog;

                var picker = new mw.filePicker({
                    type: 'images',
                    label: false,
                    autoSelect: false,
                    multiple: true,
                    footer: true,
                    _frameMaxHeight: true,
                    cancel: function () {
                        dialog.remove()
                    },
                    onResult: function (res) {
                        var url = res.src ? res.src : res;
                        if(!url) return;
                        if(!Array.isArray(url)) {
                            url = [url];
                        }
                        api.restoreSelection();

                        if(rootScope.activeNode && rootScope.activeNode.nodeName === 'IMG') {
                            rootScope.activeNode.src = url[0].toString();
                        } else {
                            url.forEach(function (src){
                                api.insertImage(src.toString());
                            });
                        }

                        dialog.remove();
                    }
                });
                dialog = mw.top().dialog({
                    content: picker.root,
                    title: mw.lang('Select image'),
                    footer: false,
                    width: 740
                });

            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection)|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));
        };
        this.element = this.render();
    },
    link: function(scope, api, rootScope){

        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M3.9,12C3.9,10.29 5.29,8.9 7,8.9H11V7H7A5,5 0 0,0 2,12A5,5 0 0,0 7,17H11V15.1H7C5.29,15.1 3.9,13.71 3.9,12M8,13H16V11H8V13M17,7H13V8.9H17C18.71,8.9 20.1,10.29 20.1,12C20.1,13.71 18.71,15.1 17,15.1H13V17H17A5,5 0 0,0 22,12A5,5 0 0,0 17,7Z" />\n' +
                        '</svg>',
                    tooltip: rootScope.lang('Insert link')
                }
            });

            el.on('click', function (e) {
                api.saveSelection();
                var sel = scope.getSelection();

                var target = mw.tools.firstParentWithTag(sel.focusNode, 'a');

                var val;
                if(target) {
                    val = {
                        url: target.href,
                        text: target.innerHTML,
                        target: target.target === '_blank'
                    };
                } else if(!sel.isCollapsed) {
                    val = {
                        url: '',
                        text: api.getSelectionHTML(),
                        target: target.target === '_blank'
                    };
                }
                var linkEditor = new mw.LinkEditor({
                    mode: 'dialog',
                });
                if(val) {
                    linkEditor.setValue(val);
                }

                linkEditor.promise().then(function (data){
                    var modal = linkEditor.dialog;
                    if(data) {
                        api.restoreSelection();
                        api.link(data);
                        modal.remove();
                    } else {
                        modal.remove();
                    }
                });


            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection)|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));
            rootScope.controllerActive(opt.controller.element.get(0), !!mw.tools.firstParentOrCurrentWithTag(api.elementNode(api.getSelection().focusNode), 'a'));

        };
        this.element = this.render();
    },

    fontSize: function (scope, api, rootScope) {
        this.checkSelection = function (opt) {
            var css = opt.css;
            var font = css.font();
            var size = font.size;
            opt.controller.element.displayValue(size);
            opt.controller.element.find('.mw-editor-dropdown-option.active').removeClass('active');
            opt.controller.element.find('.mw-editor-dropdown-option.active').removeClass('active');
            rootScope.disabled(opt.controller.element, !opt.api.isSelectionEditable()|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)))
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                customValue: true,
                data: [
                    { label: '8', value: 8 },
                    { label: '10', value: 10 },
                    { label: '12', value: 12 },
                    { label: '14', value: 14 },
                    { label: '16', value: 16 },
                    { label: '17', value: 17 },
                    { label: '20', value: 20 },
                    { label: '22', value: 22 },
                    { label: '24', value: 24 },
                    { label: '28', value: 28 },
                    { label: '32', value: 32 },
                    { label: '36', value: 36 },
                    { label: '42', value: 42 },
                    { label: '52', value: 52 },
                    { label: '62', value: 62 },
                    { label: '72', value: 72 },
                    { label: '82', value: 82 },
                    { label: '92', value: 92 },
                ],
                placeholder: rootScope.lang('Font Size')
            });
            dropdown.root.addClass('mw-editor-font-size-selector');
            dropdown.select.on('change', function (e, val) {

                if(val) {

                    api.fontSize(val.value);
                }
            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    lineHeight: function (scope, api, rootScope) {
        this.checkSelection = function (opt) {


            var css = opt.css;
            var font = css.font();
            var val = Math.round((parseFloat(font.height) / parseFloat(font.size)) * 10) / 10;

            opt.controller.element.displayValue(val);
            rootScope.disabled(opt.controller.element, !opt.api.isSelectionEditable() || !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: [
                    { label: 'normal', value: 'normal' },
                    { label: '1', value:'1' },
                    { label: '1.1', value:'1.1' },
                    { label: '1.2', value:'1.2' },
                    { label: '1.3', value:'1.3' },
                    { label: '1.4', value:'1.4' },
                    { label: '1.5', value:'1.5' },
                    { label: '1.6', value:'1.6' },
                    { label: '1.7', value:'1.7' },
                    { label: '1.8', value:'1.8' },
                    { label: '1.9', value:'1.9' },
                    { label: '2', value:'2' },

                ],
                placeholder: rootScope.lang('Line height')
            });
            dropdown.select.on('change', function (e, val) {
                if(val) {
                    api.lineHeight(val.value);
                }

            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    format: function (scope, api, rootScope) {

        this._availableTags = [
            { label: '<mw-editor-option class="mw-editor-option-dropdown-h1">Heading 1</mw-editor-option>', value: 'h1' },
            { label: '<mw-editor-option class="mw-editor-option-dropdown-h2">Heading 2</mw-editor-option>', value: 'h2' },
            { label: '<mw-editor-option class="mw-editor-option-dropdown-h3">Heading 3</mw-editor-option>', value: 'h3' },
            { label: '<mw-editor-option class="mw-editor-option-dropdown-h4">Heading 4</mw-editor-option>', value: 'h4' },
            { label: '<mw-editor-option class="mw-editor-option-dropdown-h5">Heading 5</mw-editor-option>', value: 'h5' },
            { label: '<mw-editor-option class="mw-editor-option-dropdown-h6">Heading 6</mw-editor-option>', value: 'h6' },
            { label: 'Paragraph', value: 'p' },
            { label: 'Block', value: 'div' },
            { label: 'Pre formated', value: 'pre' }
        ];

        this.availableTags = function () {
            if(this.__availableTags) {
                return this.__availableTags;
            }
            this.__availableTags = this._availableTags.map(function (item) {
                return item.value;
            });
            return this.availableTags();
        };

        this.getTagDisplayName = function (tag) {
            tag = (tag || '').trim().toLowerCase();
            if(!tag) return;
            for (var i = 0; i < this._availableTags.length; i++) {
                if(this._availableTags[i].value === tag) {
                    return this._availableTags[i].label;
                }
            }
        };

        this.checkSelection = function (opt) {
            var el = opt.api.elementNode(opt.selection.focusNode);
            var parentEl = mw.tools.firstParentOrCurrentWithTag(el, this.availableTags());
            opt.controller.element.displayValue(parentEl ? this.getTagDisplayName(parentEl.nodeName) : '');
            rootScope.disabled(opt.controller.element, !opt.api.isSelectionEditable() || !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)))
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: this._availableTags,
                placeholder: rootScope.lang('Format')
            });
            dropdown.root.addClass('mw-editor-controller-component-format')
            dropdown.select.on('change', function (e, val) {
                if(e.detail) {
                    var el = api.elementNode(api.getSelection().focusNode);
                    var parent = el.parentNode;
                    var parentul = mw.tools.firstParentOrCurrentWithTag(el, 'ul');
                    var parentol = mw.tools.firstParentOrCurrentWithTag(el, 'ol');
                    if(parentul) {
                        api.execCommand('insertUnorderedList', false, e.detail.value);
                    }
                    if(parentol) {
                        api.execCommand('insertOrderedList', false, e.detail.value);
                    }
                    if(api.isSafeMode(el)){
                        parent.contentEditable = true;
                    }
                    api.execCommand('formatBlock', false, e.detail.value);

                }
            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    fontSelector: function (scope, api, rootScope) {
        this.checkSelection = function (opt) {
            var css = opt.css;
                var font = css.font();
                var family_array = font.family.split(','), fam;
                if (family_array.length === 1) {
                    fam = font.family;
                } else {
                    fam = family_array.shift();
                }
                fam = fam.replace(/['"]+/g, '');
                opt.controller.element.displayValue(fam);
            rootScope.disabled(opt.controller.element, !opt.api.isSelectionEditable()|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)))

        };
        this.render = function () {

            var defaultData = [
                { label:'Arial', value: 'Arial' },
                { label:'Tahoma', value: 'Tahoma' },
                { label:'Verdana', value: 'Verdana' },
                { label:'Georgia', value: 'Georgia' },
                { label:'Times New Roman', value: 'Times New Roman' },

            ];
            var dropdown = new MWEditor.core.dropdown({
                data: defaultData,
                placeholder: rootScope.lang('Font'),
                eachOption: function (obj, node){
                    node.style.fontFamily = obj.value
                }
            });



            if(scope.settings.fontFamilyProvider) {
                scope.settings.fontFamilyProvider.on('change', function (data){
                    dropdown.setData([...defaultData, ...data, ...[{ label:'More...', value: '$more' }]])
                });
            }

            dropdown.select.on('change', function (e, val) {

                if(val) {
                    if(val.value !== '$more') {
                        api.fontFamily(val.value);
                    } else {
                        mw.top().drag.module_settings('#font_family_selector_main','admin');
                    }
                }

            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    undoRedo: function(scope, api, rootScope) {
        this.render = function () {
            this.root = MWEditor.core.element();
            this.root.addClass('mw-ui-btn-nav mw-editor-state-component');
            var undo = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M12.5,8C9.85,8 7.45,9 5.6,10.6L2,7V16H11L7.38,12.38C8.77,11.22 10.54,10.5 12.5,10.5C16.04,10.5 19.05,12.81 20.1,16L22.47,15.22C21.08,11.03 17.15,8 12.5,8Z" /></svg>',
                    tooltip: rootScope.lang('Undo')
                }
            });
            undo.on('mousedown touchstart', function (e) {
                rootScope.state.undo();
                rootScope._syncTextArea();
            });

            var redo = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">' +
                        '<path fill="currentColor" d="M18.4,10.6C16.55,9 14.15,8 11.5,8C6.85,8 2.92,11.03 1.54,15.22L3.9,16C4.95,12.81 7.95,10.5 11.5,10.5C13.45,10.5 15.23,11.22 16.62,12.38L13,16H22V7L18.4,10.6Z" />\n' +
                        '</svg>',
                    tooltip: rootScope.lang('Redo')
                }
            });
            redo.on('mousedown touchstart', function (e) {
                rootScope.state.redo();
                rootScope._syncTextArea();
            });
            this.root.get(0).appendChild(undo.get(0));
            this.root.get(0).appendChild(redo.get(0));
            $(rootScope.state).on('stateRecord', function(e, data){

                rootScope.disabled(undo.get(0), !data.hasNext)
                rootScope.disabled(redo.get(0), !data.hasPrev)
            })
            .on('stateUndo stateRedo', function(e, data){
                if(!data.active || !data.active.target) {
                    rootScope.disabled(undo.get(0), !data.hasNext)
                    rootScope.disabled(redo.get(0), !data.hasPrev)
                    return;
                }
                if(scope.actionWindow.document.body.contains(data.active.target)) {
                    mw.$(data.active.target).html(data.active.value);
                } else{
                    if(data.active.target.id) {
                        mw.$(scope.actionWindow.document.getElementById(data.active.target.id)).html(data.active.value);
                    }
                }
                if(data.active.prev) {
                    mw.$(data.active.prev).html(data.active.prevValue);
                }
                // mw.drag.load_new_modules();
                rootScope.disabled(undo.get(0), !data.hasNext)
                rootScope.disabled(redo.get(0), !data.hasPrev)
                $(scope).trigger(e.type, [data]);
            });
            setTimeout(function () {
                var data = rootScope.state.eventData();
                rootScope.disabled(undo.get(0), !data.hasNext)
                rootScope.disabled(redo.get(0), !data.hasPrev)
            }, 78);
            return this.root;
        };
        this.element = this.render();
    },
    'ul': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M3,4H7V8H3V4M9,5V7H21V5H9M3,10H7V14H3V10M9,11V13H21V11H9M3,16H7V20H3V16M9,17V19H21V17H9" />\n' +
                        '</svg>'
                }
            });
            el.on('mousedown touchstart', function (e) {
                var sel = api.getSelection();
                var node = api.elementNode(sel.focusNode);
                var paragraph = mw.tools.firstParentOrCurrentWithTag(node, 'p');
                if(paragraph) {
                    paragraph.contentEditable = 'inherit';
                    paragraph.parentNode.contentEditable = true;
                    mw.tools.setTag(paragraph, 'div');
                }
                var isSafeMode = api.isSafeMode(node);
                if(isSafeMode) {
                    node.parentNode.parentNode.contentEditable = true;
                }
                api.execCommand('insertUnorderedList');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection)|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));


        };
        this.element = this.render();
    },
    'ol': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M7,13V11H21V13H7M7,19V17H21V19H7M7,7V5H21V7H7M3,8V5H2V4H4V8H3M2,17V16H5V20H2V19H4V18.5H3V17.5H4V17H2M4.25,10A0.75,0.75 0 0,1 5,10.75C5,10.95 4.92,11.14 4.79,11.27L3.12,13H5V14H2V13.08L4,11H2V10H4.25Z" />\n' +
                        '</svg>',
                    'data-tip': 'Ordered list'
                }
            });
            el.on('mousedown touchstart', function (e) {
                var sel = api.getSelection();
                var node = api.elementNode(sel.focusNode);
                var paragraph = mw.tools.firstParentOrCurrentWithTag(node, 'p');

                if(paragraph) {
                    paragraph.contentEditable = 'inherit';
                    paragraph.parentNode.contentEditable = true;
                    mw.tools.setTag(paragraph, 'div');
                }
                var isSafeMode = api.isSafeMode(node);
                if(isSafeMode) {
                    node.parentNode.parentNode.contentEditable = true;
                }
                api.execCommand('insertOrderedList');

            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection)|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));
        };
        this.element = this.render();
    },
    'indent': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-indent-increase',
                    'data-tip': 'Indent'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('indent');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection));
        };
        this.element = this.render();
    },
    'outdent': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-indent-decrease',
                    'data-tip': 'Indent'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('outdent');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection));
        };
        this.element = this.render();
    },
    removeFormat: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M6,5V5.18L8.82,8H11.22L10.5,9.68L12.6,11.78L14.21,8H20V5H6M3.27,5L2,6.27L8.97,13.24L6.5,19H9.5L11.07,15.34L16.73,21L18,19.73L3.55,5.27L3.27,5Z" />\n' +
                        '</svg>',
                    tooltip: 'Remove Format'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('removeFormat');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection));
        };
        this.element = this.render();
    },
    unlink: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M17,7H13V8.9H17C18.71,8.9 20.1,10.29 20.1,12C20.1,13.43 19.12,14.63 17.79,15L19.25,16.44C20.88,15.61 22,13.95 22,12A5,5 0 0,0 17,7M16,11H13.81L15.81,13H16V11M2,4.27L5.11,7.38C3.29,8.12 2,9.91 2,12A5,5 0 0,0 7,17H11V15.1H7C5.29,15.1 3.9,13.71 3.9,12C3.9,10.41 5.11,9.1 6.66,8.93L8.73,11H8V13H10.73L13,15.27V17H14.73L18.74,21L20,19.74L3.27,3L2,4.27Z" />\n' +
                        '</svg>',
                    tooltip: 'Unlink'
                }
            });
            el.on('mousedown touchstart', function (e) {
                var sel = api.getSelection();
                if(sel.isCollapsed) {
                    var node = api.elementNode(sel.focusNode);
                    node = mw.tools.firstParentOrCurrentWithTag(node, 'a');
                    scope.api.action(node.parentNode, function () {
                        while (node.firstChild) {
                            node.parentNode.insertBefore(node.firstChild, node);
                        }
                        node.parentNode.removeChild(node);
                    })
                } else {
                    api.execCommand('unlink');
                }

            });
            return el;
        };
        this.checkSelection = function (opt) {
            var sel = api.getSelection();
            var isLink =  mw.tools.firstParentWithTag(sel.focusNode, 'a');
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection) || !isLink|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));
         };
        this.element = this.render();
    },
    delete: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z" /></svg>',
                    tooltip: 'Delete'
                }
            });
            el.on('mousedown touchstart', function (e) {
                console.log(scope, api, rootScope)

            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection));
        };
        this.element = this.render();
    },
    pin: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M16,12V4H17V2H7V4H8V12L6,14V16H11.2V22H12.8V16H18V14L16,12M8.8,14L10,12.8V4H14V12.8L15.2,14H8.8Z" />\n' +
                        '</svg>',
                    tooltip: 'Pin/Unpin to top'
                }
            });
            el.addClass('mw-editor-button-pin');
            el.on('mousedown touchstart', function (e) {
                e.preventDefault();
                var sel = api.getSelection();
                var node = api.elementNode(sel.focusNode);
                rootScope.smallEditorApi.toggle();

            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection));
        };
        this.element = this.render();
    },
    clone: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M19,21H8V7H19M19,5H8A2,2 0 0,0 6,7V21A2,2 0 0,0 8,23H19A2,2 0 0,0 21,21V7A2,2 0 0,0 19,5M16,1H4A2,2 0 0,0 2,3V17H4V3H16V1Z" /></svg>',
                    tooltip: 'Clone'
                }
            });
            el.on('mousedown touchstart', function (e) {


                var sel = api.getSelection();
                var node = api.elementNode(sel.focusNode);

                var clone = node.cloneNode(true);
                if(clone.id) {
                    clone.id =  mw.id('mw-element-');
                }
                var all = clone.querySelectorAll('[id]'), l = all.length, i = 0;
                for ( ; i < l ; i++) {
                    all[i].id = mw.id('mw-element-');
                }


                api.action(mw.tools.firstBlockLevel(node), function () {
                    node.after(clone);
                });

            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection) || !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));
        };
        this.element = this.render();
    },
    textColor: function (scope, api, rootScope) {

        this.render = function () {
            var el = MWEditor.core.colorPicker({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M9.62,12L12,5.67L14.37,12M11,3L5.5,17H7.75L8.87,14H15.12L16.25,17H18.5L13,3H11Z" />\n' +
                        '</svg>', tooltip: 'Text color'
                }
            });
            el.on('change', function (e, val) {
                var sel = scope.getSelection();
                if(sel.isCollapsed) {
                    var el = scope.api.elementNode(sel.focusNode);
                    scope.api.action(mw.tools.firstBlockLevel(el.parentNode), function () {
                        el.style.color = val
                    }, true);
                } else {
                    api.execCommand('foreColor', false, val, true);
                }

            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection)|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));
        };
        this.element = this.render();
    },
    textBackgroundColor: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.colorPicker({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M19,11.5C19,11.5 17,13.67 17,15A2,2 0 0,0 19,17A2,2 0 0,0 21,15C21,13.67 19,11.5 19,11.5M5.21,10L10,5.21L14.79,10M16.56,8.94L7.62,0L6.21,1.41L8.59,3.79L3.44,8.94C2.85,9.5 2.85,10.47 3.44,11.06L8.94,16.56C9.23,16.85 9.62,17 10,17C10.38,17 10.77,16.85 11.06,16.56L16.56,11.06C17.15,10.47 17.15,9.5 16.56,8.94Z" />\n' +
                        '</svg>', tooltip: 'Text background color'
                }
            });
            el.on('change', function (e, val) {

                var sel = scope.getSelection();
                if(sel.isCollapsed) {
                    var el = scope.api.elementNode(sel.focusNode);
                    scope.api.action(mw.tools.firstBlockLevel(el.parentNode), function () {
                        el.style.backgroundColor = val
                    }, true);
                } else {
                    api.execCommand('backcolor', false, val, true);
                }

            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection)|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));
        };
        this.element = this.render();
    },
    table: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M12.35 20H10V17H12.09C12.21 16.28 12.46 15.61 12.81 15H10V12H14V13.54C14.58 13 15.25 12.61 16 12.35V12H20V12.35C20.75 12.61 21.42 13 22 13.54V5C22 3.9 21.1 3 20 3H4C2.9 3 2 3.9 2 5V20C2 21.1 2.9 22 4 22H13.54C13 21.42 12.61 20.75 12.35 20M16 7H20V10H16V7M10 7H14V10H10V7M8 20H4V17H8V20M8 15H4V12H8V15M8 10H4V7H8V10M17 14H19V17H22V19H19V22H17V19H14V17H17V14" />\n' +
                        '</svg>', tooltip: 'Insert Table'
                }
            });
            el.on('mousedown touchstart', function (e) {
                if((e.which || e.button) === 1) {
                    var table = '<table id="test" class="mw-ui-table" style="width: 100%" border="1" width="100%"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>';
                    api.insertHTML(table);
                }
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection)|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));
        };
        this.element = this.render();
    },
    wordPaste: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M15.2,20H13.8L12,13.2L10.2,20H8.8L6.6,11H8.1L9.5,17.8L11.3,11H12.6L14.4,17.8L15.8,11H17.3L15.2,20M13,9V3.5L18.5,9H13Z" />\n' +
                        '</svg>', tooltip: 'Paste from Word'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.saveSelection();
                var dialog;
                var ok = MWEditor.core.element({
                    tag: 'span',
                    props: {
                        className: 'mw-ui-btn mw-ui-btn-info',
                        innerHTML: rootScope.lang('OK')
                    }
                });
                var cancel = MWEditor.core.element({
                    tag: 'span',
                    props: {
                        className: 'mw-ui-btn',
                        innerHTML: rootScope.lang('Cancel')
                    }
                });
                var cleanEl = mw.element({
                    props: {
                        contentEditable: true,
                        autofocus: true,
                        style: {
                            height: '250px'
                        }
                    }
                });

                var footer = mw.element();
                cancel.on('click', function (){
                    dialog.remove();
                })
                ok.on('click', function (){
                    var content = cleanEl.html().trim();
                    dialog.remove();
                    api.restoreSelection();
                    if(content){
                        api.insertHTML(api.cleanWord(content));
                    }

                });
                footer.append(cancel);
                footer.append(ok);
                dialog = mw.dialog({
                    content: cleanEl.get(0),
                    footer: footer.get(0)
                });
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection));
        };
        this.element = this.render();
    },



};
