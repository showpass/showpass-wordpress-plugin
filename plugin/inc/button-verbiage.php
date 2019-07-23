<?php if ($event['initiate_purchase_button'] === 'ipbd_buy_tickets') { ?>
  BUY TICKETS
<?php } else if ($event['initiate_purchase_button'] === 'ipbd_register') { ?>
  REGISTER
<?php } else if ($event['initiate_purchase_button'] === 'ipbd_buy_passes') { ?>
  BUY PASSES
<?php } else if ($event['initiate_purchase_button'] === 'ipbd_select_date') { ?>
  SELECT DATE
<?php } else if ($event['initiate_purchase_button'] === 'ipbd_select_time') { ?>
  SELECT TIME
<?php } else if ($event['initiate_purchase_button'] === 'ipbd_select_seats') { ?>
  SELECT SEATS
<?php } else if ($event['initiate_purchase_button'] === 'ipbd_rsvp') { ?>
  RSVP
<?php } else { ?>
  BUY TICKETS
<?php } ?>