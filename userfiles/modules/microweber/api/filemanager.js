(function (){
    mw.require('filemanager.css');
    var FileManager = function (options) {

        var scope = this;

        options = options || {};

        var defaultRequest = function (params, callback, error) {
            var xhr = new XMLHttpRequest();
            scope.dispatch('beforeRequest', {xhr: xhr, params: params});
            xhr.onreadystatechange = function(e) {
                if (this.readyState === 4 && this.status === 200) {
                    callback.call(scope, JSON.parse(this.responseText), xhr);
                } else if(this.status !== 200 && this.readyState === 4) {
                    if(error) {
                        error.call(scope, e);
                    }
                }
            };
            xhr.addEventListener('error', function (e){
                if(error) {
                    error.call(scope, e);
                }
            });
            var url = scope.settings.url + '?' + new URLSearchParams(params || {}).toString();
            xhr.open("GET", url, true);
            xhr.send();
        };

        var defaultAddFile = function (file) {
            return new Promise(function (resolve, reject){
                var xhr = new XMLHttpRequest();
                scope.dispatch('beforeAddFile', {xhr: xhr, input: input});
                xhr.onreadystatechange = function(e) {
                    if (this.readyState === 4 && this.status === 200) {
                        resolve(JSON.parse(this.responseText));
                    } else if(this.status !== 200) {
                        reject(e);
                    }
                };
                xhr.addEventListener('error', function (e){
                    reject(e);
                });
                var url = scope.settings.url + '?path=' + scope.settings.query.path;
                xhr.open("PUT", url, true);
                var formData = new FormData();
                formData.append("file", file);
                xhr.send(formData);
            });
        };

        this.uploadFile = function (file) {
            return this.settings.addFile(file).then(function (){
                scope.refresh(true);
            });
        };

        this.methods = {
            pc: function (options) {
                var node = document.createElement('span');
                node.innerHTML = options.title;
                var upload = mw.upload({
                    multiple: false,
                    element: node,
                    on: {
                        fileAdded: function () {
                            scope.progress(5);
                        },
                        filesUploaded: function () {
                            scope.refresh(true);
                        },
                        progress: function (val) {
                            scope.progress(val.percent);
                        }
                    }
                });
                scope.on('pathChanged', function (path) {
                    upload.urlParam('path', path);
                });
                return node;
            },
            createFolder: function (options) {
                var node = document.createElement('span');
                node.innerHTML = options.title;
                node.addEventListener('click', function (){
                    mw.prompt('Folder name', function (val) {
                        var xhr = new XMLHttpRequest();
                        scope.loading(true);
                        xhr.onreadystatechange = function(e) {
                            if (this.readyState === 4 && this.status === 200) {
                                 scope.refresh(true);
                            } else if(this.status !== 200) {

                            }
                            scope.loading(false)
                        };
                        xhr.addEventListener('error', function (e){
                            scope.loading(false)
                        });
                        var params = {
                            path: scope.settings.query.path,
                            name: val,
                            new_folder: 1
                        };
                        var url = mw.settings.api_url + 'create_media_dir' + '?' + new URLSearchParams(params).toString();
                        xhr.open("POST", url, true);
                        xhr.send();
                    });
                });

                return node;
            }
        };


        var defaults = {
            multiselect: true,
            selectable: true,
            canSelectFolder: false,
            options: true,
            element: null,
            query: {
                order: 'asc',
                orderBy: 'modified',
                path: '/'
            },
            backgroundColor: '#fafafa',
            stickyHeader: false,
            requestData: defaultRequest,
            addFile: defaultAddFile,
            methods: [
                {title: 'Upload file', method: 'pc' },
                {title: 'Create folder', method: 'createFolder' },
            ],
            url: mw.settings.site_url + 'api/file-manager/list',
            viewType: 'list' // 'list' | 'grid'
        };

        var _e = {};


        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

        this.settings = mw.object.extend({}, defaults, options);

        var table, tableHeader, tableBody;

        var _checkName = 'select-fm-' + (new Date().getTime());

        var globalcheck, _pathNode, _backNode;

        var _check = function (name) {
            var input = document.createElement('input');
            input.type = (scope.settings.multiselect ? 'checkbox' : 'radio');
            input.name = name || _checkName;
            var root = mw.element('<label class="mw-ui-check"><span></span></label>');
            root.prepend(input);

            ['click', 'mousedown', 'mouseup', 'touchstart', 'touchend'].forEach(function (ev){
                root.get(0).addEventListener(ev, function (e){
                   e.stopImmediatePropagation();
                   e.stopPropagation();
                });
            });

            return {
                root: root,
                input: mw.element(input),
            };
        };

        var _size = function (item, dc) {
            var bytes = item.size;
            if (typeof bytes === 'undefined' || bytes === null) return '';
            if (bytes === 0) return '0 Bytes';
            var k = 1000,
                dm = dc === undefined ? 2 : dc,
                sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
                i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        };



        var _image = function (item) {
            if (item.type === 'folder') {
                return '<span class="mw-file-manager-list-item-thumb mw-file-manager-list-item-thumb-folder"></span>';
            } else if (item.thumbnail) {
                return '<span class="mw-file-manager-list-item-thumb mw-file-manager-list-item-thumb-image" style="background-image: url(' + item.thumbnail + ')"></span>';
            } else {
                var ext = item.name.split('.').pop();
                if(!ext) {
                    ext = item.mimeType;
                }
                return '<span class="mw-file-manager-list-item-thumb mw-file-manager-list-item-thumb-file">' + (ext) + '</span>';
            }
        };

        var createOption = function (item, option) {
            if(!option.match(item)) {
                return '';
            }
            var el = mw.element({
                content: option.label
            });
            el.on('click', function (e){
                e.stopPropagation()
                option.action(item);
            });
            return el;
        };

        var _renameHandle = function (item) {
            mw.prompt(mw.lang('Enter new name'), function(){

            }, item.name);

        };

        var _downloadHandle = function (item) {

        };


        var _copyUrlHandle = function (item) {
            mw.tools.copy(item.url);
        };

        var _deleteHandle = function (item) {
            mw.confirm(mw.lang('Are you sure') + '?', function (){
                var xhr = new XMLHttpRequest();
                scope.loading(true);
                xhr.onreadystatechange = function(e) {
                    if (this.readyState === 4 && this.status === 200) {
                        scope.refresh(true);
                    } else if(this.status !== 200) {

                    }
                    scope.loading(false)
                };
                xhr.addEventListener('error', function (e){
                    scope.loading(false)
                });
                var dt = {
                    path: item.path,
                };
                var url = mw.settings.api_url + 'media/delete_media_file';
                xhr.open("POST", url, true);
                var tokenFromCookie = mw.cookie.get("XSRF-TOKEN");
                if (tokenFromCookie) {
                    xhr.setRequestHeader('X-XSRF-TOKEN', tokenFromCookie)
                }
                xhr.setRequestHeader('Content-Type', 'application/json');


                xhr.send(JSON.stringify(dt));
            });
        };

        var _selectedUI = function () {
            if(!scope.settings.selectable || !scope.settings.multiselect) {
                return;
            }
            scope.root.removeClass('mw-fm-all-selected', 'mw-fm-none-selected', 'mw-fm-part-selected');
            if(scope.areAllSelected()) {
                scope.root.addClass('mw-fm-all-selected');
                globalcheck.input.prop('checked', true);
                globalcheck.input.prop('indeterminate', false);
            } else if(scope.areNoneSelected()) {
                scope.root.addClass('mw-fm-none-selected');
                globalcheck.input.prop('checked', false);
                globalcheck.input.prop('indeterminate', false);
            }  else {
                scope.root.addClass('mw-fm-part-selected');
                globalcheck.input.prop('checked', false);
                globalcheck.input.prop('indeterminate', true);
            }
        };


        var createOptions = function (item) {
            var options = [
                { label: 'Rename', action: _renameHandle, match: function (item) { return true } },
                { label: 'Download', action: _downloadHandle, match: function (item) { return item.type === 'file'; } },
                { label: 'Copy url', action: _copyUrlHandle, match: function (item) { return true } },
                { label: 'Delete', action: _deleteHandle, match: function (item) { return true } },
            ];
            var el = mw.element().addClass('mw-file-manager-list-item-options');
            el.append(mw.element({tag: 'span', content: '...', props: {tooltip:'options'}}).addClass('mw-file-manager-list-item-options-button'));
            var optsHolder = mw.element().addClass('mw-file-manager-list-item-options-list');
            el.on('click', function (e){
                e.stopPropagation()
                var all = scope.root.get(0).querySelectorAll('.mw-file-manager-list-item-options.active');
                for (var i = 0; i < all.length; i++ ) {
                    if (all[i] !== this) {
                        all[i].classList.remove('active');
                    }
                }
                el.toggleClass('active');
            });
            options.forEach(function (options){
                optsHolder.append(createOption(item, options));
            });
            if(!this.__bodyOptionsClick) {
                this.__bodyOptionsClick = true;
                var bch = function (e) {
                    var curr = e.target;
                    var clicksOption = false;
                  while (curr && curr !== document.body) {
                      if(curr.classList.contains('mw-file-manager-list-item-options')){
                          clicksOption = true;
                          break;
                      }
                      curr = curr.parentNode;
                  }
                  if(!clicksOption) {
                      var all = scope.root.get(0).querySelectorAll('.mw-file-manager-list-item-options.active');
                      for(var i = 0; i < all.length; i++ ) {
                          if (all[i] !== this) {
                              all[i].classList.remove('active')
                          }
                      }
                  }
                };
                document.body.addEventListener('mousedown', bch , false);
            }
            el.append(optsHolder);
            return el;
        };


        var _data = {data: []};

        this.setData = function (data) {
            _data = data;
        };

        this.updateData = function (data) {
            this.setData(data);
            setTimeout(function (){
                _selectedUI();
            }, 100);
            this.dispatch('dataUpdated', data);
        };

        this.getSelectableItems = function () {
            return this.getItems().filter(function (itm){
                return itm.type !== 'folder' || scope.settings.canSelectFolder;
            });
        };

        this.getItems = function () {
            return this.getData().data;
        };

        this.getData = function () {
            return _data;
        };

        var _progress;
        this.progress = function (state) {
            state = Number(state)
            if(!_progress || !_progress.get(0).parentNode) {
                _progress = mw.element({
                    props: {
                        className: 'mw-file-manager-progress'
                    }
                });
                scope.root.prepend(_progress);
            }
            if(state) {
                mw.progress({element: _progress.get(0), action: 'Uploading...'}).set(state);
            } else {
                mw.progress({element: _progress.get(0)}).hide();
            }
            if(state === 100) {
                setTimeout(function (){
                    mw.progress({element: _progress.get(0)}).hide();
                }, 300)
            }
        }

        var _loader;
        this.loading = function (state, l) {
            console.log(l, state)
            if(!_loader || !_loader.get(0).parentNode) {
                _loader = mw.element({
                    props: {
                        className: 'mw-file-manager-spinner'
                    }
                });
                scope.root.prepend(_loader);
            }
            if(state) {
                mw.spinner({element: _loader.get(0), size: 32, decorate: false}).show();
            } else {
                mw.spinner({element: _loader.get(0)}).remove();
            }
        };


        this.requestData = function (params, cb) {
            this.settings.query = params;
            var _cb = function (data) {
                cb.call(undefined, data);
                scope.root[!data.data || data.data.length === 0 ? 'addClass' : 'removeClass']('no-results');
                scope.loading(false, 1);
            };

            scope.loading(true);
            var err = function (er) {
                scope.loading(false, 2);
            };

            this.settings.requestData(
                params, _cb, err
            );
        };




        var _noResultsBlock = function () {
            var noResultsContent = mw.element( {
                tag: 'div',

                props: {
                    innerHTML: mw.lang('This folder does not contain any files or folders'),
                    className: 'mw-file-manager-no-results-content',
                },

            });
            var block = mw.element({
                props: {
                    className: 'mw-file-manager-no-results'
                },
                content: noResultsContent
            });
            scope.creteMethodsNode(noResultsContent.get(0));
            return block;
        };

        var userDate = function (date) {
            var dt = new Date(date);
            return dt.toLocaleString();
        };

        this.find = function (item) {
            if (typeof item === 'number') {

            }
        };


        var _activeSort = {
            orderBy: this.settings.query.orderBy || 'modified',
            order: this.settings.query.order || 'desc',
        };

        this.refresh = function (full){
            if(full) {
                this.requestData(this.settings.query, function (res){
                    scope.setData(res);
                    scope.renderData();
                });
            } else {
                scope.renderData();
            }

        };

        this.sort = function (by, order, _request) {
            if(typeof _request === 'undefined') {
                _request = true;
            }
            if(!order){
                if(by === _activeSort.orderBy) {
                    if(_activeSort.order === 'asc') {
                        order = 'desc';
                    } else {
                        order = 'asc';
                    }
                } else {
                    order = 'asc';
                }
            }
            _activeSort.orderBy = by;
            _activeSort.order = order;
            this.settings.query.orderBy = _activeSort.orderBy;
            this.settings.query.order = _activeSort.order;

            mw.element('[data-sortable]', scope.root).each(function (){
                this.classList.remove('asc', 'desc');
                if(this.dataset.sortable === _activeSort.orderBy) {
                    this.classList.add(_activeSort.order);
                }
            });

            if(_request) {
                this.requestData(this.settings.query, function (res){
                    scope.setData(res);
                    scope.renderData();
                });
            }
        };

        this.search = function (keyword, _request) {
            if(typeof _request === 'undefined') {
                _request = true;
            }

            keyword = (keyword || '').trim();

            if(!keyword){
                delete this.settings.query.keyword;
                this.sort('modified', 'desc', false);
            } else {
                this.settings.query.keyword = keyword;
                this.sort('keyword', 'desc', false);
            }

            mw.element('[data-sortable]', scope.root).each(function (){
                this.classList.remove('asc', 'desc');
                if(this.dataset.sortable === _activeSort.orderBy) {
                    this.classList.add(_activeSort.order);
                }
            });

            if(_request) {
                this.requestData(this.settings.query, function (res){
                    scope.setData(res);
                    scope.renderData();
                });
            }
        }

        this.singleListView = function (item) {
            var row = mw.element({ tag: 'tr' });
            var cellImage = mw.element({ tag: 'td', content: _image(item), props: {className: 'mw-file-manager-list-item-thumb-image-cell'}  });
            var cellName = mw.element({ tag: 'td', content: item.name  });
            var cellSize = mw.element({ tag: 'td', content: _size(item) });

            var cellmodified = mw.element({ tag: 'td', content: userDate(item.modified)  });
            if(item.type === 'folder') {
                row.on('click', function (){
                    scope.path(scope.path() + '/' + item.name);
                });
            }
            if(this.settings.selectable) {

                if (this.settings.canSelectFolder || item.type === 'file') {
                    var check =  _check();
                    check.input.on('change', function () {
                         scope[!this.checked ? 'unselect' : 'select'](item);
                        _selectedUI();
                    });
                    row.append( mw.element({ tag: 'td', content: check.root, props: {className: 'mw-file-manager-list-item-check-cell'} }));
                } else {
                    row.append( mw.element({ tag: 'td'  }));
                }
            }
             row
                .append(cellImage)
                .append(cellName)
                .append(cellSize)
                .append(cellmodified);
            if(this.settings.options) {
                var cellOptions = mw.element({ tag: 'td', content: createOptions(item) });
                row.append(cellOptions);
            }
            return row;
        };

        var rows = [];

        var listViewBody = function () {
            rows = [];
            tableBody ? tableBody.remove() : '';
            tableBody =  mw.element({
                tag: 'tbody'
            });
            scope.renderData();
            return tableBody;
        };

        this.renderData = function (){
            tableBody.empty();
            rows = [];
            this._selected = [];
            if(globalcheck) {
                globalcheck.input.prop('indeterminate', false);
                globalcheck.input.prop('checked', false);
            }

            scope.getItems().forEach(function (item) {
                var row = scope.singleListView(item);
                rows.push({data: item, row: row});
                tableBody.append(row);
            });
        };


        this._selected = [];

        var pushUnique = function (obj) {
            if (scope._selected.indexOf(obj) === -1) {
                scope._selected.push(obj);
            }
        };


        var afterSelect = function (obj, state) {
            if(scope.settings.multiselect === false) {
                rows.forEach(function (r){
                    r.row.removeClass('selected');
                });
            }
            var curr = rows.find(function (row){
                return row.data === obj;
            });
            if(curr) {
                curr.row[state ? 'addClass' : 'removeClass']( 'selected' );
                var input = curr.row.find('input');
                input.prop('checked', state);
            }
            _selectedUI();
        };


        this.getSelected = function () {
            return this._selected;
        };


        this.areNoneSelected = function () {
            return this.getSelected().length === 0;
        };

        this.areAllSelected = function () {
             return this.getSelectableItems().length === this.getSelected().length;
        };

        this.selectAll = function () {
            rows.forEach(function (rowItem){
                scope.select(rowItem.data);
            });
        };
        this.selectNone = function () {
            rows.forEach(function (rowItem){
                scope.unselect(rowItem.data);
            });
        };

        this.selectAllToggle = function () {
            if(this.areAllSelected()){
                this.selectNone();
            } else {
                this.selectAll();
            }
        };

        this.select = function (obj) {
            if(obj.type === 'folder' && !this.settings.canSelectFolder) {
                return;
            }
            if (this.settings.multiselect) {
                pushUnique(obj);
            } else {
                this._selected = [obj];
            }
            afterSelect(obj, true);
        };


        this.unselect = function (obj) {
            this._selected.splice(this._selected.indexOf(obj), 1);
            afterSelect(obj, false);
        };

        this.back = function (){
            var curr = this.settings.query.path;
            if(!curr || curr === '/') {
                return;
            }
            curr = curr.split('/');
            curr.pop();
            this.settings.query.path = curr.join('/');
            this.path(this.settings.query.path );
        };



        this.creteSearchNode = function (target) {
          var html = '<div class="mw-file-manager-search">' +
              '<div class="mw-field"><input type="text" placeholder="Search"><button type="button" class="mw-file-manager-search-clear"></button></div><button type="button" class="mw-ui-btn mw-field-append mw-file-manager-search-button">' +
              '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">' +
              '<path d="M954.7,855L774.6,674.8c-13.5-13.5-30.8-20.8-48.4-22.5c54.3-67.7,87-153.5,87-246.8C813.1,187.4,635.7,10,417.6,10S22.1,187.4,22.1,405.5S199.5,801,417.6,801c82.3,0,158.8-25.3,222.1-68.5c0.4,19.6,8,39.1,23,54.1l180.2,180.2c15.4,15.5,35.7,23.2,55.9,23.2c20.2,0,40.5-7.7,55.9-23.2C985.6,935.9,985.6,885.9,954.7,855z M417.6,669.2c-145.4,0-263.7-118.3-263.7-263.7s118.3-263.7,263.7-263.7s263.7,118.3,263.7,263.7S563,669.2,417.6,669.2z"/>' +
              '</svg></button>' +
              '</div>';

            var el = mw.element(html);
            if(target) {
                target.appendChild(el.get(0));
            }
            mw.element('.mw-file-manager-search-clear', el).on('click', function (){
                mw.element('input', el).val('');
                scope.search( '', true);
            });
            mw.element('.mw-file-manager-search-button', el).on('click', function (){
                scope.search( mw.element('input', el).val().trim() , true);
            });

            mw.element('input', el).on('keydown', function (e){
                if (e.key === 'Enter' || e.keyCode === 13) {
                    scope.search( mw.element('input', el).val().trim(), true);
                }
            });
            return el.get(0);
        };

        this.creteMethodsNode = function (target) {
            if(!this.settings.methods) {
                return;
            }
            target = target || document.createElement('div');

            var root = mw.element({
                props: {
                    className: 'mw-file-manager-create-methods-dropdown'
                }
            });
            var addButton = mw.element({
                props: {
                    className: 'mw-ui-btn mw-ui-btn-notification mw-file-manager-create-methods-dropdown-add',
                    innerHTML: '+'
                }
            });

            addButton.on('click', function (){
                this.parentElement.classList.toggle('active');
            });

            addButton.get(0).ownerDocument.body.addEventListener('click', function (e){
                if (!addButton.get(0).contains(e.target)) {
                    addButton.get(0).parentElement.classList.remove('active');
                }
            });

            var selectElContent = mw.element({
                props: {
                    className: 'mw-file-manager-top-bar-actions-content'
                }
            });
            this.settings.methods.forEach(function (method){
                var node = mw.element({
                    props: {
                        className: 'mw-file-manager-add-node'
                    }
                });
                node.append(scope.methods[method.method](method));
                selectElContent.append(node);
            });
            target.appendChild(root.get(0));
            root.append(addButton);
            root.append(selectElContent);
            return target;
        };


        var createTopBar = function (){
            var topBar = mw.element({
                props: {
                    className: 'mw-file-manager-top-bar'
                }
            });

            var selectEl = mw.element({
                props: {
                    className: 'mw-file-manager-top-bar-actions'
                }
            });


            scope.creteMethodsNode(selectEl.get(0));
            scope.creteSearchNode(selectEl.get(0));
            topBar.append(selectEl);
            return topBar.get(0);

        };

        var createMainBar = function (){
            var viewTypeSelectorRoot = mw.element({
                props: {
                    className: 'mw-file-manager-bar-view-type-selector'
                }
            });
            _backNode = mw.element({
                tag: 'button',
                props: {
                    className: 'btn btn-outline-primary btn-sm',
                    innerHTML: '<i class="mdi mdi-keyboard-backspace"></i> ' + mw.lang('Back')
                }
            });
            _backNode.on('click', function (){
                scope.back();
            });

            var viewTypeSelector = mw.select({
                element: viewTypeSelectorRoot.get(0),
                size: 'small',
                data: [
                    {title: mw.lang('List'), value: 'list'},
                    {title: mw.lang('Grid'), value: 'grid'},
                ]
            });
            viewTypeSelector.on('change', function (val){
                scope.viewType( val[0].value);
            });
            _pathNode = mw.element({
                props: {
                    className: 'mw-file-manager-bar-path'
                }
            });
            var _pathNodeRoot = mw.element({
                props: {
                    className: 'mw-file-manager-bar-path-root'
                }
            });
            _pathNodeRoot.append(_pathNode)

            var bar = mw.element({
                props: {
                    className: 'mw-file-manager-bar'
                }
            });
            bar
                .append(_backNode)
                .append(_pathNodeRoot)
                .append(viewTypeSelectorRoot);
            return bar;
        };

        var createListViewHeader = function () {
            var thCheck;
            if (scope.settings.selectable && scope.settings.multiselect ) {
                globalcheck = _check('select-fm-global-' + (new Date().getTime()));
                globalcheck.root.addClass('mw-file-manager-select-all-check');
                globalcheck.input.on('input', function () {
                    scope.selectAllToggle();
                });
                thCheck = mw.element({ tag: 'th', content: globalcheck.root  }).addClass('mw-file-manager-select-all-heading');
            } else {
                thCheck = mw.element({ tag: 'th', }).addClass('mw-file-manager-select-all-heading');
            }
            var thImage = mw.element({ tag: 'th', content: ''  });
            var thName = mw.element({ tag: 'th', content: '<span>Name</span>'  }).addClass('mw-file-manager-sortable-table-header');
            var thSize = mw.element({ tag: 'th', content: '<span>Size</span>'  }).addClass('mw-file-manager-sortable-table-header');
            var thModified = mw.element({ tag: 'th', content: '<span>Last modified</span>'  }).addClass('mw-file-manager-sortable-table-header');
            var thOptions = mw.element({ tag: 'th', content: ''  });
            var ths = [thCheck, thImage, thName, thSize, thModified, thOptions];

                ths.forEach(function (th){
                    th.css('backgroundColor', scope.settings.backgroundColor);
                    if(typeof scope.settings.stickyHeader === 'number') {
                        th.css('top', scope.settings.stickyHeader);
                    }
                });

            var tr = mw.element({
                tag: 'tr',
                content: ths
            });
            tableHeader =  mw.element({
                tag: 'thead',
                content: tr
            });
            tableHeader.addClass('sticky-' + (scope.settings.stickyHeader !== false && scope.settings.stickyHeader !== undefined));
            tableHeader.css('backgroundColor', scope.settings.backgroundColor);
            thName.dataset('sortable', 'name').on('click', function (){ scope.sort(this.dataset.sortable) });
            thSize.dataset('sortable', 'size').on('click', function (){ scope.sort(this.dataset.sortable) });
            thModified.dataset('sortable', 'modified').on('click', function (){ scope.sort(this.dataset.sortable) });

            return tableHeader;
        };

        var _view = function () {
            if(!table) {
                table =  mw.element('<table class="mw-file-manager-view-table" />');
                table.css('backgroundColor', scope.settings.backgroundColor);
                table
                    .append(createListViewHeader())
                    .append(listViewBody());
            } else {
                scope.renderData();
            }
            var tableWrap = mw.element('<div class="mw-file-manager-view-table-wrap" />');
            tableWrap.append(table);
            return tableWrap;
        };

        this.view = function () {
            this.root
                .empty()
                .append(createTopBar())
                .append(createMainBar())
                .append(_view())
                .append(_noResultsBlock());
        };

        var createRoot = function (){
            scope.root = mw.element({
                props: {
                    className: 'mw-file-manager-root'
                }
            });
        };

        this.viewType = function (viewType, _forced) {
            if (!viewType) {
                return this.settings.viewType;
            }
            if(viewType !== this.settings.viewType || _forced) {
                this.settings.viewType = viewType;
                this.root.dataset('view', this.settings.viewType);
                this.dispatch('viewTypeChange', this.settings.viewType);
            }
        };

        var pathItem = function (path, html){
            var node = document.createElement('a');
            node.innerHTML = html || path.split('/').pop();
            node.addEventListener('click', function (e){
                e.preventDefault();
                scope.path(path);
            });
            return node;
        };

        this.path = function (path, _request){
            if(typeof _request === 'undefined') {
                _request = true;
            }
            if(typeof path === 'undefined'){
                return this.settings.query.path;
            }
            path = (path || '').trim();
            this.settings.query.path = path;
            delete this.settings.query.keyword;
            scope.dispatch('pathChanged', this.settings.query.path);
            path = path.split('/').map(function (itm){return itm.trim()}).filter(function (itm){return !!itm});
            _pathNode.empty();

            var showHome = path.length > 0;


            while (path.length) {
                _pathNode.prepend(pathItem(path.join('/')));
                path.pop();
            }

            if(showHome) {
                _pathNode.prepend(pathItem('', 'Home'));
                if(_backNode) {
                    _backNode.show();
                }

            } else {
                if(_backNode) {
                    _backNode.hide();
                }
            }
            if(_request) {
                scope.sort(scope.settings.query.orderBy, scope.settings.query.order, false);

                this.requestData(this.settings.query, function (res){
                    scope.setData(res);
                    scope.renderData();
                });
            }

        };

        this.init = function (){
            createRoot();
            this.requestData(this.settings.query, function (data){
                scope.updateData(data);
                scope.view();
                scope.path(scope.settings.query.path, false);
                scope.sort(scope.settings.query.orderBy, scope.settings.query.order, false);
            });
            if (this.settings.element) {
                mw.element(this.settings.element).empty().append(this.root);
            }

            this.viewType(this.settings.viewType, true);

        };
        this.init();
    };

    FileManager.addMethod = function (name, cb) {

    }

    mw.FileManager = function (options) {
        return new FileManager(options);
    };
})();
