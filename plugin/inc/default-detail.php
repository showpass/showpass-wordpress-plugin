<?php
	global $showpass_image_formatter;
	$event = json_decode($data, true);
?>
<div id="page" class="showpass-flex-box">
    <?php
  	if (isset($event['detail'])) { ?>
    <div class="showpass-layout-flex">
        <h2>Sorry, we cannot find the event that you are looking for!</h2>
    </div>
    <?php } else {
		$current_event = $event['id'];?>
    <div class="showpass-layout-flex showpass-detail-event-name">
        <div class="flex-100 showpass-no-border showpass-flex-column">
            <div class="showpass-detail-image-container">
                <?=
						isset($event['image_banner'])
							? $showpass_image_formatter->getResponsiveImage($event['image_banner'], ['alt' => $event['name'], 'title' => $event['name'], 'attr' => ['class' => 'showpass-detail-image'] ])
							: sprintf('<img class="showpass-detail-image" src="%s" alt="%s" />', plugin_dir_url(__FILE__).'../images/default-banner.jpg', $event['name']);
					?>
            </div>
        </div>
    </div>
    <div class="showpass-layout-flex showpass-detail-event-name">
        <div class="flex-100 showpass-flex-column showpass-no-border">
            <h1 class="w100"><?php echo $event['name']; ?></h1>
        </div>
    </div>
    <div class="flex-container showpass-layout-flex">
        <div class="flex-66 showpass-flex-column showpass-no-border">
            <div class="w100">
                <div class="showpass-detail-buy showpass-hide-medium">
                    <?php include 'button-logic.php'; ?>
                </div>
                <div class="showpass-event-description">
                    <?php echo $event['description'];?>
                </div>
            </div>
        </div>
        <div class="flex-33 showpass-flex-column showpass-no-border">
            <div class="w100">
                <!-- Event Date(s) & Badges -->
                <div>
                    <div class="showpass-detail-event-date">
                        <div>
                            <?php if (!showpass_ticket_sold_out($event) && $event['is_recurring_parent']) : ?>
                            <div class="info badges">
                                <span class="badge">
                                    <?php if (showpass_get_event_date($event['starts_on'], $event['timezone']) === showpass_get_event_date($event['ends_on'], $event['timezone'])): ?>
                                    Multiple Times
                                    <?php else: ?>
                                    Multiple Dates
                                    <?php endif ?>
                                </span>
                            </div>
                            <?php endif; ?>
                            <?= showpass_display_date($event, true) ?>
                            <div class="info">
                                <div class="info-icon">
                                    <i class="fa fa-map-marker icon-center"></i>
                                </div>
                                <div class="info-display">
                                    <?= $event['location']['name'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Event Date(s) & Badges -->
                <div class="showpass-detail-event-date mb30">
                    <?php if ($event['ticket_types']) : ?>
                    <div class="info mb20"><i
                            class="fa fa-tags icon-center"></i><?php echo showpass_get_price_range($event['ticket_types']);?>
                        <?php if (showpass_get_price_range($event['ticket_types']) != 'FREE') { echo $event['currency']; } ?>
                    </div>
                    <?php endif; ?>
                    <div class="showpass-detail-buy">
                        <?php include 'button-logic.php'; ?>
                    </div>
                </div>
                <div class="text-center showpass-detail-location">
                    <?php $location = $event['location'] ?>
                    <h3 class="showpass-event-veune-name"><?php echo $location['name'];?></h3>
                    <span class="showpass-detail-address"><?php echo rtrim($location['street_name']);?>,
                        <?php echo $location['city'];?></span>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>