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
            <?php } else if (showpass_ticket_sold_out($event)) { // if tickets sold out ?>
              <a class="showpass-image-banner showpass-hide-mobile showpass-soldout" style="background-image: url('<?php if ($event['image_banner']) { echo $event['image_banner']; } else { echo plugin_dir_url(__FILE__).'../images/default-banner.jpg';}?>');"></a>
              <a class="showpass-image showpass-hide-large showpass-soldout" style="background-image: url('<?php echo $event['image_banner'];?>');"></a>
            <?php } else { ?>
              <a class="showpass-image-banner showpass-hide-mobile <?php if (!$event['external_link']) echo 'open-ticket-widget' ?>" <?php if ($event_data['show_eyereturn']) {?> data-eyereturn="<?php echo $event_data['show_eyereturn']; ?>" <?php } ?> <?php if ($event_data['tracking_id']) {?> data-tracking="<?php echo $event_data['tracking_id']; ?>" <?php } ?>  <?php if ($event['external_link']) { ?>href="<?php echo $event['external_link']; ?>"<?php } else { ?>id="<?php echo $event['slug']; ?>"<?php } ?> style="background-image: url('<?php if ($event['image']) { echo $event['image']; } else { echo plugin_dir_url(__FILE__).'../images/default-square.jpg';}?>');" href="/<?php if ($detail_page) { echo $detail_page; } else { echo 'event-detail'; } ?>/?slug=<?php echo $event['slug']; ?>"></a>
              <a class="showpass-image showpass-hide-large <?php if (!$event['external_link']) echo 'open-ticket-widget' ?>" <?php if ($event_data['show_eyereturn']) {?> data-eyereturn="<?php echo $event_data['show_eyereturn']; ?>" <?php } ?> <?php if ($event_data['tracking_id']) {?> data-tracking="<?php echo $event_data['tracking_id']; ?>" <?php } ?> <?php if ($event['external_link']) { ?>href="<?php echo $event['external_link']; ?>"<?php } else { ?>id="<?php echo $event['slug']; ?>"<?php } ?> style="background-image: url('<?php if ($event['image_banner']) { echo $event['image_banner']; } else { echo plugin_dir_url(__FILE__).'../images/default-banner.jpg';}?>');" href="/<?php if ($detail_page) { echo $detail_page; } else { echo 'event-detail'; } ?>/?slug=<?php echo $event['slug']; ?>"></a>
            <?php } ?>
          </div>
          <div class="list-info showpass-flex-column list-layout-flex showpass-no-border showpass-background-white">
            <div class="showpass-space-between showpass-full-width showpass-layout-fill">
              <div class="showpass-layout-flex">
                <div class="flex-100 showpass-flex-column list-layout-flex showpass-no-border">
                  <div>
                    <?php if (!showpass_ticket_sold_out($event)) : ?>
                      <small class="showpass-price-display">
                        <?php if ($event['is_recurring_parent']) { ?>
                            Multiple Dates
                        <?php } else {?>
                          <?php echo showpass_get_price_range($event['ticket_types']);?>
                          <?php if (showpass_get_price_range($event['ticket_types']) != 'FREE') { echo $event['currency']; } ?>
                        <?php } ?>
                      </small>
                    <?php endif; ?>
                  </div>
                  <div><?php if (showpass_ticket_sold_out($event)) : ?><small class="showpass-price-display"> No Tickets Available</small><?php endif; ?></div>
                </div>
              </div>
              <div class="showpass-layout-flex">
                <div class="flex-100 showpass-flex-column list-layout-flex showpass-no-border showpass-title-wrapper">
                  <div class="showpass-event-title">
                    <?php if ($detail_page) { ?>
                      <h3><a href="/<?php echo $detail_page ?>/?slug=<?php echo $event['slug']; ?>"><?php echo $event['name']; ?></a></h3>
                    <?php } else {?>
                      <h3><a <?php if (!$event['external_link']) { ?>class="open-ticket-widget"<?php } ?> <?php if ($event_data['show_eyereturn']) {?> data-eyereturn="<?php echo $event_data['show_eyereturn']; ?>" <?php } ?> <?php if ($event_data['tracking_id']) {?> data-tracking="<?php echo $event_data['tracking_id']; ?>" <?php } ?> <?php if ($event['external_link']) { ?>href="<?php echo $event['external_link']; ?>"<?php } else { ?>id="<?php echo $event['slug']; ?>"<?php } ?>><?php echo $event['name']; ?></a></h3>
                    <?php } ?>
                  </div>
                </div>
              </div>

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
                    <?= showpass_display_date($event) ?>
                    <div class="info"><i class="fa fa-map-marker icon-center"></i><?php $location = $event['location']; echo $location['name'];?></div>
                  </div>
                </div>
              </div>
              <!-- Event Date(s) & Badges -->
                
              <div class="showpass-layout-flex showpass-list-button-layout">
                <div class="flex-50 showpass-flex-column list-layout-flex showpass-no-border showpass-button-pull-left">
                  <div class="showpass-button-full-width-list">
                    <?php if(showpass_ticket_sold_out($event)) { ?>
                      <a class="showpass-list-ticket-button showpass-button showpass-soldout">
                        <?php echo($event['inventory_sold_out'] || $event['sold_out'] ? 'SOLD OUT' : 'NOT AVAILABLE'); ?>
                      </a>
                    <?php } else { ?>
                      <a class="showpass-list-ticket-button showpass-button <?php if (!$event['external_link']) echo 'open-ticket-widget' ?>" <?php if (isset($event_data['show_eyereturn'])) {?> data-eyereturn="<?php echo $event_data['show_eyereturn']; ?>" <?php } ?> <?php if (isset($event_data['tracking_id'])) {?> data-tracking="<?php echo $event_data['tracking_id']; ?>" <?php } ?> <?php if ($event['external_link']) { ?>href="<?php echo $event['external_link']; ?>"<?php } else { ?>id="<?php echo $event['slug']; ?>"<?php } ?>>
                        <?php include 'button-verbiage.php'; ?>
                      </a>
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
