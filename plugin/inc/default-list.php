<div class="showpass-flex-box">
	<div class="showpass-layout-flex">
			<?php
			$event_data = json_decode($data, true);
			if ($event_data['count'] > 0) {
				$events = $event_data['results'];
				foreach ($events as $key => $event) { ?>
				<div class="flex-100 showpass-flex-column list-layout-flex showpass-no-border showpass-event-card">
					<div class="showpass-event-layout-list showpass-layout-flex m15">
						<div class="card-image showpass-flex-column list-layout-flex showpass-no-border showpass-no-padding p0">
							<?php if ($detail_page) { // if detail redirect url set ?>
								<a class="showpass-image-banner showpass-hide-mobile" style="background-image: url('<?php if ($event['image']) { echo $event['image']; } else { echo plugin_dir_url(__FILE__).'../images/default-square.jpg';}?>');" href="/<?php if ($detail_page) { echo $detail_page; } else { echo 'event-detail'; } ?>/?slug=<?php echo $event['slug']; ?>"></a>
								<a class="showpass-image showpass-hide-large" style="background-image: url('<?php if ($event['image_banner']) { echo $event['image_banner']; } else { echo plugin_dir_url(__FILE__).'../images/default-banner.jpg';}?>');" href="/<?php if ($detail_page) { echo $detail_page; } else { echo 'event-detail'; } ?>/?slug=<?php echo $event['slug']; ?>"></a>
							<?php } else if (showpass_ticket_sold_out($event['ticket_types'])) { // if tickets sold out ?>
								<a class="showpass-image-banner showpass-hide-mobile showpass-soldout" style="background-image: url('<?php if ($event['image_banner']) { echo $event['image_banner']; } else { echo plugin_dir_url(__FILE__).'../images/default-banner.jpg';}?>');"></a>
								<a class="showpass-image showpass-hide-large showpass-soldout" style="background-image: url('<?php echo $event['image_banner'];?>');"></a>
							<?php } else { ?>
                <a class="showpass-image-banner showpass-hide-mobile open-ticket-widget" id="<?php echo $event['slug']; ?>" style="background-image: url('<?php if ($event['image']) { echo $event['image']; } else { echo plugin_dir_url(__FILE__).'../images/default-square.jpg';}?>');" href="/<?php if ($detail_page) { echo $detail_page; } else { echo 'event-detail'; } ?>/?slug=<?php echo $event['slug']; ?>"></a>
                <a class="showpass-image showpass-hide-large open-ticket-widget" id="<?php echo $event['slug']; ?>" style="background-image: url('<?php if ($event['image_banner']) { echo $event['image_banner']; } else { echo plugin_dir_url(__FILE__).'../images/default-banner.jpg';}?>');" href="/<?php if ($detail_page) { echo $detail_page; } else { echo 'event-detail'; } ?>/?slug=<?php echo $event['slug']; ?>"></a>
							<?php } ?>
						</div>
						<div class="list-info showpass-flex-column list-layout-flex showpass-no-border showpass-background-white">
							<div class="showpass-space-between showpass-full-width showpass-layout-fill">
								<div class="showpass-layout-flex">
									<div class="flex-100 showpass-flex-column list-layout-flex showpass-no-border">
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
									<div class="flex-100 showpass-flex-column list-layout-flex showpass-no-border showpass-title-wrapper">
										<div class="showpass-event-title">
											<?php if ($detail_page) { ?>
												<h3><a href="/<?php echo $detail_page ?>/?slug=<?php echo $event['slug']; ?>"><?php echo $event['name']; ?></a></h3>
											<?php } else {?>
												<h3><a class="open-ticket-widget" id="<?php echo $event['slug']; ?>"><?php echo $event['name']; ?></a></h3>
											<?php } ?>
										</div>
									</div>
								</div>
								<div class="showpass-layout-flex">
                  <div class="flex-100 showpass-flex-column showpass-no-border showpass-detail-event-date">
										<div>
											<div class="info"><i class="fa fa-calendar icon-center"></i><?php echo showpass_get_event_date($event['starts_on'], $event['timezone'], false);?></div>
                      <div class="info"><i class="fa fa-clock-o icon-center"></i>
                        <?php echo showpass_get_event_time($event['starts_on'], $event['timezone'], false);?> - <?php echo showpass_get_event_time($event['ends_on'], $event['timezone'], false);?> <?php echo showpass_get_timezone_abbr($event['timezone'], false);?>
                      </div>
											<div class="info"><i class="fa fa-map-marker icon-center"></i><?php $location = $event['location']; echo $location['name'];?></div>
										</div>
									</div>
								</div>
								<div class="showpass-layout-flex showpass-list-button-layout">
									<div class="flex-50 showpass-flex-column list-layout-flex showpass-no-border showpass-button-pull-left">
										<div class="showpass-button-full-width-list">
                      <?php if ($event['has_related_events'] && $event_data['condensed_display']) { ?>
                        <a class="showpass-list-ticket-button showpass-button" href="/<?php if($detail_page) { echo $detail_page; } else { echo 'event-detail'; } ?>/?slug=<?php echo $event['slug']; ?>">
                          SELECT DATE
                        </a>
                      <?php } else { ?>
  											<?php if(showpass_ticket_sold_out($event['ticket_types'])) { ?>
  												<a class="showpass-list-ticket-button showpass-button showpass-soldout">
  													SOLD OUT
  												</a>
  											<?php } else { ?>
    											<a class="showpass-list-ticket-button showpass-button open-ticket-widget" id="<?php echo $event['slug']; ?>">
    												<?php if ($event['initiate_purchase_button'] == 'ipbd_buy_tickets') { ?>
    													BUY TICKETS
    												<?php } else if ($event['initiate_purchase_button'] == 'ipbd_register') { ?>
    													REGISTER
    												<?php } ?>
    											</a>
                        <?php } ?>
                      <?php } ?>
										</div>
									</div>
									<?php if ($detail_page) {?>
										<div class="flex-50 showpass-flex-column list-layout-flex showpass-no-border showpass-button-pull-right">
											<div class="showpass-button-full-width-list">
												<a class="showpass-list-ticket-button showpass-button-secondary" href="/<?php if($detail_page) { echo $detail_page; } else { echo 'event-detail'; } ?>/?slug=<?php echo $event['slug']; ?>">More Info</a>
											</div>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
			<?php if ($event_data['num_pages'] > 1) { ?>
			<div class="flex-100 showpass-flex-column list-layout-flex showpass-no-border text-center">
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
