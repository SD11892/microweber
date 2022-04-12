<module type="admin/modules/info"/>

<style>
    #import .nav-tabs {
        height: 55px;
        padding-left: 10px;
    }
    #import .nav-tabs li {
        height: 55px;
        line-height: 55px;
        margin: 0px 1px 0px 1px;
    }
    #import .nav-tabs li a {
        height: 55px;
        outline: none;
        cursor: pointer;
        width: 233px;
    }
    #import .nav-tabs li a {
        background: #f9f9f9;
        border: 1px solid #e9e9e9;
        border-bottom: 1px solid #dddddd;
    }
    #import .nav-tabs .nav-link.active {
        background: #fff;
        border-bottom: 1px solid #fff;
    }

    #import .nav-tabs .nav-link.active,#import .nav-tabs .nav-link.active i, #import .nav-tabs .nav-item.show .nav-link {
        color: #000;
    }

    #import .nav-tabs li a span.number {
        width: 25px;
        height: 15px;
        line-height: 15px;
        float: left;
        margin-right: 10px;
        margin-top: 10px;
        padding: 0px 5px;
        color: #BCBCBC;
        font-size: 32px;
        font-weight: bold;
    }
    #import .nav-tabs li a span.tab-name {
        display: block;
        font-weight: bold;
        min-width: 180px;
        line-height: 20px;
    }
    #import .nav-tabs > li > a {
        color: #a5a5a5;
    }
    #import .nav-tabs li a i {
        display: block;
        font-size: 10px;
        font-style: normal;
        line-height: 10px;
        font-weight: normal;
    }
</style>

<div class="card style-1 mb-3">

    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="admin/import_export_tool"/>
    </div>

    <div class="card-body pt-3" id="import">
        <ul class="nav nav-tabs" id="myTab" role="tablist">

        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                <span class="number">1</span>
                <span class="tab-name">Import setting</span>
                <i>Main feed setting</i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                <span class="number">2</span>
                <span class="tab-name">Tag settings</span>
                <i>Assign tags to content data</i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">
                <span class="number">3</span>
                <span class="tab-name">Import / CRON links</span>
                <i>Start import or get CRON link</i>
            </a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="card mt-2">
                <div class="card-body">

                    <table class="table table-borderless">
                        <tbody>
                        <tr>
                            <td><label for="feed_url"><b>XML link</b></label><br><small>Link to XML file</small></td>
                            <td>
                                <input type="text" class="form-control" name="feed_data[xml_url]" id="feed_url" value="https://example.com/feed.xml">
                                <input type="button" class="btn btn-primary" id="download_xml" value="Download">
                                <span id="xml_url_result" class="icon-result-success"></span>
                                <span id="xml_url_result_text">Last downloaded: 31.03.2022 08:51:04</span>
                                <span class="help tooltip tooltip-download tooltipstered"></span>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="feed_download_image_1"><b>Download images</b></label><br><small>Download and check images</small></td>
                            <td>
                                <input type="radio" name="feed_data[download_image]" id="feed_download_image_1" value="1" checked="checked"> <label for="feed_download_image_1">Yes</label>
                                <input type="radio" name="feed_data[download_image]" id="feed_download_image_0" value="0"> <label for="feed_download_image_0">No</label>
                                <span class="help tooltip tooltipstered"></span>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="feed_parts"><b>Import parts</b></label><br><small>Split importing</small></td>
                            <td>
                                <select class="form-control" name="feed_data[parts]" id="feed_parts" onchange="chageCronLinks(this.value);">
                                    <option value="1" selected="selected">1 part(s)</option>';
                                    <option value="2">2 part(s)</option>';
                                    <option value="3">3 part(s)</option>';
                                    <option value="4">4 part(s)</option>';
                                    <option value="5">5 part(s)</option>';
                                    <option value="6">6 part(s)</option>';
                                    <option value="7">7 part(s)</option>';
                                    <option value="8">8 part(s)</option>';
                                    <option value="9">9 part(s)</option>';
                                    <option value="10">10 part(s)</option>';
                                </select>
                                <span class="help tooltip tooltipstered"></span>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="feed_content_tag"><b>Content tag</b></label><br><small>Repeat content tag with elements</small></td>
                            <td>
                                <select class="form-control" name="feed_data[product_tag]" id="feed_content_tag">
                                    <option value="rss">rss</option>
                                    <option value="rss;channel;title">rss &gt; channel &gt; title</option>
                                </select>
                                <span class="help tooltip tooltipstered"></span>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="feed_primary_key"><b>Primary key</b></label><br><small>Unique Content ID or Content Model</small></td>
                            <td>
                                <select class="form-control" name="feed_data[primary_key]" id="feed_primary_key">
                                    <option value="content_id" selected="selected">Content ID</option>
                                    <option value="model">Content model</option>
                                    <option value="sku">SKU</option>
                                </select>
                                <span class="help tooltip tooltipstered"></span>
                            </td>
                        </tr>


                        <tr>
                            <td><label for="feed_data_old_content_action"><b>Old content</b></label><br><small>Content which are in your site but not in xml anymore</small></td>
                            <td>
                                <select class="form-control" name="feed_data[old_content_action]" id="feed_data_old_content_action">
                                    <option value="nothing" selected="selected">Do nothing</option>
                                    <option value="delete">Delete</option>
                                    <option value="invisible">Invisible</option>
                                </select>
                                <span class="help tooltip tooltipstered"></span>
                            </td>
                        </tr>


                        <tr>
                            <td><label for="feed_data_update-content_name"><b>Update</b></label><br><small>Select what will be changed in update</small></td>
                            <td>
                                <div id="update-items" class="well well-sm" style="height: 180px; overflow: auto;">
                                    <div>
                                        <input type="checkbox" name="feed_data[update_items][]" value="description" id="feed_data_update-description" checked="checked"> <label for="feed_data_update-description">Description</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" name="feed_data[update_items][]" value="category" id="feed_data_update-category" checked="checked"> <label for="feed_data_update-category">Category</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" name="feed_data[update_items][]" value="image" id="feed_data_update-image" checked="checked"> <label for="feed_data_update-image">Images</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" name="feed_data[update_items][]" value="visible" id="feed_data_update-visible" checked="checked"> <label for="feed_data_update-visible">Visibility</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="feed_delete"><b>Delete feed</b></label><br><small>Remove this xml setting</small></td>
                            <td>
                                <a href="https://credocart:8890/admin/index.php?route=extension/module/profi_import&amp;user_token=E6YBWi6qMuujX2fp3KLX0HC8JvlxHRhC&amp;action=deleteImport&amp;include_products=false&amp;import_id=2" id="delete-import-link" class="btn btn-small btn-danger" onclick="return confirm('Are you sure to delete this import feed ?');">Delete this import</a>
                                <input type="checkbox" id="delete-import-products" value="1" onclick="switchDeleteLink($(this).prop('checked'));">
                                <label for="delete-import-products"> Delete products from this import</label>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <livewire:counter />

        </div>
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
    </div>


    </div>
</div>
