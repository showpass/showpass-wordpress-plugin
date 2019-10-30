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
						<span class="showpass-detail-buy showpass-hide-medium <?php if (!$event['external_link']) echo 'open-ticket-widget' ?>" <?php if (isset($event['show_eyereturn'])) {?> data-eyereturn="<?php echo $event['show_eyereturn']; ?>" <?php } ?> <?php if ($event['external_link']) { ?>href="<?php echo $event['external_link']; ?>"<?php } else { ?>id="<?php echo $event['slug']; ?>"<?php } ?>>
							<?php include 'button-verbiage.php'; ?>
						</span>
					<?php } ?>
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
								<div class="info"><i class="fa fa-map-marker icon-center"></i><?= $event['location']['name'] ?></div>
							</div>
						</div>
					</div>
					<!-- Event Date(s) & Badges -->
					<div class="showpass-detail-event-date mb30">
						<?php if ($event['ticket_types']) : ?>
							<div class="info mb20"><i class="fa fa-tags icon-center"></i><?php echo showpass_get_price_range($event['ticket_types']);?>
								<?php if (showpass_get_price_range($event['ticket_types']) != 'FREE') { echo $event['currency']; } ?></div>
						<?php endif; ?>
						<?php if(showpass_ticket_sold_out($event)) {?>
							<span class="showpass-detail-buy showpass-soldout">
								<?php echo($event['inventory_sold_out'] || $event['sold_out'] ? 'SOLD OUT' : 'NOT AVAILABLE'); ?>
							</span>
						<?php } else { ?>
							<span 
								class="showpass-detail-buy <?php if (!$event['external_link']) echo 'open-ticket-widget' ?>" <?php if (isset($event['show_eyereturn'])) {?> data-eyereturn="<?php echo $event['show_eyereturn']; ?>" <?php } ?> <?php if ($event['external_link']) { ?>href="<?php echo $event['external_link']; ?>"<?php } else { ?>id="<?php echo $event['slug']; ?>"<?php } ?>
								data-show-description="<?= $show_widget_description ?>">
								<?php include 'button-verbiage.php'; ?>
							</span>
						<?php } ?>
					</div>
					<div class="text-center showpass-detail-location">
						<?php $location = $event['location'] ?>
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
