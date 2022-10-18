<?php if ($event['external_link']) { ?>
<a class="showpass-list-ticket-button showpass-button no-margin" href="<?php echo $event['external_link']; ?>"
    target="_blank">
    <?php include 'button-verbiage.php'; ?>
</a>
<?php } else if(showpass_ticket_sold_out($event)) { ?>
<a class="showpass-list-ticket-button showpass-button showpass-soldout no-margin">
    <?php echo($event['inventory_sold_out'] || $event['sold_out'] ? 'SOLD OUT' : 'NOT AVAILABLE'); ?>
</a>
<?php } else { ?>
<a class="showpass-list-ticket-button showpass-button no-margin <?php if (!$event['external_link']) echo 'open-ticket-widget' ?>"
    id="<?php echo $event['slug']; ?>" data-show-description="<?= $show_widget_description ?>"
    <?php if (isset($event_data['tracking_id'])) {?> data-tracking="<?php echo $event_data['tracking_id']; ?>"
    <?php } ?>>
    <?php include 'button-verbiage.php'; ?>
</a>
<?php } ?>