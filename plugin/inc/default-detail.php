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
				<img class="showpass-detail-image" alt="<?php echo $event['name']; ?>" src="<?php if ($event['image_banner']) { echo $event['image_banner']; } else { echo plugin_dir_url(__FILE__).'../images/default-banner.jpg';}?>" />
			</div>
		</div>
		<div class="showpass-layout-flex showpass-detail-event-name">
			<div class="flex-100 showpass-flex-column showpass-no-border"><h1 class="w100"><?php echo $event['name']; ?></h1></div>
		</div>
		<div class="flex-container showpass-layout-flex">
			<div class="flex-66 showpass-flex-column showpass-no-border">
				<div class="w100">
					<?php if(showpass_ticket_sold_out($event['ticket_types'])) { ?>
						<span class="showpass-detail-buy showpass-hide-medium showpass-soldout">
							<?php echo($event['inventory_sold_out'] || $event['sold_out'] ? 'SOLD OUT' : 'NOT AVAILABLE'); ?>
						</span>
					<?php } else { ?>
						<span class="showpass-detail-buy showpass-hide-medium <?php if (!$event['external_link']) echo 'open-ticket-widget' ?>" <?php if ($event_data['show_eyereturn']) {?> data-eyereturn="<?php echo $event_data['show_eyereturn']; ?>" <?php } ?> <?php if ($event['external_link']) { ?>href="<?php echo $event['external_link']; ?>"<?php } else { ?>id="<?php echo $event['slug']; ?>"<?php } ?>>
							<?php include 'button-verbiage.php'; ?>
						</span>
					<?php } ?>
					<?php echo $event['description'];?>
				</div>
			</div>
			<div class="flex-33 showpass-flex-column showpass-no-border">
				<div class="w100">
					<div class="showpass-detail-event-date mb30">
            <?php $location = $event['location']; ?>
              <?php if (!$event['is_recurring_parent']) { ?>
                <div class="info"><i class="fa fa-calendar icon-center"></i><?php echo showpass_get_event_date($event['starts_on'], $event['timezone'], false);?></div>
                <div class="info"><i class="fa fa-clock-o icon-center"></i><?php echo showpass_get_event_time($event['starts_on'], $event['timezone'], false);?> - <?php echo showpass_get_event_time($event['ends_on'], $event['timezone'], false);?>
                <?php echo showpass_get_timezone_abbr($event['timezone'], false);?></div>
							<?php } else { ?>
                <div class="info"><i class="fa fa-calendar-plus-o icon-center"></i> Multiple Dates</div>
							<?php } ?>
  						<div class="info"><i class="fa fa-map-marker icon-center"></i><?php echo $location['name'];?></div>
  						<?php if ($event['ticket_types']) : ?>
                <div class="info mb20"><i class="fa fa-tags icon-center"></i><?php echo showpass_get_price_range($event['ticket_types']);?>
                  <?php if (showpass_get_price_range($event['ticket_types']) != 'FREE') { echo $event['currency']; } ?></div>
              <?php endif; ?>
							<?php if(showpass_ticket_sold_out($event)) {?>
								<span class="showpass-detail-buy showpass-soldout">
									<?php echo($event['inventory_sold_out'] || $event['sold_out'] ? 'SOLD OUT' : 'NOT AVAILABLE'); ?>
								</span>
							<?php } else { ?>
								<span class="showpass-detail-buy <?php if (!$event['external_link']) echo 'open-ticket-widget' ?>" <?php if ($event_data['show_eyereturn']) {?> data-eyereturn="<?php echo $event_data['show_eyereturn']; ?>" <?php } ?> <?php if ($event['external_link']) { ?>href="<?php echo $event['external_link']; ?>"<?php } else { ?>id="<?php echo $event['slug']; ?>"<?php } ?>>
                  <?php include 'button-verbiage.php'; ?>
	            	</span>
							<?php } ?>
					</div>
					<div class="text-center showpass-detail-location">
						<h3 class="showpass-event-veune-name"><?php echo $location['name'];?></h3>
						<span class="showpass-detail-address"><?php echo rtrim($location['street_name']);?>, <?php echo $location['city'];?></span>
						<iframe width="100%" height="300" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDe9oSMuAfjkjtblej94RJvQh3ioWJb4go&q=<?php echo urlencode($location['name']);?>,<?php echo urlencode($location['city']);?>+<?php echo urlencode($location['province']);?>
							&center=<?php echo $location['position'];?>" allowfullscreen>
						</iframe>
						<?php //echo do_shortcode('[codepeople-post-map name="'.$event->location->name.'" center="'.$event->location->position.'" width="100% height="300"]'); ?>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
