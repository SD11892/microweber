<?php only_admin_access(); ?>
<?php $lic = mw()->update->get_licenses('limit=10000'); ?>

<script>
    mw.edit_licence = function ($lic_id) {

        licensemodal = mw.modal({
            content: '<div type="settings/group/license_edit"  lic_id="' + $lic_id + '" class="module" id="lic_' + $lic_id + '"></div>',
            onremove: function () {
                mw.reload_module("#<?php print $params['id'] ?>");
            },
            name: 'licensemodal'
        });

        mw.reload_module("#lic_" + $lic_id);
    }


    mw.validate_licenses = function () {
        $.ajax({
            url: "<?php print site_url('api') ?>/mw_validate_licenses"
        }).done(function () {
            mw.reload_module("#<?php print $params['id'] ?>");

        });
    }
</script>

<?php if (is_array($lic) and !empty($lic)): ?>
    <div class="">
        <table width="100%" cellspacing="0" cellpadding="0" class="table table-bordered">
            <thead class="bg-secondary">
            <tr>
                <th><?php _e('License'); ?></th>
                <th class="left"><?php _e('Key'); ?></th>
                <th class="left"><?php _e('Status'); ?></th>
                <th class="center"><?php _e('View'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($lic as $item): ?>
                <tr>
                    <td class="left"><?php print $item['rel_type']; ?></td>
                    <td class="left"><?php print $item['local_key']; ?>
                        <?php if (isset($item['status']) and $item['status'] == 'active'): ?>
                            <small>
                                <ul>
                                    <?php if (isset($item['rel_name']) and $item['rel_name'] != ''): ?>
                                        <li><?php print $item['rel_name']; ?></li>
                                    <?php endif; ?>
                                    <?php if (isset($item['registered_name']) and $item['registered_name'] != ''): ?>
                                        <li><?php print $item['registered_name']; ?></li>
                                    <?php endif; ?>
                                    <?php if (isset($item['company_name']) and $item['company_name'] != ''): ?>
                                        <li><?php print $item['company_name']; ?></li>
                                    <?php endif; ?>
                                    <?php if (isset($item['reg_on']) and $item['reg_on'] != ''): ?>
                                        <li>registered on <?php print date('d M ,Y', strtotime($item['reg_on'])); ?></li>
                                    <?php endif; ?>
                                    <?php if (isset($item['due_on']) and $item['due_on'] != ''): ?>
                                        <li>next payment on <?php print date('d M ,Y', strtotime($item['due_on'])); ?></li>
                                    <?php endif; ?>
                                </ul>
                            </small>
                        <?php endif; ?></td>
                    <td class="left"><?php print ucwords($item['status']); ?></td>
                    <td class="center"><a class="btn btn-outline-primary btn-sm" href="javascript:mw.edit_licence('<?php print $item['id'] ?>');"><?php _e('Edit'); ?></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<div class="row">
    <div class="col">
        <?php if (is_array($lic) and !empty($lic)): ?>
            <a class="btn btn-primary btn-sm" href="javascript:mw.validate_licenses();"><?php _e('Validate'); ?></a>
        <?php endif; ?>
    </div>
    <div class="col text-right">
        <a class="btn btn-success btn-sm" href="javascript:mw.edit_licence('0');"><?php _e('Add License'); ?></a>
    </div>
</div>

