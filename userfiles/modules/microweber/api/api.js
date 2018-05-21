if (typeof jQuery == 'undefined') {

<?php


    $haystack = load_web_component_file('jquery/jquery-3.3.1.min.js');
    $haystack .= "\n\n".load_web_component_file('jquery/jquery-migrate-3.0.0.js');
	 
 
	$needle = '//@ sourceMappingURL=';
	$replace = '//@ disabled_sourceMappingURL=';
	$pos = strpos($haystack,$needle);
	$newstring = $haystack;
	if ($pos !== false) {
		$newstring = substr_replace($haystack,$replace,$pos,strlen($needle));
	}
	print $newstring;
?>


}


$.ajaxSetup({
    cache: false,
    error: function (xhr, e) {
        if(xhr.status !== 200 && xhr.status !== 0){
            mw.notification.error('Error ' + xhr.status + ' - ' + xhr.statusText + ' - \r\n' + xhr.responseText );
            setTimeout(function(){
                mw.tools.loading(false);
            }, 333);
        }
    }
});






jQuery.cachedScript = function( url, options ) {
    options = $.extend( options || {}, {
    dataType: "script",
    cache: true,
    url: url
});
    return jQuery.ajax( options );
};



if(typeof mw === 'undefined'){
    mw = {}
}




mw.version = "<?php print MW_VERSION; ?>";

mw.pauseSave = false;

mw.askusertostay = false;

  if (top === self){
    window.onbeforeunload = function() {
      if(mw.askusertostay){
        mw.notification.warning("<?php _e("You have unsaved changes"); ?>!");
        return "<?php _e("You have unsaved changes"); ?>!";
      }
    }
  }

  warnOnLeave = function(){
     mw.tools.confirm("<?php _e("You have unsaved changes! Are you sure"); ?>?");
  }

  mw.module = {}

  mwd = document;
  mww = window;
  mwhead = mwd.head || mwd.getElementsByTagName('head')[0];

  mw.loaded = false;

  mw._random = new Date().getTime();

  mw.random = function() {
    return mw._random++;
  }

  String.prototype.contains = function(a) {
    return !!~this.indexOf(a);
  };
  String.prototype.tonumber = function(){
    var n = parseFloat(this);
    if(!isNaN(n)){
        return n;
    }
    else{
      return 0;
    }
  }

  mw.onLive = function(callback) {
    if (typeof mw.settings.liveEdit === 'boolean' && mw.settings.liveEdit) {
      callback.call(this)
    }
  }
  mw.onAdmin = function(callback) {
    if ( window['mwAdmin'] ) {
      callback.call(this);
    }
  }

  if (Array.prototype.indexOf === undefined) {
    Array.prototype.indexOf = function(obj) {
      var i=0, l=this.length;
      for ( ; i < l; i++) {
        if (this[i] == obj) {
          return i;
        }
      }
      return -1;
    }
  }


  mw.required = typeof mw.required === 'undefined'?[]:mw.required;
  mw.require = function(url, inHead, key) {
    if(typeof inHead === 'boolean' || typeof inHead === 'undefined'){
        inHead = inHead || false;
    }
    if(typeof inHead === 'string'){
        var keyString = ''+inHead;
        inHead = key || false;
    }
    if(typeof key === 'string'){
        var keyString = key;
    }
    var toPush = url, urlModified = false;
    if(!!keyString){
        toPush = keyString;
        urlModified = true
    }
    var t = url.split('.').pop();
    var url = url.contains('//') ? url : (t !== "css" ? "<?php print( mw_includes_url() ); ?>api/" + url  :  "<?php print( mw_includes_url() ); ?>css/" + url);
    if(!urlModified) toPush = url;
    if (!~mw.required.indexOf(toPush)) {
      mw.required.push(toPush);
      var url = url.contains("?") ?  url + '&mwv=' + mw.version : url + "?mwv=" + mw.version;
      if(document.querySelector('link[href="'+url+'"],script[src="'+url+'"]') !== null){
          return
      }
      var string = t != "css" ? "<script type='text/javascript'  src='" + url + "'></script>" : "<link rel='stylesheet' type='text/css' href='" + url + "' />";
      if ((mwd.readyState === 'loading'/* || mwd.readyState === 'interactive'*/) && !inHead && !!window.CanvasRenderingContext2D && self === parent) {
         mwd.write(string);
      }
      else {
          if(typeof $ === 'function'){
              $(mwhead).append(string);
          }
          else{
              if( t !== "css")  {
                  var el = mwd.createElement('script');
                  el.src = url;
                  el.setAttribute('type', 'text/javascript');
                  mwhead.appendChild(el);
              }
              else{
                 var el = mwd.createElement('link');
                 el.rel='stylesheet';
                 el.type='text/css';
                 el.href = url;
                 mwhead.appendChild(el);
              }
          }
      }
    }
  }

  mw.getScripts = function (array, callback) {
    if(array.length === 0){
        callback.call();
    }
    else{
        var curr = array.shift();
        if (!~mw.required.indexOf(curr)) {
            mw.required.push(curr);
            $.getScript(curr, function () {
                mw.getScripts(array, callback)
            });
        }
        else{
            mw.getScripts(array, callback)
        }
    }
  }

  mw.moduleCSS = mw.module_css = function(url){
    if (!~mw.required.indexOf(url)) {
      mw.required.push(url);
      var el = mwd.createElement('link');
      el.rel='stylesheet';
      el.type='text/css';
      el.href = url;
      mwhead.insertBefore(el, mwhead.firstChild);
    }
  }
  mw.moduleJS = mw.module_js = function(url){
    mw.require(url, true);
  }



  mw.wait = function(a, b, max) {
    window[a] === undefined ? setTimeout(function() {
      mw.wait(a, b), 52
    }) : b.call(a);
  }

  mw.target = {}

  mw.is = {
    obj: function(obj) {
      return typeof obj === 'object';
    },
    func: function(obj) {
      return typeof obj === 'function';
    },
    string: function(obj) {
      return typeof obj === 'string';
    },
    array:function(obj){
      return [].constructor === obj.constructor;
    },
    invisible: function(obj) {
      return window.getComputedStyle(obj, null).visibility === 'hidden';
    },
    visible: function(obj) {
      return window.getComputedStyle(obj, null).visibility === 'visible';
    },
    ie: (/*@cc_on!@*/false || !!window.MSStream),
    firefox:navigator.userAgent.toLowerCase().contains('firefox')
  }


/*
 * Microweber - Javascript Framework
 *
 * Copyright (c) Licensed under the Microweber
 * license http://microweber.com/license
 *
 */







  mw.Xsettings = {
    regions:false,
    liveEdit:false,
    debug: true,
	basic_mode:false,
    site_url: '<?php print site_url(); ?>',
    template_url: '<?php print TEMPLATE_URL; ?>',
    modules_url:'<?php print modules_url(); ?>',
    includes_url: '<?php   print( mw_includes_url());  ?>',
    upload_url: '<?php print site_url(); ?>api/upload/',
    api_url: '<?php print site_url(); ?>api/',
    libs_url: '<?php   print( mw_includes_url());  ?>api/libs/',
    api_html: '<?php print site_url(); ?>api_html/',
    page_id: '<?php print intval(PAGE_ID) ?>',
    post_id: '<?php print intval(POST_ID) ?>',
    category_id: '<?php print intval(CATEGORY_ID) ?>',
    content_id: '<?php print intval(CONTENT_ID) ?>',
    editables_created: false,
    element_id: false,
    text_edit_started: false,
    sortables_created: false,
    drag_started: false,
    sorthandle_hover: false,
    resize_started: false,
    sorthandle_click: false,
    row_id: false,
    edit_area_placeholder: '<div class="empty-element-edit-area empty-element ui-state-highlight ui-sortable-placeholder"><span><?php _e("Please drag items here"); ?></span></div>',
    empty_column_placeholder: '<div id="_ID_" class="empty-element empty-element-column"><?php _e("Please drag items here"); ?></div>',
    handles: {
      module: "\
        <div contenteditable='false' id='mw_handle_module' class='mw-defaults mw_master_handle mw-sorthandle mw-sorthandle-col mw-sorthandle-module' draggable='false'>\
            <div class='mw_col_delete mw_edit_delete_element' draggable='false'>\
                <a class='mw_edit_btn mw_edit_delete right' href='javascript:void(0);' onclick='mw.drag.delete_element(mw.handle_module);return false;' draggable='false'><span></span></a>\
            </div>\
            <a title='Click to edit this module.' class='mw_edit_settings' href='javascript:void(0);' onclick='mw.drag.module_settings();return false;' draggable='false'><span class='mw-element-name-handle' draggable='false'></span></a>\
            <span id='mw_handle_module_up' class='mw_handle_module_arrow'></span>\
            <span id='mw_handle_module_down' class='mw_handle_module_arrow'></span>\
            <span title='Click to select this module.' class='mw-sorthandle-moveit' draggable='false' title='<?php _e("Move"); ?>'></span>\
        </div>",
      row: "\
        <div contenteditable='false' class='mw-defaults mw_master_handle mw_handle_row' id='mw_handle_row' draggable='false'>\
            <span title='<?php _e("Click to select this column"); ?>.' class='column_separator_title'><?php _e("Columns"); ?></span>\
            <a href='javascript:;' onclick='event.preventDefault();mw.drag.create_columns(this,1);' class='mw-make-cols mw-make-cols-1 active'  draggable='false'>1</a>\
            <a href='javascript:;' onclick='event.preventDefault();mw.drag.create_columns(this,2);' class='mw-make-cols mw-make-cols-2'  draggable='false'>2</a>\
            <a href='javascript:;' onclick='event.preventDefault();mw.drag.create_columns(this,3);' class='mw-make-cols mw-make-cols-3'  draggable='false'>3</a>\
            <a href='javascript:;' onclick='event.preventDefault();mw.drag.create_columns(this,4);' class='mw-make-cols mw-make-cols-4'  draggable='false'>4</a>\
            <a href='javascript:;' onclick='event.preventDefault();mw.drag.create_columns(this,5);' class='mw-make-cols mw-make-cols-5'  draggable='false'>5</a>\
            <a class='mw_edit_delete mw_edit_btn right' onclick='mw.drag.delete_element(mw.handle_row);' href='javascript:;' draggable='false'><span></span></a>\
        </div>",
      element: "\
        <div contenteditable='false' draggable='false' id='mw_handle_element' class='mw-defaults mw_master_handle mw-sorthandle mw-sorthandle-element'>\
            <div contenteditable='false' draggable='false' class='mw_col_delete mw_edit_delete_element'>\
                <a contenteditable='false' draggable='false' class='mw_edit_btn mw_edit_delete'  onclick='mw.drag.delete_element(mw.handle_element);'><span></span></a>\
            </div>\
            <span contenteditable='false' draggable='false' class='mw-sorthandle-moveit' title='<?php _e("Move"); ?>'></span>\
        </div>",
      item: "<div title='<?php _e("Click to select this item"); ?>.' class='mw_master_handle' id='items_handle'></div>"
    },
    sorthandle_delete_confirmation_text: "<?php _e("Are you sure you want to delete this element"); ?>?"
  }



  mw.load_module = function(name, selector, callback, attributes) {
     var attributes = attributes || {};
     attributes.module = name;
      mw._({
        selector: selector,
        params: attributes,
        done: function() {
          mw.settings.sortables_created = false;
          if (mw.is.func(callback)) {
            callback.call(mw.$(selector)[0]);
          }
        }
      });
  }

  mw.loadModuleData = function(name, update_element, callback, attributes){
    var attributes = attributes || {};
    if(typeof update_element == 'function'){
      var callback = update_element;
    }
    var update_element = document.createElement('div');
    attributes.module = name;
    mw._({
      selector: update_element,
      params: attributes,
      done: function(data) {
        callback.call(this, data);
      }
    }, false, true);
  }
  mw.getModule = function(name, params, callback){
    if( typeof params == 'function'){
        var callback = params;
    }
    var params = params || {};
    var update_element = document.createElement('div');
    for(var x in params){
        update_element.setAttribute(x, params[x]);
    }
    mw.loadModuleData(name, update_element, function(a){
        callback.call(a);
    });
  }

  mw.reload_module_intervals = {};
  mw.reload_module_interval = function(module_name, interval) {
    var interval =  interval || 1000;
    var obj = {pause:false};
    if(!!mw.reload_module_intervals[module_name]){
        clearInterval(mw.reload_module_intervals[module_name]);
    }
    mw.reload_module_intervals[module_name] = setInterval(function(){
        if(!obj.pause){
            obj.pause = true;
            mw.reload_module(module_name, function(){
                obj.pause = false;
            });
        }
    }, interval);
    return mw.reload_module_intervals['module_name'];
  }

  mw.reload_module_parent = function(module, callback) {
    if(self !== parent && !!parent.mw){

       parent.mw.reload_module(module, callback)
	   if(typeof(top.mweditor) != 'undefined'  && typeof(top.mweditor) == 'object'   && typeof(top.mweditor.contentWindow) != 'undefined'){
		 top.mweditor.contentWindow.mw.reload_module(module, callback)
		} else if(typeof(window.top.iframe_editor_window) != 'undefined'  && typeof(window.top.iframe_editor_window) == 'object'   && typeof(window.top.iframe_editor_window.mw) != 'undefined'){

		window.top.iframe_editor_window.mw.reload_module(module, callback)
		}

        if(typeof(parent.mw_preview_frame_object) != 'undefined'  && typeof(parent.mw_preview_frame_object) == 'object'   && typeof(parent.mw_preview_frame_object.contentWindow) != 'undefined'){
            if(parent.mw_preview_frame_object.contentWindow != null && typeof(parent.mw_preview_frame_object.contentWindow.mw) != 'undefined'){
                parent.mw_preview_frame_object.contentWindow.mw.reload_module(module, callback)
            }
        }
    } else {
		if(typeof(mweditor) != 'undefined'  && typeof(mweditor) == 'object'   && typeof(mweditor.contentWindow) != 'undefined' && typeof(mweditor.contentWindow.mw) != 'undefined'){
		    mweditor.contentWindow.mw.reload_module(module, callback)
		}

	}
  }
  mw.reload_modules = function(array, callback, simultaneously) {
      if(array.array && !array.slice){
          callback = array.callback || array.done || array.ready;
          simultaneously = array.simultaneously;
          array = array.array;
      }
      simultaneously = simultaneously || false;
      if(simultaneously){
        var l = array.length, ready = 0, i = 0;
        for( ; i<l; i++){
            mw.reload_module(array[i], function(){
                ready++;
                if(ready === l && callback){
                    callback.call();
                }
            });
        }
      }
      else{
        if(array.length === 0){
            if(callback){
                callback.call()
            }
        }
        else{
            mw.reload_module(array[0], function(){
                array.shift();
                mw.reload_modules(array, callback, false);
            });
        }
      }
  };
  mw.reload_module_everywhere = function(module) {
    mw.tools.eachWindow(function () {
        if(this.mw && this.mw.reload_module){
            this.mw.reload_module(module)
        }
    })
  }
  mw.reload_module = function(module, callback) {
    if(module.constructor === [].constructor){
        var l = module.length, i=0, w = 1;
        for( ; i<l; i++){
          mw.reload_module(module[i], function(){
            w++;
            if(w === l && typeof callback === 'function'){
              callback.call();
            }
          });
        }
        return false;
    }
    var done = callback || false;
    if (typeof module != 'undefined') {
      if (typeof module == 'object') {
        mw._({
          selector: module,
          done:done
        });
      } else {
        var module_name = module.toString();
        var refresh_modules_explode = module_name.split(",");
        for (var i = 0; i < refresh_modules_explode.length; i++) {
          var module = refresh_modules_explode[i];
          if (typeof module != 'undefined') {
		    var module = module.replace(/##/g, '#');
            var m = mw.$(".module[data-type='" + module + "']");
            if (m.length === 0) {
                try { var m = $(module); }  catch(e) {};
             }
             m.each(function() {
                mw._({
                  selector: this,
                  done:done
                });
            });
          }
        }
      }
    }
  }

  mw.clear_cache = function() {
    $.ajax({
      url: mw.settings.site_url+'api/clearcache',
      type: "POST",
      success: function(data){
        if(mw.notification != undefined){
          mw.notification.msg(data);
        }
      }
    });
  }
  mw.temp_reload_module_queue_holder = [];




  mw["_"] = function(obj, sendSpecific, DONOTREPLACE) {
    if(mw.on != undefined){
        mw.on.DOMChangePause = true;
    }
    var DONOTREPLACE = DONOTREPLACE || false;
    var sendSpecific = sendSpecific || false;
    var url = typeof obj.url !== 'undefined' ? obj.url : mw.settings.site_url+'module/';
    var selector = typeof obj.selector !=='undefined' ? obj.selector : '';
    var params =  typeof obj.params !=='undefined' ? obj.params : {};
    var to_send = params;
    if(typeof $(obj.selector)[0] === 'undefined') {
        mw.pauseSave = false;
        mw.on.DOMChangePause = false;
        return false;
    }
    if(mw.session != undefined){
        mw.session.checkPause = true;
    }
    var node = $(obj.selector)[0]
    var attrs = node.attributes;



     // wait between many reloads
      if(typeof(node.id) != 'undefined' && typeof(node.id) != 'null') {
          if ( mw.temp_reload_module_queue_holder.indexOf(node.id) == -1){
          mw.temp_reload_module_queue_holder.push(node.id);
              setTimeout(function() {
                  var reload_index = mw.temp_reload_module_queue_holder.indexOf(node.id);
                  delete mw.temp_reload_module_queue_holder[reload_index];
              }, 100);
          } else {
              return;
          }

       }

    if (sendSpecific) {
      attrs["class"] !== undefined ? to_send["class"] = attrs["class"].nodeValue : ""
      attrs["data-module-name"] !== undefined ? to_send["data-module-name"] = attrs["data-module-name"].nodeValue : "";
      attrs["data-type"] !== undefined ? to_send["data-type"] = attrs["data-type"].nodeValue : "";
      attrs["type"] !== undefined ? to_send["type"] = attrs["type"].nodeValue : "";
      attrs["template"] !== undefined ? to_send["template"] = attrs["template"].nodeValue : "";
      attrs["ondrop"] !== undefined ? to_send["ondrop"] = attrs["ondrop"].nodeValue : "";
    }
    else {
      for (var i in attrs) {
  		  if(attrs[i] != undefined){
              var name = attrs[i].name;
              var val = attrs[i].nodeValue;
              if(typeof to_send[name] === 'undefined'){
                  to_send[name]  = val;
              }
  		  }
      }
    }
    var b = true;
    for (var a in to_send) {
       if(to_send.hasOwnProperty(a)) { var b = false; };
    }
    if(b){
      mw.tools.removeClass(mwd.body, 'loading');
      mw.pauseSave = false;
      mw.on.DOMChangePause = false;
      return false;
    }

    var xhr = $.post(url, to_send, function(data) {

      if(mw.session != undefined){
        mw.session.checkPause = false;
      }
      if(DONOTREPLACE){
          obj.done.call($(selector)[0], data);
          mw.tools.removeClass(mwd.body, 'loading');
          mw.pauseSave = false;
          mw.on.DOMChangePause = false;
          return false;
      }

      var docdata = mw.tools.parseHtml(data);
      if(typeof to_send.id  !== 'undefined'){
         var id = to_send.id;
      }
      else{
        var id = docdata.body.querySelector(['id']);
      }
      mw.$(selector).replaceWith($(docdata.body).html());
      setTimeout(function(){
        mw.trigger('moduleLoaded');
      }, 33)
      if(!id){ mw.pauseSave = false;mw.on.DOMChangePause = false;  return false; }

      typeof mw.resizable_columns === 'function' ? mw.resizable_columns() : '';
      typeof mw.drag !== 'undefined' ? mw.drag.fix_placeholders(true) : '';
      var m = mwd.getElementById(id);
      typeof obj.done === 'function' ? obj.done.call(selector, m) : '';
      if(!!mw.wysiwyg){
        $(m).hasClass("module") ? mw.wysiwyg.init_editables(m) : '' ;
          if(!!mw.on){
            mw.on.moduleReload(id, "", true);
          }
      }
      if(mw.on != undefined){
        mw.on.DOMChangePause = false;
      }
      mw.tools.removeClass(mwd.body, 'loading');


    })
    .fail(function(){
       mw.pauseSave = false;
       typeof obj.fail === 'function' ? obj.fail.call(selector) : '';
    })
    .always(function(){
        mw.pauseSave = false;
    });
    return xhr;
  }


  api = function(action, params, callback){
    var url = mw.settings.api_url + action;
    var type = typeof params;
    if(type === 'string'){
        var obj = mw.serializeFields(params);
    }
    else if(type === 'object' && !jQuery.isArray(params)){
        var obj = params;
    }
    else{
      obj = {};
    }
    $.post(url, obj, function(data){
       if(typeof callback === 'function'){
          callback.call(data);
       }
    }).fail(function(){

    });
  }

  mw.log = d = function(what) {
    if (window.console && mw.settings.debug) {
      top.console.log(what);
    }
  }

  mw.$ = function(selector, context) {
    if(typeof selector === 'object'){ return jQuery(selector); }
    var context = context || mwd;
    if (typeof mwd.querySelector !== 'undefined') {
      if (typeof selector === 'string') {
        try {
          return jQuery(context.querySelectorAll(selector));
        } catch (e) {
          return jQuery(selector, context);
        }
      } else {
        return jQuery(selector, context);
      }
    } else {
      return jQuery(selector, context);
    }
  };

  mw.get = function(action, params, callback){
    var url = mw.settings.api_url + action;
    var type = typeof params;
    if(type === 'string'){
        var obj = mw.serializeFields(params);
    }
    else if(type.constructor === {}.constructor ){
        var obj = params;
    }
    else{
      var obj = {};
    }
    $.post(url, obj)
        .success(function(data) { return typeof callback === 'function' ? callback.call(data) : data;   })
        .error(function(data) { return typeof callback === 'function' ? callback.call(data) : data;  });
  }

  get_content = function(params, callback){
    var obj = mw.url.getUrlParams("?"+params);
    if(typeof callback!='function'){
       mw.get('get_content_admin', obj);
    }
    else{
       mw.get('get_content_admin', obj, function(){callback.call(this)});
    }
  }

  mw.get_content = get_content

  mw.serializeFields =  function(id, ignorenopost){
        var ignorenopost = ignorenopost || false;
        var el = mw.$(id);
        var fields = "input[type='text'], input[type='email'], input[type='number'], input[type='tel'], "
                    + "input[type='password'], input[type='hidden'], input[type='datetime'], input[type='date'], input[type='time'], "
                    +"input[type='email'],  textarea, select, input[type='checkbox']:checked, input[type='radio']:checked, "
                    +"input[type='checkbox'][data-value-checked][data-value-unchecked]";
        var data = {}
        $(fields, el).each(function(){
            if(!this.name){
                console.warn('Name attribute missing on ' + this.outerHTML);
            }
            if((!$(this).hasClass('no-post') || ignorenopost) && !this.disabled && this.name && typeof this.name != 'undefined'){
              var el = this, _el = $(el);
              var val = _el.val();
              var name = el.name;
              if(el.name.contains("[]")){
                  data[name] = data[name] || []
                  data[name].push(val);
              }
              else if(el.type === 'checkbox' && el.getAttribute('data-value-checked') ){
                  data[name] = el.checked ? el.getAttribute('data-value-checked') : el.getAttribute('data-value-unchecked');
              }
              else{
                data[name] = val;
              }
            }
        });
        return data;
   }

mw.response = function(form, data, messages_at_the_bottom){
    var messages_at_the_bottom = messages_at_the_bottom || false;
    if(data == null  ||  typeof data == 'undefined' ){
      return false;
    }

    var data = mw.tools.toJSON(data);
    if(typeof data == 'undefined'){
          return false;
      }

    if(typeof data.error !== 'undefined'){
        mw._response.error(form, data, messages_at_the_bottom);
        return false;
    }
    else if(typeof data.success !== 'undefined'){
        mw._response.success(form, data, messages_at_the_bottom);
        return true;
    }
    else if(typeof data.warning !== 'undefined'){
        mw._response.warning(form, data, messages_at_the_bottom);
        return false;
    }
    else{
        return false;
    }
}

mw._response = {
  error:function(form, data, _msg){
    var form = mw.$(form);
    var err_holder = mw._response.msgHolder(form, 'error');
    mw._response.createHTML(data.error, err_holder);
  },
  success:function(form, data, _msg){
    var form = mw.$(form);
    var err_holder = mw._response.msgHolder(form, 'success');
    mw._response.createHTML(data.success, err_holder);
  },
  warning:function(form, data, _msg){
    var form = mw.$(form);
    var err_holder = mw._response.msgHolder(form, 'warning');
    mw._response.createHTML(data.warning, err_holder);
  },
  msgHolder : function(form, type, method){
    var method = method || 'append';
    var err_holder = form.find(".alert:first");
    if(err_holder.length==0){
        var err_holder = mwd.createElement('div');
        form[method](err_holder);
    }

    var bootrap_error_type = 'default';
    if(type == 'error'){
    bootrap_error_type = 'danger';
    } else if(type == 'done'){
    bootrap_error_type = 'info';
    }


    $(err_holder).empty().attr("class", 'alert alert-' + type + ' alert-' + bootrap_error_type + ' ');
    return err_holder;
  },
  createHTML:function(data, holder){
    var i, html = "";


    if(typeof data === 'string'){
        html+= data.toString();
    }
    else{
      for( i in data){
          if(typeof data[i] === 'string'){
              html+='<li>'+data[i]+'</li>';
          }
          else if(typeof data[i] === 'object'){
            $.each(data[i], function(){
                html+='<li>'+this+'</li>';
            })
          }
      }
    }
    mw.$(holder).eq(0).append('<ul class="mw-error-list">'+html+'</ul>');
    mw.$(holder).eq(0).show();
  }
}
 
 
mw._one = {};
mw._ones = {};

mw.one = function(name, func){
  if(mw._one[name] === undefined){ mw._one[name] = true; }
  if(typeof func === 'function'){
    if(mw._one[name] === true){
      mw._one[name] = false;
      func.call();
      mw.on.one(name, func, true);
    }
  }
  else{
    if(func == 'ready' || func == 'done' || func === true){
      mw._one[name] = true;
      mw.on.one(name, func, true, true);
    }
  }
}
mw.user = {
  isLogged:function(callback){
    $.post(mw.settings.api_url + 'is_logged', function(data){
        var isLogged =  (data == 'true');
        callback.call(isLogged, isLogged);
    });
  }
}


mw.required.push("<?php print mw_includes_url(); ?>api/jquery.js");
mw.required.push("<?php print mw_includes_url(); ?>api/tools.js");
mw.required.push("<?php print mw_includes_url(); ?>api/css_parser.js");
mw.required.push("<?php print mw_includes_url(); ?>api/files.js");
mw.required.push("<?php print mw_includes_url(); ?>api/forms.js");
mw.required.push("<?php print mw_includes_url(); ?>api/url.js");
mw.required.push("<?php print mw_includes_url(); ?>api/events.js");
//mw.required.push("<?php print mw_includes_url(); ?>api/upgrades.js");
mw.required.push("<?php print mw_includes_url(); ?>api/session.js");
mw.required.push("<?php print mw_includes_url(); ?>api/shop.js");
mw.required.push("<?php print mw_includes_url(); ?>api/common.js");


<?php  // include "jquery.js";  ?>
<?php  include "tools.js"; ?>
<?php  include "css_parser.js"; ?>
<?php  include "files.js"; ?>
<?php  include "forms.js"; ?>
<?php  include "url.js"; ?>
<?php  include "events.js"; ?>
<?php  include "shop.js"; ?>
<?php  include "common.js"; ?>






<?php  //include "upgrades.js"; ?>

<?php  include "session.js"; ?>