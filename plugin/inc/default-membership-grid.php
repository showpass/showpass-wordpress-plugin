<?php 
global $showpass_image_formatter;
/**
 * Custom breakpoints for responsive image.
 * Add max 980-1920 breakpoint, column layout only allows for a max width of 640px.
 * 600px x 600px is already cached, so use that image.
 */
$image_breakpoints = [
    [600, '(max-width: 1920px) and (min-width: 981px)'],
    [960, '(max-width: 980px) and (min-width: 781px)'], 
    [780, '(max-width: 780px) and (min-width: 601px)'], 
    [600, '(max-width: 600px) and (min-width: 376px)'], 
    [375, '(max-width: 375px)']
];

$membership_data = json_decode($data, true);
?>

<div class="showpass-flex-box">
    <div class="showpass-layout-flex">
        <?php
        $results_length = count($membership_data['results']);
        if ($results_length > 0) {
            $memberships = $membership_data['results'];
            foreach ($memberships as $key => $membership) {
                ?>
                <div class="showpass-flex-column showpass-no-border showpass-event-card showpass-grid">
                    <div class="showpass-membership-grid showpass-layout-flex m15">
                        <div class="flex-100 showpass-flex-column showpass-no-border showpass-no-padding p0">
                            <a href="<?= $membership_href ?>" class="showpass-image ratio banner">
                                <?= isset($membership['image']) 
                                    ? $showpass_image_formatter->getResponsiveImage($membership['image'], ['alt' => $membership['name'], 'title' => $membership['name'], 'breakpoints' => $image_breakpoints]) 
                                    : sprintf('<img src="%s" alt="%s" />', plugin_dir_url(__FILE__).'../images/default-banner.jpg', $membership['name']);
                                ?>
                            </a>
                        </div>
                        <div class="flex-100 showpass-flex-column showpass-no-border showpass-background-white">
                            <div class="showpass-full-width">
                                <div class="showpass-layout-flex">
                                    <div class="flex-100 showpass-flex-column showpass-no-border">
                                        <div class="showpass-membership-price"><?php if ($membership['membership_levels']) : ?><small class="showpass-price-display"><?php echo showpass_get_product_price_range($membership['membership_levels']);?> <?php echo $membership['currency']; ?></small><?php endif; ?></div>
                                        <div class="showpass-membership-price"><?php if (!$membership['membership_levels']) : ?><small class="showpass-price-display"> No Items Available</small><?php endif; ?></div>
                                    </div>
                                </div>
                                <div class="showpass-layout-flex">
                                    <div class="flex-100 showpass-flex-column showpass-no-border showpass-title-wrapper">
                                        <div class="showpass-event-title showpass-membership-grid-title">
                                            <h3><a class="open-membership-widget" id="<?php echo $membership['id']; ?>"><?php echo $membership['name']; ?></a></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="showpass-layout-flex">
                                    <div class="flex-100 showpass-flex-column list-layout-flex showpass-no-border">
                                        <div>
                                            <div class="text-muted small"><?php $atts = $membership['membership_levels']; echo sizeOf($atts);?> Options Available</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="showpass-showpass-layout-flex">
                                    <div class="clearfix showpass-layout-flex showpass-list-button-layout">
                                        <div class="flex-100 showpass-flex-column list-layout-flex showpass-no-border showpass-button-pull-left">
                                            <div class="showpass-button-full-width-list">
                                                <a 
                                                    class="showpass-list-ticket-button showpass-button open-membership-widget" 
                                                    id="<?php echo $membership['id']; ?>"
                                                    data-show-description="<?= $show_widget_description ?>"
                                                >Buy Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if ($membership_data['num_pages'] > 1) { ?>
                <div class="flex-100 showpass-flex-column showpass-no-border text-center">
                    <ul class="showpass-pagination mb0 mt30">
                        <?php for ($i = 1; $i <= $membership_data['num_pages']; $i++) {
                            $current = $i == $membership_data['page_number'] ? 'class="current"' : '';
                            if ($current != '') { ?>
                                <li <?php echo $current;?>><?php echo $i;?></li>
                            <?php } else { ?>
                                <li><a href="<?php echo showpass_get_events_next_prev($i);?>"><?php echo $i; ?></a></li>
                            <?php } 
                        } ?>
                    </ul>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="flex-100">
                <h1 class="mt0">Sorry, no memberships found!</h1>
                <?php if ($_GET) { ?>
                    <a class="back-link" href="/memberships/">View All Memberships</a>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>