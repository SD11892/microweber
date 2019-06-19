var uploader = mw.files.uploader({
    filetypes: "zip, sql, json, csv, xls, xml",
    multiple: false
});

_mw_log_reload_int = false;
$(document).ready(function () {

    mw.$("#mw_uploader").append(uploader);
    $(uploader).bind("FileUploaded", function (obj, data) {
    	
    	mw.backup_import.upload(data.src);
    	
        mw.$("#mw_uploader_loading").hide();
        mw.$("#mw_uploader").show();
        mw.$("#upload_file_info").html("");
    });

    $(uploader).bind('progress', function (up, file) {
        mw.$("#mw_uploader").hide();
        mw.$("#mw_uploader_loading").show();
        mw.tools.disable(mwd.getElementById('mw_uploader_loading'), 'Uploading file...<span id="upload_file_info"></span>');
        mw.$("#upload_file_info").html(file.percent + "%");
    });
    
    $(uploader).bind('error', function (up, file) {
        mw.notification.error("The backup must be sql or zip.");
    });
    
});