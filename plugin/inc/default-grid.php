<div class="showpass-flex-box">
	<div class="showpass-layout-flex">
			<?php
			$event_data = json_decode($data, true);
			if ($event_data['count'] > 0) {
				$events = $event_data['results'];
				foreach ($events as $key => $event) { ?>
				<div class="flex-50 showpass-flex-column showpass-no-border showpass-event-card">
					<div class="showpass-event-list showpass-layout-flex m15">
						<div class="flex-100 showpass-flex-column showpass-no-border showpass-no-padding p0">
							<?php if ($detail_page) { ?>
								<a class="showpass-image" style="background-image: url('<?php echo $event['image_banner'];?>');" href="/<?php echo $detail_page; ?>/?slug=<?php echo $event['slug']; ?>"></a>
							<?php } else {?>
								<a class="showpass-image open-ticket-widget" id="<?php echo $event['slug']; ?>" style="background-image: url('<?php echo $event['image_banner'];?>');"></a>
							<?php } ?>
						</div>
						<div class="flex-100 showpass-flex-column showpass-no-border showpass-background-white">
							<div class="showpass-full-width">
								<div class="showpass-layout-flex">
									<div class="flex-100 showpass-flex-column showpass-no-border">
										<div><?php if ($event['ticket_types']) : ?><small class="showpass-price-display"> <?php echo showpass_get_price_range($event['ticket_types']);?></small><?php endif; ?></div>
                    <div><?php if (!$event['ticket_types']) : ?><small class="showpass-price-display"> No Tickets Available</small><?php endif; ?></div>
                  </div>
								</div>
								<div class="showpass-layout-flex">
									<div class="flex-100 showpass-flex-column showpass-no-border showpass-title-wrapper">
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
									<div class="flex-100 showpass-flex-column showpass-no-border">
										<div>
											<div class="text-muted small"><?php echo showpass_get_event_date($event['starts_on'], $event['timezone'], false);?></div>
											<div class="text-muted small"><?php $location = $event['location']; echo $location['name'];?></div>
										</div>
									</div>
								</div>
								<div class="showpass-showpass-layout-flex">
										<div class="clearfix showpass-layout-flex showpass-list-button-layout">
												<div class="flex-50 showpass-flex-column showpass-no-border showpass-button-pull-left">
													<div class="showpass-button-full-width-grid">
														<a class="showpass-list-ticket-button showpass-button open-ticket-widget" id="<?php echo $event['slug']; ?>">Buy Tickets</a>
													</div>
												</div>
											<?php if ($detail_page) {?>
												<div class="flex-50 showpass-flex-column showpass-no-border showpass-button-pull-right">
													<div class="showpass-button-full-width-grid">
														<a class="showpass-list-ticket-button showpass-button-secondary" href="/<?php echo $detail_page; ?>/?slug=<?php echo $event['slug']; ?>">More Info</a>
													</div>
												</div>
											<?php } ?>
										</div>
								</div>
							</div>
						</div>
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
