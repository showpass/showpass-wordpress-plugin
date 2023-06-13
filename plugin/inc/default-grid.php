<?php 
	global $showpass_image_formatter;
	/**
	 * Custom breakpoints for responsive image.
	 * Add max 980-1920 breakpoint, column layout only allows for a max width of 640px.
	 * 600px x 600px is already cached, so use that image.
	 */
	$image_breakpoints = [
		[600, '(max-width: 1920px) and (min-width: 981px)'],
		[960, '(max-width: 980px) and (min-width: 781px)'], 
		[780, '(max-width: 780px) and (min-width: 601px)'], 
		[600, '(max-width: 600px) and (min-width: 376px)'], 
		[375, '(max-width: 375px)']
	];

	$event_data = json_decode($data, true);
?>

<div class="showpass-flex-box">
    <div class="showpass-layout-flex">
        <?php
			if ($event_data['count'] > 0) {
				$events = $event_data['results'];
				foreach ($events as $key => $event) {
					// default event link does nothing
					$event_href = 'javascript:void(0);';
                    if (isset($event['external_link'])) {
                        $event_href = $event['external_link'];
                    }
                    // only set link if not sold out
					else if ( !showpass_ticket_sold_out($event) ) {
						/**
						 * if detail_page is setup, then link to the detail page
						 * otherwise link externally
						 */
						if (isset($detail_page)) {
							$event_href = sprintf('/%s/?slug=%s', $detail_page, $event['slug']);
						}
					}
				?>
        <div class="showpass-flex-column showpass-no-border showpass-event-card showpass-grid">
            <div class="showpass-event-grid showpass-layout-flex m15">
                <div class="flex-100 showpass-flex-column showpass-no-border showpass-no-padding p0">
                    <a href="<?= $event_href ?>" class="showpass-image ratio banner">
                        <?= isset($event['image_banner']) 
                            ? $showpass_image_formatter->getResponsiveImage($event['image_banner'], ['alt' => $event['name'], 'title' => $event['name'], 'breakpoints' => $image_breakpoints]) 
                            : sprintf('<img src="%s" alt="%s" />', plugin_dir_url(__FILE__).'../images/default-banner.jpg', $event['name']);
                        ?>
                    </a>
                </div>
                <div class="flex-100 showpass-flex-column showpass-no-border showpass-background-white">
                    <div class="showpass-full-width">
                        <!-- Ticket Pricing -->
                        <div class="showpass-layout-flex">
                            <div class="flex-100 showpass-flex-column showpass-no-border">
                                <?php if (!showpass_ticket_sold_out($event)) : ?>
                                <small class="showpass-price-display">
                                    <?php echo showpass_get_price_range($event['ticket_types']);?>
                                    <?php if (showpass_get_price_range($event['ticket_types']) != 'FREE') { echo $event['currency']; } ?>
                                </small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Ticket Pricing -->

                        <!-- Event Name -->
                        <div class="showpass-layout-flex">
                            <div class="flex-100 showpass-flex-column showpass-no-border showpass-title-wrapper">
                                <div class="showpass-event-title">
                                    <?php if ($detail_page) { ?>
                                    <h3><a
                                            href="/<?php echo $detail_page ?>/?slug=<?php echo $event['slug']; ?>"><?php echo $event['name']; ?></a>
                                    </h3>
                                    <?php } else {?>
                                    <h3>
                                        <a <?php if (!$event['external_link']) { ?>class="open-ticket-widget" <?php } ?>
                                            <?php if ($event['external_link']) { ?>
                                            href="<?php echo $event['external_link']; ?>" <?php } else { ?>
                                            id="<?php echo $event['slug']; ?>" <?php } ?>
                                            <?php if (isset($event_data['tracking_id'])) {?>
                                            data-tracking="<?php echo $event_data['tracking_id']; ?>" <?php } ?>>
                                            <?php echo $event['name']; ?>
                                        </a>
                                    </h3>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <!-- Event Name -->

                        <!-- Event Date(s) & Badges -->
                        <div class="showpass-layout-flex">
                            <div class="flex-100 showpass-flex-column showpass-no-border showpass-detail-event-date">
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
                                </div>
                            </div>
                        </div>
                        <!-- Event Date(s) & Badges -->
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex-100 showpass-flex-column showpass-no-border showpass-background-white action-bar">
                    <div class="showpass-layout-flex">
                        <div class="showpass-layout-flex showpass-list-button-layout">
                            <div class="flex-50 showpass-flex-column showpass-no-border showpass-button-pull-left">
                                <div class="showpass-button-full-width-grid">
                                    <?php include 'button-logic.php'; ?>
                                </div>
                            </div>
                            <?php if ($detail_page) {?>
                            <div class="flex-50 showpass-flex-column showpass-no-border showpass-button-pull-right">
                                <div class="showpass-button-full-width-grid">
                                    <a class="showpass-list-ticket-button showpass-button-secondary no-margin"
                                        href="/<?php echo $detail_page; ?>/?slug=<?php echo $event['slug']; ?>">More
                                        Info</a>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!-- Action Buttons -->
            </div>
        </div>
        <?php } ?>
        <?php if ($event_data['num_pages'] > 1) { ?>
        <div class="flex-100 showpass-flex-column showpass-no-border text-center">
            <ul class="showpass-pagination mb0 mt30">
                <?php for ($i = 1; $i <= $event_data['num_pages']; $i++) {
						$current = $i == $event_data['page_number'] ? 'class="current"' : '';
						if ($current != '') { ?>
                <li <?php echo $current;?>><?php echo $i;?></li>
                <?php } else { ?>
                <li><a href="<?php echo showpass_get_events_next_prev($i);?>"><?php echo $i; ?></a></li>
                <?php } } ?>
            </ul>
        </div>
        <?php } ?>
        <?php } else { ?>
        <div class="flex-100">
            <h1 class="mt0">Sorry, no events found!</h1>
            <?php if ($_GET) { ?>
            <a class="back-link" href="/events/">View All Events</a>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
</div>