<?php
/*if (!user_can_access('module.modules.index')) {
    return;
}*/
?>

<?php $load_module = url_param('load_module');
if ($load_module == true): ?>
    <?php
    $mod = str_replace('___', DS, $load_module);
    $mod = load_module($mod, $attrs = array('view' => 'admin', 'backend' => 'true'));
    print $mod;
    ?>
<?php else: ?>
    <?php
    $mod_params = array();
    $mod_params['ui'] = 'any';
    if (isset($params['reload_modules'])) {
        $s = 'skip_cache=1';
        if (isset($params['cleanup_db'])) {
            $s .= '&cleanup_db=1';
            $s .= '&reload_modules=1';
        }

        $mods = scan_for_modules($s);
    }

    if (isset($params['category'])) {
        $mod_params['category'] = $params['category'];
    }

    if (isset($params['keyword'])) {
        $mod_params['keyword'] = $params['keyword'];
    }

    if (isset($params['search-keyword'])) {
        $mod_params['keyword'] = $params['search-keyword'];
    }

    if (isset($params['show-ui'])) {
        if ($params['show-ui'] == 'admin') {
            $mod_params['ui_admin'] = '1';
        } else if ($params['show-ui'] == 'live_edit') {
            $mod_params['ui'] = '1';
        }
    }

    if (isset($params['installed'])) {
        $mod_params['installed'] = $params['installed'];
    } else {
        $mod_params['installed'] = 1;
    }

    if (isset($params['install_new'])) {
        $update_api = new \Microweber\Update();
        $result = $update_api->get_modules();
        $mods = $result;
    } else {
        $mods = mw()->module_manager->get($mod_params);
    }

    $allowMods = [];
    foreach ($mods as $mod) {
        if (!user_can_view_module($mod)) {
            continue;
        }
        $allowMods[] = $mod;
    }
    $mods = $allowMods;

    $upds = false;
    ?>

    <script>mw.lib.require('mwui_init');</script>

    <style>
        .mw-module-installed-0 {
            opacity: 0.6;
        }
    </style>

    <?php if (isset($mods) and is_array($mods) == true and $mods == true): ?>
        <div class="row mw-modules">
            <?php if (is_array($upds) == true): ?>
                <?php foreach ($upds as $upd_mod): ?>
                    <div class="col-xl-3 col-md-4 col-6 mb-3">
                        <?php if (isset($upd_mod['module'])): ?>
                            <?php $item = module_info($upd_mod['module']); ?>

                            <?php if (isset($item['id'])): ?>
                                <div class="mw-admin-module-list-item mw-module-installed-<?php print $item['installed'] ?> h-100" id="module-db-id-<?php print $item['id'] ?>">
                                    <module type="admin/modules/edit_module" data-module-id="<?php print $item['id'] ?>" class="h-100"/>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php foreach ($mods as $k => $item): ?>
                <?php if (!isset($item['id'])): ?>
                    <div class="col-xl-3 col-md-4 col-6 mb-3">
                        <div class="mw-admin-module-list-item mw-module-not-installed h-100" id="module-remote-id-<?php print $item['id'] ?>">
                            <div class=" module module-admin-modules-edit-module h-100">
                                <?php
                                if (isset($item[0]) and is_array($item[0])) {
                                    $item = $item[0];
                                }

                                $data = $item;
                                include($config["path"] . 'update_module.php'); ?>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="col-xl-3 col-md-4 col-6 mb-3">
                        <div class="mw-admin-module-list-item mw-module-installed-<?php print $item['installed'] ?> h-100" id="module-db-id-<?php print $item['id'] ?>">
                            <module type="admin/modules/edit_module" data-module-id="<?php print $item['id'] ?>" class="h-100"/>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="card style-1 h-100 mw-modules-module-holder">
            <div class="card-body h-100 d-flex align-items-center justify-content-center flex-column">
                <div class="icon-title">
                    <i class="mdi mdi-view-grid-plus"></i> <h5 class="mb-0"><?php _e("No modules found"); ?></h5>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>