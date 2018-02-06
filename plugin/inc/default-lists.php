<div class="flex-box">
	<div class="layout-flex">
			<?php
			$event_data = json_decode($data, true);
			if ($event_data['count'] > 0) {
				$events = $event_data['results'];
				foreach ($events as $key => $event) { ?>
				<div class="flex-50 flex-column no-border showpass-event-card ">
					<div class="showpass-event-list layout-flex m15">
						<div class="flex-100 flex-column no-border no-padding p0">
							<div>
								<a href="/<?php if($detail_page){ print_r($detail_page);} else {print_r('event-detail');} ?>/?slug=<?php echo $event['slug']; ?>" class="showpass-image" >
									<img alt="<?php echo $event['name']; ?>" src="<?php echo $event['image_stretch_banner_lg']; ?>"/>
								</a>
							</div>
						</div>
						<div class="flex-100 flex-column no-border background-white">
							<div>
								<div class="layout-flex">
									<div class="flex-100 flex-column no-border">
										<div><?php if ($event['ticket_types']) : ?><small class="text-warning"> <?php $price = $event['ticket_types'];  print_r(showpass_get_price_range($price));?></small><?php endif; ?></div>
									</div>
								</div>
								<div class="layout-flex">
									<div class="flex-100 flex-column no-border">
										<div class="showpass-event-title">
											<h3 class="mb0"><a href="/<?php if($detail_page){ print_r($detail_page);} else {print_r('event-detail');} ?>/?slug=<?php echo $event['slug']; ?>"><?php echo $event['name']; ?></a></h3>
										</div>
									</div>
								</div>
								<div class="layout-flex">
									<div class="flex-100 flex-column no-border">
										<div>
											<div class="text-muted small"><?php echo showpass_get_event_date($event['starts_on'], $event['timezone'], false);?></div>
											<div class="text-muted small"><?php $location = $event['location']; echo $location['name'];?></div>
										</div>
									</div>
								</div>
								<div class="layout-flex">
										<div class="clearfix layout-flex showpass-list-button-layout">
											<div class="flex-50 flex-column no-border button-pull-left">
												<div class="w100">
													<a class="list-ticket-button showpass-button" onclick="showpass.tickets.eventPurchaseWidget('<?php echo $event['slug'];?>', {'theme-primary': '#000000'})">Buy Tickets</a>
												</div>
											</div>
											<div class="flex-50 flex-column no-border button-pull-right">
												<div class="w100">
													<a class="list-ticket-button secondary showpass-button-secondary" href="/<?php if($detail_page){ print_r($detail_page);} else {print_r('event-detail');} ?>/?slug=<?php echo $event['slug']; ?>">More info</a>
												</div>
											</div>
										</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
			<?php if ($event_data['num_pages'] > 1) { ?>
			<div class="flex-100 flex-column no-border text-center pagination-container">
				<ul class="pagination showpass-pagination mb0 mt30">
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
