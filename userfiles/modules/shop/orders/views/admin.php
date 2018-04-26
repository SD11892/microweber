<?php

use Microweber\View;

?>
<div id="mw-order-table-holder">
    <?php if ($orders_type == 'completed' and isset($orders) and is_array($orders)) : ?>
        <div class="orders-holder" id="shop-orders">
            <?php if ($has_new): ?>
                <div class="you-have-new-orders">
                    <p class="new-orders p-b-10 p-t-10 ">You have <?php //print $notif_html; ?> new orders</p>

                    <div class="mw-ui-box mw-ui-box-content mw-ui-box-notification new-notification m-b-10"><span
                            class="mai-shop"></span> <strong>You have <?php //print $notif_html; ?> new orders</strong>
                        <button class="close-alert"><i class="mw-icon-close"></i></button>
                    </div>

                    <?php

                    $view_file = __DIR__ . '/partials/orders-list.php';

                    $view = new View($view_file);
                    $view->assign('params', $params);

                    $view->assign('orders', $new_orders);
                    print    $view->display();

                    ?>
                </div>
            <?php endif; ?>

            <?php if (!$has_new): ?>
                <div class="mw-ui-box mw-ui-box-content mw-ui-box-warn new-warn m-t-10 p-b-10 p-t-10"><span class="mai-shop"></span>
                    <strong>No new orders</strong>
                    <button class="close-alert"><i class="mw-icon-close"></i></button>
                </div>
            <?php endif; ?>
            <p class="bold p-b-10 p-t-10 m-t-20"><?php print _e('List of all orders'); ?></p>
            <?php

            $view_file = __DIR__ . '/partials/orders-list.php';

            $view = new View($view_file);
            $view->assign('params', $params);

            $view->assign('orders', $orders);
            print    $view->display();

            ?>
        </div>

        <div class="mw-clear"></div>

        <br/>


        <div class="export-label">
            <span><?php _e("Export data"); ?>:</span>
            <a class="mw-ui-btn mw-ui-btn-small" href="javascript:export_orders_to_excel()"><?php _e("Excel"); ?></a>
        </div>
    <?php elseif ($orders_type == 'carts' and isset($orders) and is_array($orders)) : ?>
        <label
            class="mw-ui-label"><?php _e("Abandoned Carts Section helps you analyze why some customers aren't checking out."); ?></label>
        <div class="mw-ui-box-content">
            <div id="orders_stat" style="height: 250px;"></div>
        </div>


    <?php else: ?>
        <div class="orders-holder mw-ui-box mw-ui-box-content">
            <div class="you-have-new-orders p-40">
                <p class="no-new-orders">
                    <span class="mai-shop"></span><br/>
                    <?php _e("You don't have any orders"); ?>
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>
<script>
    export_orders_to_excel = function (id) {
        data = {}
        $.post(mw.settings.api_url + 'shop/export_orders', data,
            function (resp) {
                mw.notification.msg(resp);
                if (resp.download != undefined) {
                    window.location = resp.download;
                }
            });
    }
</script>