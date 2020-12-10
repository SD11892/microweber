<?php
$entry = false;
//d($item);return;
if (isset($item['rel_id']) AND !isset($is_entry)) {
    $entry_params['id'] = $item['rel_id'];
    $entry = get_contact_entry_by_id($entry_params);
    $item_id = $item['rel_id'];
} elseif (isset($item['id']) AND isset($is_entry)) {
    $entry_params['id'] = $item['id'];
    $entry = get_contact_entry_by_id($entry_params);
    $item_id = $item['id'];
}

if (isset($entry['form_values'])) {
    $form_values = json_decode($entry['form_values'], true);

    if ($form_values) {
        $countArrays = ceil(count($form_values) / 2);
    }

    $form_values_1 = [];
    $form_values_2 = [];
    if (is_array($form_values)) {
        $form_values_1 = array_slice($form_values, 0, $countArrays);
        $form_values_2 = array_slice($form_values, $countArrays);
    }
}

$created_by = false;
if (isset($item['created_by'])) {
    $created_by = get_user_by_id($item['created_by']);

    if (isset($created_by['username'])) {
        $created_by_username = $created_by['username'];
    } else {
        $created_by_username = false;
    }
}
?>

<div class="card mb-2 not-collapsed-border collapsed card-message-holder <?php if (!isset($is_entry)): ?>card-bubble<?php endif; ?> <?php if (isset($item['is_read']) AND $item['is_read'] == 0): ?>active<?php endif; ?> bg-silver" data-toggle="collapse" data-target="#notif-entry-item-<?php print $item_id ?>" aria-expanded="false" aria-controls="collapseExample">
    <div class="card-body">
        <?php if (isset($params['module']) and $params['module'] == 'admin/notifications'): ?>
            <div class="row align-items-center mb-3">
                <div class="col text-left">
                    <span class="text-primary text-break-line-2">New form entry</span>
                </div>
            </div>
        <?php endif; ?>

        <div class="row align-items-center">
            <div class="col" style="max-width:55px;">
                <i class="mdi mdi-email text-primary mdi-24px"></i>
            </div>
            <div class="col-10 col-sm item-id"><span class="text-primary">#<?php echo $entry_params['id']; ?></span></div>

            <div class="col-6 col-sm">
                <?php print date('M d, Y', strtotime($item['created_at'])); ?>
                <small class="text-muted"><?php print date('h:s', strtotime($item['created_at'])); ?>h</small>
            </div>

            <div class="col-6 col-sm"><?php print mw('format')->ago($item['created_at']); ?></div>
        </div>

        <div class="collapse" id="notif-entry-item-<?php print $item_id ?>">
            <hr class="thin"/>
            <div class="row">
                <div class="col-md-6">
                    <h6><strong>Fields</strong></h6>
                    <?php if ($form_values_1): ?>
                        <?php foreach ($form_values_1 as $key => $val1): ?>
                            <?php if (!is_array($val1)): ?>
                                <div>
                                    <small class="text-muted"><?php echo(str_replace('_', ' ', $key)); ?>:</small>
                                    <p><?php echo $val1; ?></p>
                                </div>
                            <?php else: ?>
                                <small class="text-muted"><?php echo(str_replace('_', ' ', $key)); ?>:</small>
                                <?php foreach ($val1 as $val1_1): ?>
                                    <p><?php echo ($val1_1) . '<br />'; ?></p>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <h6>&nbsp;</h6>
                    <?php if ($form_values_2): ?>
                        <?php foreach ($form_values_2 as $key => $val2): ?>
                            <?php if (!is_array($val2)): ?>
                                <div>
                                    <small class="text-muted"><?php echo(str_replace('_', ' ', $key)); ?>:</small>
                                    <p><?php echo $val2; ?></p>
                                </div>
                            <?php else: ?>
                                <small class="text-muted"><?php echo(str_replace('_', ' ', $key)); ?>:</small>
                                <?php foreach ($val2 as $val2_1): ?>
                                    <p><?php echo ($val2_1) . '<br />'; ?></p>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <div>
                        <small class="text-muted">Attached files:</small>
                        <p><i class="mdi mdi-pdf-box text-primary mdi-18px"></i> Refactoring UI: Bad About</p>
                        <p><i class="mdi mdi-file-check text-primary mdi-18px"></i> Some of our files attached</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>