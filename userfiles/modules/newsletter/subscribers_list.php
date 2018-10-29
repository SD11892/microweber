<?php only_admin_access(); ?>
<script>
    mw.lib.require('font_awesome5');
</script>
<?php
$subscribers_params = array();
$subscribers_params['no_limit'] = true;
$subscribers_params['order_by'] = "created_at desc";
$subscribers = newsletter_get_subscribers($subscribers_params);
?>
<?php if ($subscribers): ?>

    <div class="table-responsive">
        <table width="100%" border="0" class="mw-ui-table layout-fixed">
            <thead>
            <tr>
                <th><?php _e('Date'); ?></th>
                <th><?php _e('Name'); ?></th>
                <th><?php _e('Email'); ?></th>
                <th><?php _e('Subscribed'); ?></th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($subscribers as $subscriber): ?>
                <tr id="newsletter-subscriber-<?php print $subscriber['id']; ?>">
                    <td><?php print $subscriber['created_at']; ?></td>
                    <td><input type="text" class="mw-ui-field" name="name" value="<?php print $subscriber['name']; ?>"/></td>
                    <td><input type="email" class="mw-ui-field" name="email" value="<?php print $subscriber['email']; ?>"/></td>
                    <td>
                        <select class="mw-ui-field mw-ui-field-medium" name="is_subscribed">
                            <option value="1" <?php if ($subscriber['is_subscribed']): ?>  selected <?php endif; ?> ><?php _e('Yes'); ?></option>
                            <option value="0" <?php if (!$subscriber['is_subscribed']): ?>  selected <?php endif; ?> ><?php _e('No'); ?></option>
                        </select>
                    </td>
                    <td style="min-width: 90px;">
                        <input type="hidden" name="id" value="<?php print $subscriber['id']; ?>"/>
                        <button class="mw-ui-btn mw-ui-btn-notification mw-ui-btn-small" onclick="edit_subscriber('#newsletter-subscriber-<?php print $subscriber['id']; ?>')"><span class="fas fa-save"></span></button>
                        <a class="mw-ui-btn mw-ui-btn-icon mw-ui-btn-important mw-ui-btn-small" href="javascript:;" onclick="delete_subscriber('<?php print $subscriber['id']; ?>')"><span class="fas fa-trash"></span></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
