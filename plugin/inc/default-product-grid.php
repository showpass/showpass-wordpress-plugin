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

	$product_data = json_decode($data, true);

	// <pre style="font-size: 12px">

	// </pre>
?>

<div class="showpass-flex-box">
	<div class="showpass-layout-flex">
			<?php
			if ($product_data['count'] > 0) {
				$products = $product_data['results'];
				foreach ($products as $key => $product) { ?>
				
				<div class="flex-50 showpass-prodcut-grid-flex showpass-flex-column showpass-no-border showpass-event-card">
					<div class="showpass-event-list showpass-layout-flex m15">
						<div class="flex-100 showpass-flex-column showpass-no-border showpass-no-padding p0">
							<a 
								id="<?php echo $product['id']; ?>" 
								class="showpass-image open-product-widget ratio square" 
							>
								<?= isset($product['image']) 
									? $showpass_image_formatter->getResponsiveImage($product['image'], ['alt' => $product['name']]) 
									: sprintf('<img src="%s" alt="%s" />', plugin_dir_url(__FILE__).'../images/default-square.jpg', $product['name']);
								?>
							</a>
						</div>
						<div class="flex-100 showpass-flex-column showpass-no-border showpass-background-white">
							<div class="showpass-full-width">
								<div class="showpass-layout-flex">
									<div class="flex-100 showpass-flex-column showpass-no-border">
										<div class="showpass-product-price"><?php if ($product['product_attributes']) : ?><small class="showpass-price-display"><?php echo showpass_get_product_price_range($product['product_attributes']);?> <?php echo $product['currency']; ?></small><?php endif; ?></div>
										<div class="showpass-product-price"><?php if (!$product['product_attributes']) : ?><small class="showpass-price-display"> No Items Available</small><?php endif; ?></div>
                  </div>
								</div>
								<div class="showpass-layout-flex">
									<div class="flex-100 showpass-flex-column showpass-no-border showpass-title-wrapper">
										<div class="showpass-event-title showpass-product-grid-title">
											<h3><a class="open-product-widget" id="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a></h3>
										</div>
									</div>
								</div>
								<div class="showpass-layout-flex">
									<div class="flex-100 showpass-flex-column list-layout-flex showpass-no-border">
										<div>
											<div class="text-muted small"><?php $atts = $product['product_attributes']; echo sizeOf($atts);?> Options Available</div>
										</div>
									</div>
								</div>
								<div class="showpass-showpass-layout-flex">
										<div class="clearfix showpass-layout-flex showpass-list-button-layout">
											<div class="flex-100 showpass-flex-column list-layout-flex showpass-no-border showpass-button-pull-left">
												<div class="showpass-button-full-width-list">
													<a 
														class="showpass-list-ticket-button showpass-button open-product-widget" 
														id="<?php echo $product['id']; ?>"
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
			<?php if ($product_data['num_pages'] > 1) { ?>
			<div class="flex-100 showpass-flex-column showpass-no-border text-center">
				<ul class="showpass-pagination mb0 mt30">
					<?php for ($i = 1; $i <= $product_data['num_pages']; $i++) {
						$current = $i == $product_data['page_number'] ? 'class="current"' : '';
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
					<h1 class="mt0">Sorry, no products found!</h1>
					<?php if ($_GET) { ?>
						<a class="back-link" href="/events/">View All Products</a>
					<?php } ?>
				</div>
			<?php } ?>
	</div>
</div>
