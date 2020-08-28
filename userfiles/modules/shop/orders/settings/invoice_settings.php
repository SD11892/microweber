<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <i class="module-icon-svg-fill"></i> <strong>Invoicing</strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" name="enable_invoices" id="enable_invoices" data-option-group="shop" data-value-checked="1" data-value-unchecked="0" class="mw_option_field custom-control-input" <?php if (true): ?>checked<?php endif; ?>>
                <label class="custom-control-label" for="enable_invoices">Enable invoicing</label>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">Company Logo: </label>

            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="mw_option_field custom-file-input" id="invoice_company_logo" data-option-group="shop" name="invoice_company_logo" />
                    <label class="custom-file-label" for="invoice_company_logo">Choose file</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">Company Name: </label>
            <input type="text" class="mw_option_field form-control" data-option-group="shop" name="invoice_company_name" placeholder="" value="">
        </div>

        <div class="form-group">
            <label class="control-label">Company Country:</label>

            <select name="invoice_company_country" class="mw_option_field selectpicker" data-size="5" data-width="100%" data-option-group="shop">
                <?php if (countries_list()): ?>
                    <?php foreach (countries_list() as $country): ?>
                        <option value="<?php print $country; ?>" selected="selected"><?php print $country; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <div class="form-group">
                <label class="control-label">Company City: </label>
                <input type="text" class="mw_option_field form-control" data-option-group="shop" name="invoice_company_city" placeholder="" value="">
            </div>

            <div class="form-group">
                <label class="control-label">Company Address: </label>
                <input type="text" class="mw_option_field form-control" data-option-group="shop" name="invoice_company_address" placeholder="" value="">
            </div>

            <div class="form-group">
                <label class="control-label">Company VAT Number: </label>
                <input type="text" class="mw_option_field form-control" data-option-group="shop" name="invoice_company_vat_number" placeholder="" value="">
            </div>

            <div class="form-group">
                <label class="control-label">ID Company Number: </label>
                <input type="text" class="mw_option_field form-control" data-option-group="shop" name="invoice_id_company_number" placeholder="" value="">
            </div>

            <div class="form-group">
                <label class="control-label">Additional information: </label>
                <textarea class="mw_option_field form-control" data-option-group="shop" name="invoice_company_bank_details" placeholder="For example: reason for taxes"></textarea>
            </div>

            <div class="form-group">
                <label class="control-label">Bank transfer details: </label>
                <textarea class="mw_option_field form-control" data-option-group="shop" name="invoice_company_bank_details" placeholder="Enter your bank details here"></textarea>
            </div>
        </div>
    </div>
</div>
