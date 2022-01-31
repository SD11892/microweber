<template id="backup-modal">

    <div class="mw-backup" id="mw-backup-type">

        <div class="mw-backup-options">
            <br/>
            <h2 style="font-weight: bold">Want do you want to backup?</h2>
            <br/>

            <label class="mw-ui-check mw-backup-option">
                <div class="option-radio">
                    <input type="radio" name="backup_by_type" checked="checked" value="content"/>
                    <span></span>
                </div>
                <h3>Content backup</h3>
                <p>Create backup of your sites without <b>sensitive</b> information
                    <br/>
                    <small class="text-muted">
                        This will create a zip with live-edit css, media, post categories & pages.
                    </small>
                </p>
            </label>


            <label class="mw-ui-check mw-backup-option">
                <div class="option-radio">
                    <input type="radio" name="backup_by_type" value="custom"/>
                    <span></span>
                </div>
                <h3>Custom backup</h3>
                <p>Create backup with custom selected tables, media, modules & templates
                    <br/>
                    <small class="text-muted">
                        You can select one by one information included in zip.
                    </small>
                </p>
            </label>


            <label class="mw-ui-check mw-backup-option active">
                <div class="option-radio">
                    <input type="radio" name="backup_by_type" value="full"/>
                    <span></span>
                </div>
                <h3>Full backup</h3>
                <p>
                    Create full backup of all database tables, system settings and options
                    <br/>
                    <small class="text-muted">
                        Include <b>sensitive</b> information like users, <b>passwords</b>, <b>api keys</b>, settings.
                    </small>
                </p>

            </label>


        </div>

        <div style="margin-bottom:20px;" class="js-backup-import-installation-language-wrapper"></div>
        <div class="backup-import-modal-log-progress"></div>

        <div class="mw-backup-buttons">
            <a class="btn btn-link button-cancel" onClick="mw.backup.close_modal();">Close</a>
            <button class="btn btn-primary btn-rounded button-start" onclick="mw.backup.next()" type="submit">Next
            </button>
        </div>

    </div>

    <div id="mw-backup-custom" style="display:none">

        <div class="js-toggle-backup-select-items">

            <div class="card style-1 mb-3 card-collapse">
                <div class="card-header no-border cursor-pointer" data-toggle="collapse" data-target="#include-modules">
                    <h6><i class="mdi mdi-view-grid-plus text-primary mr-2"></i> <strong><?php _e("Database Tables"); ?></strong></h6>
                </div>

                <div class="card-body py-0">
                    <div class="collapse pb-4" id="include-modules">
                        <div style="width:100%;  ">
                            <ul class="mw-ui-inline-list">
                                <?php
                                $tablesList = mw()->database_manager->get_tables_list(true);
                                foreach ($tablesList as $tableName):
                                    ?>
                                    <li style="width: 100%;">
                                        <label class="mw-ui-check">
                                            <input type="checkbox" class="js-backup-tables" name="include_tables[]" value="<?php echo $tableName; ?>">
                                            <span></span><span><?php echo $tableName; ?></span>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card style-1 mb-3 card-collapse">
                <div class="card-header no-border cursor-pointer" data-toggle="collapse" data-target="#include-modules">
                    <h6><i class="mdi mdi-view-grid-plus text-primary mr-2"></i> <strong><?php _e("Include Modules"); ?></strong></h6>
                </div>

                <div class="card-body py-0">
                    <div class="collapse pb-4" id="include-modules">
                        <div style="width:100%;  ">
                            <ul class="mw-ui-inline-list">
                                <?php
                                $modules = get_modules('order_by=module asc');
                                foreach ($modules as $module):
                                    ?>
                                    <li style="width: 100%;">
                                        <label class="mw-ui-check">
                                            <input type="checkbox" class="js-export-modules" name="include_modules[]" value="<?php echo $module['module']; ?>">
                                            <span></span><span><?php _e($module['name']); ?></span>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card style-1 mb-3 card-collapse">
                <div class="card-header no-border cursor-pointer" data-toggle="collapse" data-target="#include-templates">
                    <h6><i class="mdi mdi-pencil-ruler text-primary mr-2"></i> <strong><?php _e("Include Templates"); ?></strong></h6>
                </div>

                <div class="card-body py-0">
                    <div class="collapse pb-4" id="include-templates">
                        <div style="width:100%;">
                            <ul class="mw-ui-inline-list">
                                <?php
                                $templates = site_templates();
                                foreach ($templates as $template):
                                    ?>
                                    <li style="width: 100%;">
                                        <label class="mw-ui-check">
                                            <input type="checkbox" class="js-export-templates" name="include_templates[]" value="<?php echo $template['dir_name']; ?>">
                                            <span></span><span><?php echo $template['name']; ?></span>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mw-backup-buttons">
            <a class="btn btn-link button-cancel" onClick="mw.backup.choice_tab();">Back</a>
            <button class="btn btn-primary btn-rounded button-start" onclick="mw.backup.start()" type="submit">

            </button>
        </div>

    </div>

</template>
