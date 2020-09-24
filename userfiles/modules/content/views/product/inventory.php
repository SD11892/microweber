<style>
    <?php if ($contentData['track_quantity']==0):?>
    .js-track-quantity {
        display: none;
    }
    <?php endif; ?>
</style>

<script>
    $(document).ready(function () {
        $('.').click(function () {
            $('.js-track-quantity').toggle();
        });
    });
</script>

<div class="card style-1 mb-3">
    <div class="card-header no-border">
        <h6><strong>Inventory</strong></h6>
    </div>

    <div class="card-body pt-3">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>SKU (Stock Keeping Unit)</label>
                    <input type="text" name="sku" class="form-control" value="<?php echo $contentData['sku']; ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Barcode (ISBN, UPC, GTIN, etc.)</label>
                    <input type="text" name="barcode" class="form-control" value="<?php echo $contentData['barcode']; ?>">
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="track_quantity" class="custom-control-input " value="1" <?php if ($contentData['track_quantity']==1):?>checked="checked"<?php endif; ?> id="customCheck2">
                        <label class="custom-control-label" for="customCheck2">Track quantity</label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck3" name="sell_oos" value="1" <?php if ($contentData['sell_oos']==1):?>checked="checked"<?php endif; ?>>
                        <label class="custom-control-label" for="customCheck3">Continue selling when out of stock</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="js-track-quantity">

            <hr class="thin no-padding"/>

            <h6><strong>Quantity</strong></h6>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Available</label>
                        <div class="input-group mb-1 append-transparent input-group-quantity">
                            <input type="text" class="form-control" name="quantity" value="<?php echo $contentData['quantity']; ?>" />
                            <div class="input-group-append">
                                <div class="input-group-text plus-minus-holder">
                                    <button type="button" class="plus"><i class="mdi mdi-menu-up"></i></button>
                                    <button type="button" class="minus"><i class="mdi mdi-menu-down"></i></button>
                                </div>
                            </div>
                        </div>

                        <small class="text-muted">How many products you have</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Max quantity per order</label>
                        <div class="input-group mb-1 append-transparent input-group-quantity">
                            <input type="text" class="form-control" name="max_quantity_per_order" value="<?php echo $contentData['max_quantity_per_order']; ?>" placeholder="No limit" />
                            <div class="input-group-append">
                                <div class="input-group-text plus-minus-holder">
                                    <button type="button" class="plus"><i class="mdi mdi-menu-up"></i></button>
                                    <button type="button" class="minus"><i class="mdi mdi-menu-down"></i></button>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted">How many products can be ordered at once</small>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
