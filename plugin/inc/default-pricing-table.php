<?php 
	global $showpass_image_formatter;
	/**
	 * Custom breakpoints for responsive image.
	 * Add max 980-1920 breakpoint, column layout only allow for a max width of 640px up to 1920.
	 * 600px x 600px is already cached, so use that image.
	 */
	$image_breakpoints = [
		[600, '(max-width: 1920px) and (min-width: 981px)'],
		[960, '(max-width: 980px) and (min-width: 781px)'], 
		[780, '(max-width: 780px) and (min-width: 601px)'], 
		[600, '(max-width: 600px) and (min-width: 376px)'], 
		[375, '(max-width: 375px)']
	];
?>

<div class="showpass-flex-box showpass-pricing-table">
	<div class="showpass-layout-flex justify-center">
			<?php
			if ($events) {
				foreach ($events as $key => $event) { ?>
				<div class="showpass-flex-column showpass-no-border showpass-event-card showpass-grid">
					<div class="showpass-event-list showpass-layout-flex m15 layout-fill">
						<div class="flex-100 showpass-flex-column showpass-no-border showpass-no-padding p0">
							<div class="showpass-image ratio banner">
								<?= isset($event['image_banner']) 
									? $showpass_image_formatter->getResponsiveImage($event['image_banner'], ['alt' => $event['name'], 'breakpoints' => $image_breakpoints]) 
									: sprintf('<img src="%s" alt="%s" />', plugin_dir_url(__FILE__).'../images/default-banner.jpg', $event['name']);
						 		?>
							</div>
						</div>
						<div class="flex-100 showpass-flex-column showpass-no-border showpass-background-white">
							<div class="showpass-full-width">
								<div class="showpass-layout-flex">
									<div class="flex-100 showpass-flex-column showpass-no-border">
                    <div>
                      <?php if ($event['ticket_types']) : ?>
                        <small class="showpass-price-display">
                          <?php echo showpass_get_price_range($event['ticket_types']);?>
                          <?php if (showpass_get_price_range($event['ticket_types']) != 'FREE') { echo $event['currency']; } ?>
                        </small>
                      <?php endif; ?>
                    </div>
                    <div><?php if (!$event['ticket_types']) : ?><small class="showpass-price-display"> No Tickets Available</small><?php endif; ?></div>
                  </div>
								</div>
								<div class="showpass-layout-flex">
									<div class="flex-100 showpass-flex-column showpass-no-border showpass-title-wrapper">
										<div>
                      <h3><?php echo $event['name']; ?></h3>
										</div>
									</div>
								</div>
								<?php if ($show_event_details) { ?>
                  <div class="showpass-layout-flex">
                    <div class="flex-100 showpass-flex-column showpass-no-border showpass-detail-event-date">
                      <div>
												<?= showpass_display_date($event, true) ?>
                        <div class="info"><i class="fa fa-map-marker icon-center"></i><?php $location = $event['location']; echo $location['name'];?></div>
                      </div>
                    </div>
                  </div>
                <?php } ?>
                <?php if ($show_event_description) { ?>
                  <div class="showpass-layout-flex">
                    <div class="flex-100 showpass-flex-column showpass-no-border showpass-event-description">
                      <div class="description">
                        <?php echo $event['description']; ?>
                      </div>
									  </div>
                  </div>
                <?php } ?>
								<div class="pricing-table-buy-now">
									<div class="showpass-list-button-layout">
										<div class="showpass-no-border">
											<?php if(showpass_ticket_sold_out($event)) {?>
												<a class="showpass-list-ticket-button showpass-button showpass-soldout">
													<?php echo($event['inventory_sold_out'] || $event['sold_out'] ? 'SOLD OUT' : 'NOT AVAILABLE'); ?>
												</a>
											<?php } else { ?>
											<a 
												class="showpass-list-ticket-button showpass-button <?php if (!$event['external_link']) echo 'open-ticket-widget' ?>" 
												<?php if ($event['external_link']) { ?>
													href="<?php echo $event['external_link']; ?>"
												<?php } else { ?>
													id="<?php echo $event['slug']; ?>" 
													href="#"
												<?php } ?>
												data-show-description="<?= $show_widget_description ?>"
											>
                        <?php include 'button-verbiage.php'; ?>
											</a>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
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
