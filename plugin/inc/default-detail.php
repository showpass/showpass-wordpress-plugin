<div id="page" class="flex-box entry-content pb50">
	<?php
	 $event_data = json_decode($data, true);
	if (isset($event_data['detail'])) { ?>
		<div class="layout-flex">
			<h2>Sorry, we cannot find the event that you are looking for!</h2>
		</div>
	<?php } else {
		$event = $event_data;
		$current_event = $event['id'];?>
		<div class="layout-flex event-name mb30">
			<div class="flex-100 flex-column no-border">
				<img class="mb20 mt20 border-radius-4 box-shadow-image" alt="<?php echo $event['name']; ?>" src="<?php echo $event['image_stretch_banner_lg']; ?>" />
			</div>
		</div>
		<div class="layout-flex detail event-name mb30">
			<div class="flex-100 flex-column no-border"><h1><?php echo $event['name']; ?></h1></div>
		</div>
		<div class="flex-container layout-flex">
			<div class="flex-66 flex-column no-border">
				<div>
					<span onclick="showpass.tickets.eventPurchaseWidget('<?php echo $event['slug'];?>', {'theme-primary': '#000000'})" class="expanded button large mb30 red-button pt20 pb20 hide-large">BUY TICKETS</span>
					<?php echo $event['description'];?>
				</div>
			</div>
			<div class="flex-33 flex-column event-detail no-border">
				<div>
					<div class="event-info mb30 ">
						<div class="info"><i class="fa fa-calendar icon-center"></i><?php echo showpass_get_event_date($event['starts_on'], $event['timezone'], false);?></div>
						<div class="info"><i class="fa fa-clock-o icon-center"></i> <?php echo showpass_get_event_time($event['starts_on'], $event['timezone'], false);?> - <?php echo showpass_get_event_time($event['ends_on'], $event['timezone'], false);?>
							<?php echo showpass_get_timezone_abbr($event['timezone'], false);?></div>
						<div class="info"><i class="fa fa-map-marker icon-center"></i> <?php  $location = $event['location']; echo $location['name'];?></div>
						<?php if ($event['ticket_types']) : ?><div class="info mb20"><i class="fa fa-tags icon-center"></i> <?php	print_r(showpass_get_price_range($event['ticket_types']));?></div><?php endif; ?>
						<span onclick="showpass.tickets.eventPurchaseWidget('<?php echo $event['slug'];?>', {'theme-primary': '#000000'})" class="expanded button large showpass-detail-buy mb30 mt30 red-button pt20 pb20">BUY TICKETS</span>
					</div>
					<div class="text-center mt30 mb30">
						<h2 class="mb0 text-center"><?php echo $location['name'];?></h2>
						<span class="mb30 block"><?php echo rtrim($location['street_name']);?>, <?php echo $location['city'];?></span>
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
