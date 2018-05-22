<?php event_trigger('mw.admin.dashboard.start'); ?>
<div class="mw-ui-col-container">
    <div class="admin-side-content">
        <?php event_trigger('mw.admin.dashboard.content'); ?>


        <?php
        $last_messages = mw()->forms_manager->get_entires();
        if ($last_messages == null) {
            $last_messages = 0;
        }
        ?>
        <div class="dashboard-recent">
            <div class="dr-head">
                <span class="drh-activity-name"><i class="mai-mail"></i> <?php _e("Recent Messages") ?></span>
                <a href="<?php print admin_url('view:modules/load_module:contact_form'); ?>" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info"><strong><?php print count($last_messages); ?></strong> <?php print _e('Go to Messages'); ?></a>
            </div>
            <div class="dr-list">
                <div class="messages-holder">
                    <?php include('recent_messages.php'); ?>
                </div>
            </div>
        </div>

        <div class="mw-ui-box quick-lists pull-left">
            <div class="mw-ui-box-header">
                <i class="mai-link"></i> <?php _e("Quick Links"); ?>
            </div>

            <div class="mw-ui-box-content">
                <div class="mw-ui-row" id="quick-links-row">
                    <div class="mw-ui-col">
                        <div class="mw-ui-col-container">
                            <div class="mw-ui-navigation">
                                <?php event_trigger('mw.admin.dashboard.links'); ?>
                                <?php $dash_menu = mw()->ui->module('admin.dashboard.menu'); ?>
                                <?php if (!empty($dash_menu)): ?>
                                    <?php foreach ($dash_menu as $item): ?>
                                        <?php $view = (isset($item['view']) ? $item['view'] : false); ?>
                                        <?php $link = (isset($item['link']) ? $item['link'] : false); ?>
                                        <?php if ($view == false and $link != false) {
                                            $btnurl = $link;
                                        } else {
                                            $btnurl = admin_url('view:') . $item['view'];
                                        } ?>
                                        <?php $icon = (isset($item['icon_class']) ? $item['icon_class'] : false); ?>
                                        <?php $text = $item['text']; ?>
                                        <a href="<?php print $btnurl; ?>"><span
                                                    class="<?php print $icon; ?>"></span><span><?php print $text; ?></span></a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mw-ui-col">
                        <div class="mw-ui-col-container">
                            <div class="mw-ui-navigation">
                                <?php $dash_menu = mw()->ui->module('admin.dashboard.menu.second'); ?>
                                <?php if (!empty($dash_menu)): ?>
                                    <?php foreach ($dash_menu as $item): ?>
                                        <?php $view = (isset($item['view']) ? $item['view'] : false); ?>
                                        <?php $link = (isset($item['link']) ? $item['link'] : false); ?>
                                        <?php if ($view == false and $link != false) {
                                            $btnurl = $link;
                                        } else {
                                            $btnurl = admin_url('view:') . $item['view'];
                                        } ?>
                                        <?php $icon = (isset($item['icon_class']) ? $item['icon_class'] : false); ?>
                                        <?php $text = $item['text']; ?>
                                        <a href="<?php print $btnurl; ?>"><span
                                                    class="<?php print $icon; ?>"></span><span><?php print $text; ?></span></a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php event_trigger('mw.admin.dashboard.links2'); ?>
                                <?php event_trigger('mw.admin.dashboard.help'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="mw-ui-col">
                        <div class="mw-ui-col-container">
                            <?php if (mw()->ui->enable_service_links): ?>
                                <div class="mw-ui-navigation">
                                    <?php $dash_menu = mw()->ui->module('admin.dashboard.menu.third'); ?>
                                    <?php if (!empty($dash_menu)): ?>
                                        <?php foreach ($dash_menu as $item): ?>
                                            <?php $view = (isset($item['view']) ? $item['view'] : false); ?>
                                            <?php $link = (isset($item['link']) ? $item['link'] : false); ?>
                                            <?php if ($view == false and $link != false) {
                                                $btnurl = $link;
                                            } else {
                                                $btnurl = admin_url('view:') . $item['view'];
                                            } ?>
                                            <?php $icon = (isset($item['icon_class']) ? $item['icon_class'] : false); ?>
                                            <?php $text = $item['text']; ?>
                                            <a href="<?php print $btnurl; ?>"><span
                                                        class="<?php print $icon; ?>"></span><span><?php print $text; ?></span></a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <?php event_trigger('mw.admin.dashboard.links3'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php event_trigger('mw.admin.dashboard.main'); ?>
    </div>
</div>