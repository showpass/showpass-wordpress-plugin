<div id="page" class="showpass-flex-box">
	<?php
	 $event_data = json_decode($data, true);
	if (isset($event_data['detail'])) { ?>
		<div class="showpass-layout-flex">
			<h2>Sorry, we cannot find the event that you are looking for!</h2>
		</div>
	<?php } else {
		$event = $event_data;
		$current_event = $event['id'];?>
		<div class="showpass-layout-flex showpass-detail-event-name">
			<div class="flex-100 showpass-flex-column showpass-no-border">
				<img class="showpass-detail-image" alt="<?php echo $event['name']; ?>" src="<?php echo $event['image_stretch_banner_lg']; ?>" />
			</div>
		</div>
		<div class="showpass-layout-flex showpass-detail-event-name">
			<div class="flex-100 showpass-flex-column showpass-no-border"><h1 class="w100"><?php echo $event['name']; ?></h1></div>
		</div>
		<div class="flex-container showpass-layout-flex">
			<div class="flex-66 showpass-flex-column showpass-no-border">
				<div class="w100">
					<span onclick="showpass.tickets.eventPurchaseWidget('<?php echo $event['slug'];?>', {'theme-primary': '#000000'})" class="showpass-detail-buy showpass-hide-large">BUY TICKETS</span>
					<?php echo $event['description'];?>
				</div>
			</div>
			<div class="flex-33 showpass-flex-column showpass-no-border">
				<div class="w100">
					<div class="showpass-detail-event-date mb30 ">
						<div class="info"><i class="fa fa-calendar icon-center"></i><?php echo showpass_get_event_date($event['starts_on'], $event['timezone'], false);?></div>
						<div class="info"><i class="fa fa-clock-o icon-center"></i> <?php echo showpass_get_event_time($event['starts_on'], $event['timezone'], false);?> - <?php echo showpass_get_event_time($event['ends_on'], $event['timezone'], false);?>
							<?php echo showpass_get_timezone_abbr($event['timezone'], false);?></div>
						<div class="info"><i class="fa fa-map-marker icon-center"></i> <?php  $location = $event['location']; echo $location['name'];?></div>
						<?php if ($event['ticket_types']) : ?><div class="info mb20"><i class="fa fa-tags icon-center"></i> <?php	print_r(showpass_get_price_range($event['ticket_types']));?></div><?php endif; ?>
						<span onclick="showpass.tickets.eventPurchaseWidget('<?php echo $event['slug'];?>', {'theme-primary': '#000000'})" class="showpass-detail-buy">BUY TICKETS</span>
					</div>
					<div class="text-center showpass-detail-location">
						<h2 class="showpass-event-veune-name"><?php echo $location['name'];?></h2>
						<span class="showpass-detail-address"><?php echo rtrim($location['street_name']);?>, <?php echo $location['city'];?></span>
						<iframe width="100%" height="300" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDe9oSMuAfjkjtblej94RJvQh3ioWJb4go&q=<?php echo $location['name'];?>,<?php echo $location['city'];?>+<?php echo $location['province'];?>
							&center=<?php echo $location['position'];?>" allowfullscreen>
						</iframe>
						<?php //echo do_shortcode('[codepeople-post-map name="'.$event->location->name.'" center="'.$event->location->position.'" width="100% height="300"]'); ?>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
