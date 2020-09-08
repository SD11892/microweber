
<div id="link-editor"></div>

<script>
    mw.lib.require("bootstrap_select");
    mw.require("admin-new.css");
    mw.require("editor.js");

    window.linkEditor = new mw.LinkEditor({
        element: document.querySelector('#link-editor'),
        mode: 'element'
    });
    linkEditor.promise().then(function (data){
        var modal = mw.dialog.get(window.frameElement);
        if(data) {
            modal.result(data);
            modal.remove()
        } else {
            modal.remove()
        }
    });

</script>
