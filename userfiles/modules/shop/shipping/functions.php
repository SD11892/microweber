<?php



event_bind('mw.admin.shop.settings.menu', function ($data) {
    print ' <div class="col-12 col-sm-6 col-lg-4">
                <a href="?group=shipping" class="d-flex my-3">
                            <div class="icon-holder"><i class="mdi mdi-truck-outline mdi-20px"></i></div>

                    <div class="info-holder">
                        <span class="text-primary font-weight-bold">' . _e('Shipping', true) . '</span><br/>
                        <small class="text-muted">Delivery methods and suppliers</small>
                    </div>
                </a>
            </div>';
});