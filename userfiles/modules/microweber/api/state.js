(function (){
    if(mw.State) return;
    var State = function(options){

        var scope = this;
        var defaults = {
            maxItems: 1000
        };
        this.options = $.extend({}, defaults, (options || {}));
        this._state = this.options.state || [];
        this._active = null;
        this._activeIndex = -1;

        this.hasNext = false;
        this.hasPrev = false;

        this.state = function(state){
            if(!state){
                return this._state;
            }
            this._state = state;
            return this;
        };


        this.active = function(active){
            if(!active){
                return this._active;
            }
        };

        this.activeIndex = function(activeIndex){
            if(!activeIndex){
                return this._activeIndex;
            }
        };

        this._timeout = null;
        this.timeoutRecord = function(item){
            clearTimeout(this._timeout);
            this._timeout = setTimeout(function(scope, item){
                scope.record(item);
            }, 333, this, item);
        };

        var recentRecordIsEqual = function (item) {
            const curr = scope._state[0];
            if(!curr) return false;
            for (var n in item) {
                if(curr[n] !== item[n]) {
                    return false;
                }
            }
            return true;
        };

        this.record = function(item){
            if(this._activeIndex>-1) {
                var i = 0;
                while ( i <  this._activeIndex) {
                    this._state.shift();
                    i++;
                }
            }
            if (recentRecordIsEqual(item)) {
                return;
            }
            this._state.unshift(item);
            if(this._state.length >= this.options.maxItems) {
                this._state.splice(-1,1);
            }
            this._active = null;
            this._activeIndex = -1;
            this.afterChange(false);
            mw.$(this).trigger('stateRecord', [this.eventData()]);
            return this;
        };

        this.actionRecord = function(recordGenFunc, action){
            this.record(recordGenFunc());
            action.call();
            this.record(recordGenFunc());
        };

        this.redo = function(){
            this._activeIndex--;
            this._active = this._state[this._activeIndex];
            this.afterChange('stateRedo');
            return this;
        };

        this.undo = function(){
            if(this._activeIndex === -1) {
                this._activeIndex = 1;
            }
            else{
                this._activeIndex++;
            }
            this._active = this._state[this._activeIndex];
            this.afterChange('stateUndo');
            return this;
        };

        this.hasRecords = function(){
            return !!this._state.length;
        };

        this.eventData = function(){
            return {
                hasPrev: this.hasPrev,
                hasNext: this.hasNext,
                active: this.active(),
                activeIndex: this.activeIndex()
            };
        };
        this.afterChange = function(action){
            this.hasNext = true;
            this.hasPrev = true;

            if(action) {
                if(this._activeIndex >= this._state.length) {
                    this._activeIndex = this._state.length - 1;
                    this._active = this._state[this._activeIndex];
                }
            }

            if(this._activeIndex <= 0) {
                this.hasPrev = false;
            }
            if(this._activeIndex === this._state.length-1 || (this._state.length === 1 && this._state[0].$initial)) {
                this.hasNext = false;
            }

            if(action){

                mw.$(this).trigger(action, [this.eventData()]);
            }
            if(action !== false){
                mw.$(this).trigger('change', [this.eventData()]);
            }
            return this;
        };

        this.reset = function(){
            this._state = this.options.state || [];
            this.afterChange('reset');
            return this;
        };

        this.clear = function(){
            this._state = [];
            this.afterChange('clear');
            return this;
        };


    };
    mw.State = State;
})();

(function(){
    if(mw.liveEditState) return;
    mw.liveEditState = new mw.State();
    mw.liveEditState.record({
         value: null,
         $initial: true
    });
    mw.$liveEditState = mw.$(mw.liveEditState);

    var ui = mw.$('<div class="mw-ui-btn-nav"></div>'),
        undo = mwd.createElement('span'),
        redo = mwd.createElement('span');
    undo.className = 'mw-ui-btn mw-ui-btn-medium';
    undo.innerHTML = '<span class="mw-icon-reply"></span>';
    redo.className = 'mw-ui-btn mw-ui-btn-medium';
    redo.innerHTML = '<span class="mw-icon-forward"></span>';

    undo.onclick = function(){
        mw.liveEditState.undo();
    };
    redo.onclick = function(){
        mw.liveEditState.redo();
    };

    ui.append(undo);
    ui.append(redo);

    mw.$(document).ready(function(){
        var idata = mw.liveEditState.eventData();

        mw.$(undo)[!idata.hasNext?'addClass':'removeClass']('disabled');
        mw.$(redo)[!idata.hasPrev?'addClass':'removeClass']('disabled');

        /*undo.disabled = !idata.hasNext;
        redo.disabled = !idata.hasPrev;*/

        var edits = document.querySelectorAll('.edit'), editstime = null;

        for ( var i = 0; i < edits.length; i++ ) {
            if(!mw.tools.hasParentsWithClass(this, 'edit')) {
                edits[i].addEventListener('keydown', function (e) {
                    var sel = getSelection();
                    var target = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
                    if(target && !target.__initialRecord) {
                        target.__initialRecord = true;

                        mw.liveEditState.record({
                            target: target,
                            value: target.innerHTML
                        });
                    }
                });
                edits[i].addEventListener('input', function (e) {
                    clearTimeout(editstime);
                    editstime = setTimeout(function () {
                        var sel = getSelection();
                        var target = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
                        if(!target) return;
                        mw.liveEditState.record({
                            target: target,
                            value: target.innerHTML
                        });
                        this.__initialRecord = false;
                    }, 1234);
                });
            }
        }

        mw.$liveEditState.on('stateRecord', function(e, data){
            mw.$(undo)[!data.hasNext?'addClass':'removeClass']('disabled');
            mw.$(redo)[!data.hasPrev?'addClass':'removeClass']('disabled');
        });
        mw.$liveEditState.on('stateUndo stateRedo', function(e, data){



            if(!data.active || (!data.active.target && !data.active.action)) {
                mw.$(undo)[!data.hasNext?'addClass':'removeClass']('disabled');
                mw.$(redo)[!data.hasPrev?'addClass':'removeClass']('disabled');
                return;
            }
            if(data.active.action) {
                data.active.action();
            } else if(document.body.contains(data.active.target)) {
                mw.$(data.active.target).html(data.active.value);
            } else{
                if(data.active.target.id) {
                    mw.$(document.getElementById(data.active.target.id)).html(data.active.value);
                }
            }
            if(data.active.prev) {
                mw.$(data.active.prev).html(data.active.prevValue);
            }
            mw.drag.load_new_modules();
            mw.$(undo)[!data.hasNext?'addClass':'removeClass']('disabled');
            mw.$(redo)[!data.hasPrev?'addClass':'removeClass']('disabled');
        });

        mw.$('#history_panel_toggle,#history_dd,.mw_editor_undo,.mw_editor_redo').remove();
        mw.$('.wysiwyg-cell-undo-redo').eq(0).prepend(ui);





        mw.element(document.body).on('keydown', function(e) {
            if (e.ctrlKey && e.key === 'z') {
                e.preventDefault();
                mw.liveEditState.undo();
            } else if (e.ctrlKey && e.key === 'y') {
                e.preventDefault();
                mw.liveEditState.redo();
            }
        });

    });

})();


