<?php if ($event['initiate_purchase_button'] === 'ipbd_buy_tickets'): ?>
  BUY TICKETS
<?php elseif ($event['initiate_purchase_button'] === 'ipbd_register'): ?>
  REGISTER
<?php elseif ($event['initiate_purchase_button'] === 'ipbd_buy_passes'): ?>
  BUY PASSES
<?php elseif ($event['initiate_purchase_button'] === 'ipbd_select_date'): ?>
  SELECT DATE
<?php elseif ($event['initiate_purchase_button'] === 'ipbd_select_time'): ?>
  SELECT TIME
<?php elseif ($event['initiate_purchase_button'] === 'ipbd_rsvp'): ?>
  RSVP
<?php elseif ($event['initiate_purchase_button'] === 'ipbd_get_tickets'): ?>
  GET TICKETS
<?php elseif ($event['initiate_purchase_button'] === 'ipbd_select_seats'): ?>
  SELECT SEATS
<?php else: ?>
  BUY TICKETS
<?php endif ?>
