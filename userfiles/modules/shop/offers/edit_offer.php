<?php must_have_access(); ?>

<?php
$date_format = get_date_format();
//$products = offers_get_products();

$all_products = get_products('nolimit=1');

if (isset($params['offer_id']) && $params['offer_id'] !== 'false') {
    $addNew = false;
    //WAS $data = offer_get_by_id($params['offer_id']);
    $data = \MicroweberPackages\Offer\Models\Offer::getById($params['offer_id']);

    if (isset($data['expires_at']) && $data['expires_at'] != '0000-00-00 00:00:00') {
        try {
            $carbonUpdatedAt = Carbon::parse($data['expires_at']);
            $data['expires_at'] = $carbonUpdatedAt->format('Y-m-d');
        } catch (\Exception $e) {
            //
        }
    }
} else {
    $addNew = true;

    $data['id'] = '';
    $data['product_id'] = '';
    $data['product_title'] = '';
    $data['price'] = '';
    $data['price_id'] = '';
    $data['offer_price'] = '';
    $data['created_at'] = '';
    $data['updated_at'] = '';
    $data['expires_at'] = '';
    $data['created_by'] = '';
    $data['edited_by'] = '';
    $data['is_active'] = 1;
}
?>

<script>mw.lib.require('mwui_init');</script>
<script>mw.lib.require('bootstrap_datetimepicker');</script>

<script>
    // SET GLOBAL MULTILANGUAGE TEXTS
    var TEXT_FIELD_MUST_BE_FLOAT_NUMBER = "<?php _ejs('The field must be float number.');?>";
    var TEXT_FIELD_MUST_BE_NUMBER = "<?php _ejs('The field must be number.');?>";
    var TEXT_SUCCESS_SAVE = "<?php _ejs('Offer is saved!');?>";
    var TEXT_FIELD_CANNOT_BE_EMPTY = "<?php _ejs('This field cannot be empty.');?>";
    var TEXT_FILL_ALL_FIELDS = "<?php _ejs('Please fill in all fields correctly.');?>";

    var today = new Date();

    editOferrSetExpirationDate = function () {
        $('[name="expires_at"]', '.js-edit-offer-form').datetimepicker({
            defaultDate: new Date(today.getTime() + (24 * 60 * 60 * 1000)),
            format: '<?php print $date_format;?>',
            //zIndex: 1105
        });
    }

    $(document).ready(function () {
        // editOferrSetExpirationDate();
    });
</script>

<div class="js-validation-messages"></div>

<form class="js-edit-offer-form" action="<?php print api_url('offer_save'); ?>">
    <input type="hidden" name="id" value="<?php print $data['id'] ?>"/>
    <?php if ($addNew) { ?>
        <input type="hidden" name="created_by" value="<?php print user_id() ?>"/>
    <?php } else { ?>
        <input type="hidden" name="edited_by" value="<?php print user_id() ?>"/>
    <?php } ?>

    <div class="form-group">
        <label class="control-label"><?php _e("Offer status"); ?></label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" data-value-checked="1" data-value-unchecked="0" <?php if ($data['is_active'] == 1): ?>checked<?php endif; ?>>
            <label class="custom-control-label" for="is_active"><?php _e("Active"); ?></label>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php _e("Product title | Price"); ?></label>

        <select name="product_id_with_price_id" class="js-product-title selectpicker" data-size="5" data-live-search="true" data-width="100%">
            <?php if ($all_products): ?>
                <?php foreach ($all_products as $product) {

                    $all_prices = get_product_prices($product['id'], true);
                    $product_id = $product['id'];

                    if ($all_prices) {
                        foreach ($all_prices as $a_price) {
                            $offer_product_price_id = $data['product_id'] . '|' . $data['price_id'];
                            $option_id = $product_id . '|' . $a_price['id'];
                            $selected = ($offer_product_price_id == $option_id ? ' selected="selected"' : '');
                            ?>

                            <option value="<?php print $option_id; ?>"<?php print $selected; ?>><?php print $product['title'] . ' | ' . $a_price['name'] . ' : ' . mw()->shop_manager->currency_symbol() . ' ' . $a_price['values_plain']; ?></option>
                            <?php
                        }
                    }
                }
                ?>
            <?php else: ?>
                <option value=""><?php _e("No products found"); ?></option>
            <?php endif; ?>
        </select>
    </div>

    <div class="form-group">
        <label class="control-label"><?php _e("Offer price"); ?> <?php print mw()->shop_manager->currency_symbol(); ?></label>
        <input type="text" name="offer_price" class="form-control js-validation js-validation-float-number" value="<?php print number_format(floatval($data['offer_price']), 2); ?>"/>
        <div class="js-field-message"></div>
    </div>

    <?php if ($addNew) { ?>
        <div class="form-group">
            <label class="control-label"><?php _e("Offer start at"); ?></label>
            <small class="text-muted d-block mb-2"><?php _e("The date when the offer will be created"); ?></small>
            <input type="date" name="created_at" class="form-control" value="<?php print date("Y-m-d H:i:s"); ?>"/>
        </div>
    <?php } else { ?>
        <div class="mw-ui-row">
            <div class="mw-ui-col">
                <div class="mw-ui-col-container">
                    <div class="form-group">
                        <label class="control-label"><?php _e("Offer start at"); ?></label>
                        <p><?php print date_system_format($data['created_at']); ?></p>
                    </div>
                </div>
            </div>
            <div class="mw-ui-col">
                <div class="mw-ui-col-container">
                    <div class="form-group">
                        <label class="control-label"><?php _e("Updated date"); ?></label>
                        <p><?php print date_system_format($data['updated_at']); ?></p>
                        <div class="js-field-message"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="form-group">
        <label class="control-label"><?php _e("Offer expiry at"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("The date when the offer will expire"); ?></small>

        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="expiration-checkbox" <?php echo (!empty($data['expires_at']) && $data['expires_at'] != '0000-00-00 00:00:00') ? 'checked' : '' ?> >
            <label class="custom-control-label" for="expiration-checkbox"><?php echo _e('Has expiration date');?></label>
        </div>

        <div class="js-exp-date-holder">
            <input type="date" name="expires_at" class="js-exp-date form-control disabled-js-validation disabled-js-validation-expiry-date" autocomplete="off" value="<?php print ($data['expires_at']); ?>"/>
        </div>

        <div class="js-field-message"></div>
    </div>

    <hr class="thin">

    <div class="d-flex justify-content-between">
        <div>
            <?php if (!$addNew) { ?>
                <a class="btn btn-outline-danger btn-sm" href="javascript:deleteOffer('<?php print $data['id'] ?>')"><?php _e("Delete"); ?></a>
            <?php } ?>
        </div>

        <div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="editModal.modal.remove()"><?php _e("Cancel"); ?></button>
            <button type="button" class="btn btn-success btn-sm js-save-offer"><?php _e("Save"); ?></button>
        </div>
    </div>
</form>
<script type='text/javascript'>

    $(document).ready(function () {
        <?php if (isset($data['product_title'])): ?>
        $(".js-product_title").val("<?php echo $data['product_title']; ?>").change();
        <?php endif; ?>

        $('#expiration-checkbox').on('click',function(e) {

            var checked = $(this).is(":checked");
            var calendarInputEl =  $('.js-exp-date');

            if(checked === false) {
                calendarInputEl.attr('data-old-val', calendarInputEl.val())
                calendarInputEl.val('');
            } else {
                if( calendarInputEl.attr('data-old-val')){
                    calendarInputEl.val(calendarInputEl.attr('data-old-val')).trigger('change');

                }
            }
        });

    });

</script>
<script src="<?php print $config['url_to_module']; ?>js/edit-offer.js"/>

