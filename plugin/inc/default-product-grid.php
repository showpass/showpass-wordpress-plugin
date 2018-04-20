<div class="showpass-flex-box">
	<div class="showpass-layout-flex">
			<?php
			$product_data = json_decode($data, true);
			if ($product_data['count'] > 0) {
				$products = $product_data['results'];
				foreach ($products as $key => $product) { ?>
				<div class="flex-50 showpass-prodcut-grid-flex showpass-flex-column showpass-no-border showpass-event-card">
					<div class="showpass-event-list showpass-layout-flex m15">
						<div class="flex-100 showpass-flex-column showpass-no-border showpass-no-padding p0">
							<a class="showpass-image-banner open-product-widget" style="background-image: url('<?php echo $product['thumbnail'];?>');"></a>
						</div>
						<div class="flex-100 showpass-flex-column showpass-no-border showpass-background-white">
							<div class="showpass-full-width">
								<div class="showpass-layout-flex">
									<div class="flex-100 showpass-flex-column showpass-no-border">
										<div class="product-price"><?php if ($product['product_attributes']) : ?><small class="showpass-price-display"><?php echo showpass_get_product_price_range($product['product_attributes']);?> <?php echo $event['currency']; ?></small><?php endif; ?></div>
										<div class="product-price"><?php if (!$product['product_attributes']) : ?><small class="showpass-price-display"> No Items Available</small><?php endif; ?></div>
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
													<a class="showpass-list-ticket-button showpass-button open-product-widget" id="<?php echo $product['id']; ?>">Buy Now</a>
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
