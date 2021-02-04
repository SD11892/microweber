<?php
/*

  type: layout

  name: Small Modal

  description: Small Modal

 */
?>

<style>
    .dropdown-menu.shopping-cart {
        min-width: 25rem;
    }


</style>

<?php $total = cart_sum(); ?>

<?php if (is_array($data)) : ?>
    <div class="products m-6">
        <?php foreach ($data as $item) : ?>
            <div class="form-row product-item align-items-center">
                <div class="col-3 d-flex item-img">
                    <?php if (isset($item['item_image']) and $item['item_image'] != false): ?>
                        <?php $p = $item['item_image']; ?>
                    <?php else: ?>
                        <?php $p = get_picture($item['rel_id']); ?>
                    <?php endif; ?>

                    <?php if ($p != false): ?>
                        <img src="<?php print thumbnail($p, 70, 70, true); ?>" alt=""/>
                    <?php endif; ?>
                </div>
                <div class="col-8">
                    <div class="form-row m-1">
                        <div class="col-12 d-flex item-title m-1">
                            <a class="" title="" href="<?php print $item['url'] ?>"><?php print $item['title'] ?></a>
                        </div>
                        <div class="col-12 d-flex item-price m-1">
                            <span class="text-small py-1"><?php print $item['qty'] ?> <span class="px-1">x</span></span>
                            <span class="p-1"><?php print currency_format($item['price']); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-1 d-flex item-action justify-content-end">
                    <a data-toggle="tooltip" title="<?php _e("Remove"); ?>" href="javascript:mw.cart.remove('<?php print $item['id'] ?>');"><i class="material-icons text-danger">delete_forever</i></a>
                </div>

            </div>
            <hr class="m-1">
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (is_ajax()) : ?>

    <script>
        $(document).ready(function () {
            //  cartModalBindButtons();

        });
    </script>

<?php endif; ?>

<div class="products-amount m-3">
    <div class="form-row align-items-center">
        <?php if (is_array($data)): ?>
            <div class="col-12 col-sm-6 total">
                <h6><strong><?php _e("Total Amount: "); ?> <br class="d-none d-sm-block"> <?php print currency_format($total); ?></strong></h6>
            </div>
            <div class="col-12 col-sm-6">
                <button type="button" class="btn btn-primary btn-md float-right" data-toggle="modal" data-target="#shoppingCartModal"><?php _e("Checkout"); ?></button>
            </div>
        <?php else: ?>
            <div class="col-12">
                <h5><?php _e("Your cart is empty. Please add some products in the cart."); ?></h5>
            </div>
        <?php endif; ?>
    </div>

</div>

