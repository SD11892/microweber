// JavaScript Document

mw.module_pictures = {
    after_upload: function (data) {
        $.post(mw.settings.api_url + 'save_media', data,
            function (data) {

            });
    },

    after_change: function (data) {
        setTimeout(function () {
            mw.reload_module('pictures');
            mw.reload_module_parent('pictures');
            mw.reload_module_parent('posts');
            mw.reload_module_parent('shop/products');
            mw.reload_module_parent("pictures/admin");
        }, 500)

    },

    save_title: function (id, title) {
        var data = {};
        data.id = id;
        data.title = title;
        $.post(mw.settings.api_url + 'save_media', data,
            function (data) {
                mw.reload_module_parent('pictures');
            });
    },
	save_tags: function (id, tags) {
        var data = {};
        data.id = id;
        data.tags = tags;
        $.post(mw.settings.api_url + 'save_media', data,
            function (data) {
                mw.reload_module_parent('pictures');
                mw.reload_module_parent('tags');
            });
    },
    del: function (id) {
        if(typeof id === 'string'){
          if (confirm('Are you sure you want to delete this image?')) {
              $.post(mw.settings.api_url + 'delete_media', { id: id  }, function (data) {
                  $('.admin-thumb-item-' + id).fadeOut();
                  mw.module_pictures.after_change()
              });
          }
        }
        else{
          if (confirm('Are you sure you want to delete selected images?')) {
              $.post(mw.settings.api_url + 'delete_media', { ids: id  }, function (data) {
                $.each(id, function(){
                  $('.admin-thumb-item-' + this).fadeOut(); 
                })

                  mw.module_pictures.after_change()
              });
          }
        }

    },
    init: function (selector) {
        var el = $(selector);
        el.sortable({
            items: ".admin-thumb-item",
            placeholder: 'admin-thumb-item-placeholder',
            update: function () {
                var serial = el.sortable('serialize');
                $.post(mw.settings.api_url + 'reorder_media', serial,
                    function (data) {
                        mw.module_pictures.after_change()
                    });
            }
        });
    }
}
