<div class="showpass-flex-box showpass-pricing-table">
	<div class="showpass-layout-flex justify-center">
			<?php
			if ($events) {
				foreach ($events as $key => $event) { ?>
				<div class="showpass-flex-column showpass-no-border showpass-event-card showpass-grid">
					<div class="showpass-event-list showpass-layout-flex m15 layout-fill">
						<div class="flex-100 showpass-flex-column showpass-no-border showpass-no-padding p0">
							<span class="showpass-image" style="background-image: url('<?php echo $event['image_banner'];?>');"></span>
						</div>
						<div class="flex-100 showpass-flex-column showpass-no-border showpass-background-white">
							<div class="showpass-full-width">
								<?php /* <div class="showpass-layout-flex">
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
										<div class="showpass-event-title">
                      <h3><a class="open-ticket-widget" id="<?php echo $event['slug']; ?>"><?php echo $event['name']; ?></a></h3>
										</div>
									</div>
								</div>
								<div class="showpass-layout-flex">
									<div class="flex-100 showpass-flex-column showpass-no-border showpass-detail-event-date">
										<div>
											<div class="info"><i class="fa fa-calendar icon-center"></i><?php echo showpass_get_event_date($event['starts_on'], $event['timezone'], false);?></div>
											<div class="info"><i class="fa fa-map-marker icon-center"></i><?php $location = $event['location']; echo $location['name'];?></div>
										</div>
									</div>
								</div> */?>
                <div class="showpass-layout-flex">
									<div class="flex-100 showpass-flex-column showpass-no-border showpass-event-description">
										<div class="description">
                      <?php echo $event['description']; ?>
										</div>
									</div>
								</div>
								<div class="pricing-table-buy-now">
									<div class="showpass-list-button-layout">
										<div class="showpass-no-border">
											<?php if(showpass_ticket_sold_out($event['ticket_types'])) {?>
												<a class="showpass-list-ticket-button showpass-button showpass-soldout">
													SOLD OUT
												</a>
											<?php } else { ?>
											<a class="showpass-list-ticket-button showpass-button open-ticket-widget" id="<?php echo $event['slug']; ?>" href="#">
												<?php if ($event['initiate_purchase_button'] == 'ipbd_buy_tickets') { ?>
													BUY TICKETS
												<?php } else if ($event['initiate_purchase_button'] == 'ipbd_register') { ?>
													REGISTER
												<?php } ?>
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
