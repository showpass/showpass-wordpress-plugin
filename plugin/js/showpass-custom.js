(function ($, window, document) {

	const getParams = (element) => {
		return {
			'theme-primary': $(element).attr('data-color') || $('#option_widget_color').val() || '',
			'keep-shopping': $(element).attr('data-shopping') || $('#option_keep_shopping').val() || 'true',
			'theme-dark': $(element).attr('data-theme') || $('#option_theme_dark').val() || '',
			'show-description': $(element).attr('data-show-description') || $('#option_show_widget_description').val() || 'false',
			'distribution-tracking': $(element).attr('data-distribution-tracking') || $('#option_showpass_distribution_tracking').val() || ''
		}
	}

	/**
	 * Fixes issue with showpass object not initialized yet on page load
	 */
	function refresh() {
		if (typeof showpass !== "undefined") {
			init();
		} else {
			setTimeout(refresh, 2000);
		}
	}

	if (typeof showpass !== "undefined") {
		init();
	} else {
		setTimeout(refresh, 2000);
	}

	function init () {
		$(window).on('load', function () {

			showpass.tickets.addCartCountListener(function(count) {
				let html = '';
				if (count > 0) {
					html = 'Shopping Cart (' + count + ')';
					Cookies.set('cart', html);
					$('.showpass-cart-button span').html(html);
				} else {
					html = 'Shopping Cart';
					Cookies.set('cart', html);
					$('.showpass-cart-button span').html(html);
				}
			});

			// GET QUERY STING
			function getQueryStrings() {
				let assoc = {};
				let decode = function(s) {
					return decodeURIComponent(s.replace(/\+/g, " "));
				};
				let queryString = location.search.substring(1);
				var keyValues = queryString.split('&');

				for (var i in keyValues) {
					var key = keyValues[i].split('=');
					if (key.length > 1) {
						assoc[decode(key[0])] = decode(key[1]);
					}
				}
				return assoc;
			}

			var qs = getQueryStrings();
			// SET AFFILIATE COOKIE
			if (!$.isEmptyObject(qs) && qs.aff) {
				Cookies.set('affiliate', qs.aff, {
					expires: 7
				});
			}

			// SET AUTO OPEN COOKIE
			if (!$.isEmptyObject(qs) && qs.auto) {
				Cookies.set('auto', qs.auto, {
					expires: 7
				});
			}

		});

		$(document).ready(function() {
			function getQueryStrings() {
				var assoc = {};
				var decode = function(s) {
					return decodeURIComponent(s.replace(/\+/g, " "));
				};
				var queryString = location.search.substring(1);
				var keyValues = queryString.split('&');

				for (var i in keyValues) {
					var key = keyValues[i].split('=');
					if (key.length > 1) {
						assoc[decode(key[0])] = decode(key[1]);
					}
				}
				return assoc;
			}

			var qs = getQueryStrings();

			if (qs.auto) {
				var slug = qs.auto;
				setTimeout(function() {
					Cookies.remove('auto');
					showpass.tickets.eventPurchaseWidget(slug, params);
				}, 500);
			}

			$('body').on('click', '.open-calendar-widget', function(e) {
				e.preventDefault();

				let id = $(this).attr('data-org-id');
				let params = {
					'theme-primary': $(this).attr('data-color') || $('#option_widget_color').val(),
					'keep-shopping': false,
					'tags': $(this).attr('data-tags')
				};

				showpass.tickets.calendarWidget(id, params);
			});

			const embeddedCalendarExists = document.getElementById('showpass-calendar-widget');
			if (embeddedCalendarExists) {
				let script = document.createElement("script");
				script.type = "text/javascript";
				script.src = 'https://showpass.com/static/dist/sdk.js';
				script.onload = function() {
					const id = embeddedCalendarExists.getAttribute('data-org-id');
					let params = {
						'theme-primary': $('#option_widget_color').val(),
					};
					if (embeddedCalendarExists.getAttribute('data-tags')) {
						const tags = {
							tags: embeddedCalendarExists.getAttribute('data-tags')
						};
						params = Object.assign(params, tags);
					}
					showpass.tickets.mountCalendarWidget(id, params);
				};
				document.body.appendChild(script);
			}

			$('body').on('click', '.open-product-widget', function(e) {
				e.preventDefault();

				let id = $(this).attr('id');
				let params = getParams(this);

				if ($(this).attr('data-tracking')) {
					params['tracking-id'] = $(this).attr('data-tracking');
				}

				// Overwrite tracking-id if set in URL
				if (Cookies.get('affiliate')) {
					params['tracking-id'] = Cookies.get('affiliate');
				}

				showpass.tickets.productPurchaseWidget(id, params);
			});

			const openShowpassWidget = (slug, params) => {
				const openWidget = () => {
					showpass.tickets.eventPurchaseWidget(slug, params);
				}

				/**
				 * Handle the redirect if distribution partner with an external link
				 */
				if (params['data-distribution'] !== '') {
					const checkEvent = async () => {
						try {
							const response = await fetch('https://www.showpass.com/api/public/events/' + slug + '/')
							if (response) {
								const data = await response.json();
								if (data) {
									if (data.id && data.external_link) {
										window.open(data.external_link, '_blank');
									} else {
										openWidget();
									}
								}
								return data;
							}
							return response;
						} catch (error) {
							openWidget();
						};
					}
					checkEvent();
				} else {
					openWidget();
				}
			}

			$('body').on('click', 'a[href*="showpass.com"].force-showpass-widget', function (e) {
				console.log('test');
				e.preventDefault();
				slug = $(this).attr('href').split('.com/')[1];

				let params = getParams(this);

				if ($(this).attr('data-tracking')) {
					params['tracking-id'] = $(this).attr('data-tracking');
				}

				/**
				 * Add query parameters if distribution tracking is enabled
				 */

				// Overwrite tracking-id if set in URL
				if (Cookies.get('affiliate')) {
					params['tracking-id'] = Cookies.get('affiliate');
				}

				openShowpassWidget(slug, params);
			});

			$('body').on('click', '.open-ticket-widget', function (e) {
				e.preventDefault();

				let slug = $(this).attr('id');
				let params = getParams(this);

				if ($(this).attr('data-tracking')) {
					params['tracking-id'] = $(this).attr('data-tracking');
				}

				/**
				 * Add query parameters if distribution tracking is enabled
				 */

				// Overwrite tracking-id if set in URL
				if (Cookies.get('affiliate')) {
					params['tracking-id'] = Cookies.get('affiliate');
				}

				openShowpassWidget(slug, params);

			});

			$('.showpass-cart-button').on('click', function(e) {
				e.preventDefault();
				let params = getParams(this);
				showpass.tickets.checkoutWidget(params);
			});

			if (Cookies.get('cart')) {
				$('.showpass-cart-button span').html(Cookies.get('cart'));
			}

			var span = document.createElement('span');

			span.className = 'fa';
			span.style.display = 'none';
			document.body.insertBefore(span, document.body.firstChild);

			function css(element, property) {
				return window.getComputedStyle(element, null).getPropertyValue(property);
			}

			if (css(span, 'font-family') !== 'FontAwesome') {
				var headHTML = document.head.innerHTML;
				headHTML += '<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">';
				document.head.innerHTML = headHTML;
			}
			document.body.removeChild(span);

			/*
			* Related events select box widget toggle
			*/

			$('.showpass-date-select').on('change', function(e) {
				var slug = $(this).val();
				if (slug != '') {
					let params = getParams(this);

					if (Cookies.get('affiliate')) {
						params['tracking-id'] = Cookies.get('affiliate');
					}

					showpass.tickets.eventPurchaseWidget(slug, params);
				}
			});

		});

		/*
		* Decorate iFrame for GA cross domain tracking
		*/
		const mutationObserver = new MutationObserver(function(mutations) {
			mutations.forEach(function(mutation) {
				if (mutation.target.className.includes('showpass-widget-body')) {
					let gobj = window[window.GoogleAnalyticsObject];
					let tracker, linker;
					let iFrame = document.getElementById('showpass-widget');
					if (gobj) {
						tracker = gobj.getAll()[0];
						linker = new window.gaplugins.Linker(tracker);
						iFrame.src = linker.decorate(iFrame.src);
					}
				}
			});
		});

		mutationObserver.observe(document.documentElement, { attributes: true });
	}

})(jQuery, window, document);
