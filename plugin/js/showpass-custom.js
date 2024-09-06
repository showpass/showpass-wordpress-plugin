(function ($, window, document) {

	const getParams = (element) => {
		return {
			'theme-primary': $(element).attr('data-color') || $('#option_widget_color').val() || '',
			'keep-shopping': $(element).attr('data-shopping') || $('#option_keep_shopping').val() || 'true',
			'theme-dark': $(element).attr('data-theme') || $('#option_theme_dark').val() || '',
			'show-description': $(element).attr('data-show-description') || $('#option_show_widget_description').val() || 'false',
			'show-specific-tickets': $(element).attr('data-show-specific-tickets') || ''
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
				let params = {
					'theme-primary': $('#option_widget_color').val(),
					'keep-shopping': $('#option_keep_shopping').val(),
					'show-description': $('#option_show_widget_description').val()
				};
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

				let useBeta = $('#option_use_showpass_beta').val();
				let useDemo = $('#option_use_showpass_demo').val();
				if (useBeta) {
					script.src = 'https://beta.showpass.com/static/dist/sdk.js';
				} else if (useDemo) {
					script.src = 'https://demo.showpass.com/static/dist/sdk.js';
				}

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
				openWidget();
			}

			$('body').on('click', 'a[href*="showpass.com"].force-showpass-widget', function (e) {
				e.preventDefault();
				slug = $(this).attr('href').split('.com/')[1];

				let params = getParams(this);

				if ($(this).attr('data-tracking')) {
					params['tracking-id'] = $(this).attr('data-tracking');
				}

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


		/**
		 * Shared function to decorate iframe - we default to cookie version because its more reliable
		 * @param {object} showpass widget iframe object
		 */
		const decorateIframe = (iFrame) => {
			// For analytics.js (UA)
			// We use the linker provided by analytics.js to decorate the iframe src
			let gobj = window[window.GoogleAnalyticsObject];
			if (gobj) {
				let tracker = gobj.getAll()[0];
				let linker = new window.gaplugins.Linker(tracker);
				iFrame.src = linker.decorate(iFrame.src);
			}

			if (typeof document.cookie === "string" && document.cookie !== "") {
				// Get the _ga from cookies and parse it to extract client_id and session_id.
				// This is used as a fallback for GTM implementations.
				let cookie = {};
				document.cookie.split(";").forEach(function (el) {
					const splitCookie = el.split("=");
					const key = splitCookie[0].trim();
					const value = splitCookie[1];
					cookie[key] = value;
				});
				// Parse the _ga cookie to extract client_id and session_id.
				// A _ga cookie will look something like GA1.1.1194072907.1685136322
				const gaCookie = cookie["_ga"];
				if (gaCookie) {
					const client_id = gaCookie.substring(6); // using the example above, this will return "1194072907.1685136322"
					const session_id = client_id.split(".")[1]; // ["1194072907", "1685136322"]

					if (
						!isNaN(Number(client_id)) &&
						!isNaN(Number(session_id))
					) {
						let url = new URL(iFrame.src);
						url.searchParams.append("parent_client_id", client_id);
						url.searchParams.append(
							"parent_session_id",
							session_id
						);
						iFrame.src = url.toString();
					}
				}
			}

			// Pass the parent page's referrer to our iFrame.
			// When the referrer is unavailable (ie. direct visit), the web-app
			// should not inject and GA4 defaults to the default behaviour.
			const referrer = document.referrer || "";
			if (referrer) {
				let url = new URL(iFrame.src);
				url.searchParams.append("parent_document_referrer", referrer);
				iFrame.src = url.toString();
			}
		}

		/*
		* Decorate iFrame for GA cross domain tracking
	    * This only works for pop ups, for embedded calendar we need to use a watcher
		*/
		const mutationObserver = new MutationObserver(function(mutations) {
			mutations.forEach(function(mutation) {
				if (mutation.target.className.includes('showpass-widget-body') && document) {

					let iFrame = document.getElementById('showpass-widget');

					// if query params already exist, exit
					let queryParams = new URLSearchParams(iFrame.src);
					if (
						queryParams.get("parent_client_id") ||
						queryParams.get("parent_session_id") ||
						queryParams.get("parent_document_referrer")
					) {
						return;
					}
					decorateIframe(iFrame);
				}
			});
		});

		mutationObserver.observe(document.documentElement, { attributes: true });

		let calendarDIV = document.getElementById("showpass-calendar-widget");
		if (calendarDIV) {
			/** Wrap IFrame for embeded calendar */
			function wrapIFrame(iFrame) {
				clearInterval(findIFrameInterval);
				decorateIframe(iFrame);
			}
			const findIFrameInterval = setInterval(findIFrame, 100);
			function findIFrame() {
				let iFrame = document.getElementsByClassName("showpass-widget-iframe");
				if (iFrame && iFrame[0] && iFrame[0].src) {
				wrapIFrame(iFrame[0]);
			}
		}
	}
}})(jQuery, window, document);
