(function ($, window, document) {
	const getParams = (element) => {
		return {
			"theme-primary":
				$(element).attr("data-color") ||
				$("#option_widget_color").val() ||
				"",
			"keep-shopping":
				$(element).attr("data-shopping") ||
				$("#option_keep_shopping").val() ||
				"true",
			"theme-dark":
				$(element).attr("data-theme") ||
				$("#option_theme_dark").val() ||
				"",
			"show-description":
				$(element).attr("data-show-description") ||
				$("#option_show_widget_description").val() ||
				"false",
			"show-specific-tickets":
				$(element).attr("data-show-specific-tickets") || "",
		};
	};

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

	function init() {
		$(window).on("load", function () {
			showpass.tickets.addCartCountListener(function (count) {
				let html = "";
				if (count > 0) {
					html = "Shopping Cart (" + count + ")";
					Cookies.set("cart", html);
					$(".showpass-cart-button span").html(html);
				} else {
					html = "Shopping Cart";
					Cookies.set("cart", html);
					$(".showpass-cart-button span").html(html);
				}
			});

			// GET QUERY STING
			function getQueryStrings() {
				let assoc = {};
				let decode = function (s) {
					return decodeURIComponent(s.replace(/\+/g, " "));
				};
				let queryString = location.search.substring(1);
				var keyValues = queryString.split("&");

				for (var i in keyValues) {
					var key = keyValues[i].split("=");
					if (key.length > 1) {
						assoc[decode(key[0])] = decode(key[1]);
					}
				}
				return assoc;
			}

			var qs = getQueryStrings();
			// SET AFFILIATE COOKIE
			if (!$.isEmptyObject(qs) && qs.aff) {
				Cookies.set("affiliate", qs.aff, {
					expires: 7,
				});
			}

			// SET AUTO OPEN COOKIE
			if (!$.isEmptyObject(qs) && qs.auto) {
				Cookies.set("auto", qs.auto, {
					expires: 7,
				});
			}
		});

		$(document).ready(function () {
			function getQueryStrings() {
				var assoc = {};
				var decode = function (s) {
					return decodeURIComponent(s.replace(/\+/g, " "));
				};
				var queryString = location.search.substring(1);
				var keyValues = queryString.split("&");

				for (var i in keyValues) {
					var key = keyValues[i].split("=");
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
					"theme-primary": $("#option_widget_color").val(),
					"keep-shopping": $("#option_keep_shopping").val(),
					"show-description": $(
						"#option_show_widget_description"
					).val(),
				};
				setTimeout(function () {
					Cookies.remove("auto");
					showpass.tickets.eventPurchaseWidget(slug, params);
				}, 500);
			}

			$("body").on("click", ".open-calendar-widget", function (e) {
				e.preventDefault();

				let id = $(this).attr("data-org-id");
				let params = {
					"theme-primary":
						$(this).attr("data-color") ||
						$("#option_widget_color").val(),
					"keep-shopping": false,
					tags: $(this).attr("data-tags"),
				};

				showpass.tickets.calendarWidget(id, params);
			});

			function initializeShowpassEmbeddedWidgets() {
				const embeddedCalendarWidget = document.getElementById(
					"showpass-calendar-widget"
				);

				const embeddedCartWidget = document.getElementById(
					"showpass-cart-widget"
				);

				// Check for embedded event/product/membership widgets - look for elements with IDs starting with 'showpass-'
				// and containing '-widget-'
				const embeddedPurchaseWidgets = [];
				document
					.querySelectorAll('[id^="showpass-"][id*="-widget-"]')
					.forEach((widget) => {
						embeddedPurchaseWidgets.push(widget);
					});

				if (
					embeddedCalendarWidget ||
					embeddedPurchaseWidgets.length > 0 ||
					embeddedCartWidget
				) {
					let script = document.createElement("script");
					script.type = "text/javascript";
					script.src = "https://showpass.com/static/dist/sdk.js";

					let useBeta = $("#option_use_showpass_beta").val();
					let useDemo = $("#option_use_showpass_demo").val();
					if (useBeta) {
						script.src =
							"https://beta.showpass.com/static/dist/sdk.js";
					} else if (useDemo) {
						script.src =
							"https://demo.showpass.com/static/dist/sdk.js";
					}

					script.onload = function () {
						if (embeddedCalendarWidget) {
							const id =
								embeddedCalendarWidget.getAttribute(
									"data-org-id"
								);
							let params = {
								"theme-primary": $(
									"#option_widget_color"
								).val(),
							};
							if (
								embeddedCalendarWidget.getAttribute("data-tags")
							) {
								const tags = {
									tags: embeddedCalendarWidget.getAttribute(
										"data-tags"
									),
								};
								params = Object.assign(params, tags);
							}
							showpass.tickets.calendarWidget(
								id,
								params,
								"showpass-calendar-widget"
							);
						}

						if (embeddedCartWidget) {
							let params = {
								"theme-primary":
									$("#option_widget_color").val() || "",
								"keep-shopping":
									$("#option_keep_shopping").val() || "true",
								"theme-dark":
									$("#option_theme_dark").val() || "",
							};
							showpass.tickets.checkoutWidget(
								params,
								"showpass-cart-widget"
							);
						}

						if (embeddedPurchaseWidgets.length > 0) {
							embeddedPurchaseWidgets.forEach(function (widget) {
								const slug = widget.getAttribute("data-slug");
								const widgetType =
									widget.getAttribute("data-type") || "event"; // Default to event if not specified

								let params = getParams(widget);

								if (widgetType === "product") {
									showpass.tickets.productPurchaseWidget(
										slug,
										params,
										widget.id
									);
								} else if (widgetType === "membership") {
									showpass.tickets.membershipPurchaseWidget(
										slug,
										params,
										widget.id
									);
								} else {
									// Default to event widget
									showpass.tickets.eventPurchaseWidget(
										slug,
										params,
										widget.id
									);
								}
							});
						}
					};
					document.body.appendChild(script);
				}
			}

			initializeShowpassEmbeddedWidgets();

			const openShowpassWidget = (slug, params, widgetType) => {
				if (widgetType === "product") {
					showpass.tickets.productPurchaseWidget(slug, params);
				} else if (widgetType === "membership") {
					showpass.tickets.membershipPurchaseWidget(slug, params);
				} else {
					// Default to event widget
					showpass.tickets.eventPurchaseWidget(slug, params);
				}
			};

			$("body").on("click", ".open-membership-widget", function (e) {
				e.preventDefault();

				let id = $(this).attr("id");
				let params = getParams(this);

				if ($(this).attr("data-tracking")) {
					params["tracking-id"] = $(this).attr("data-tracking");
				}

				// Overwrite tracking-id if set in URL
				if (Cookies.get("affiliate")) {
					params["tracking-id"] = Cookies.get("affiliate");
				}

				openShowpassWidget(id, params, "membership");
			});

			$("body").on("click", ".open-ticket-widget", function (e) {
				e.preventDefault();

				let slug = $(this).attr("id");
				let params = getParams(this);

				if ($(this).attr("data-tracking")) {
					params["tracking-id"] = $(this).attr("data-tracking");
				}

				// Overwrite tracking-id if set in URL
				if (Cookies.get("affiliate")) {
					params["tracking-id"] = Cookies.get("affiliate");
				}

				openShowpassWidget(slug, params, "event");
			});

			$("body").on("click", ".open-product-widget", function (e) {
				e.preventDefault();

				let id = $(this).attr("id");
				let params = getParams(this);

				if ($(this).attr("data-tracking")) {
					params["tracking-id"] = $(this).attr("data-tracking");
				}

				// Overwrite tracking-id if set in URL
				if (Cookies.get("affiliate")) {
					params["tracking-id"] = Cookies.get("affiliate");
				}

				openShowpassWidget(id, params, "product");
			});

			$("body").on(
				"click",
				'a[href*="showpass.com"].force-showpass-widget',
				function (e) {
					e.preventDefault();

					let slug;
					const href = $(this).attr("href");

					if (href.includes("/m/")) {
						// For membership URLs
						slug = href.split("/m/")[1];
					} else {
						try {
							const url = new URL(href);
							slug = url.pathname.substring(1); // Remove leading slash
						} catch (e) {
							slug = href.split(".com/")[1];
						}
					}

					let params = getParams(this);

					if ($(this).attr("data-tracking")) {
						params["tracking-id"] = $(this).attr("data-tracking");
					}

					// Overwrite tracking-id if set in URL
					if (Cookies.get("affiliate")) {
						params["tracking-id"] = Cookies.get("affiliate");
					}

					let widgetType = "event";
					if (href.includes("/m/")) {
						widgetType = "membership";
					}

					if ($(this).attr("data-type")) {
						widgetType = $(this).attr("data-type");
					}

					openShowpassWidget(slug, params, widgetType);
				}
			);

			$(".showpass-cart-button").on("click", function (e) {
				e.preventDefault();
				let params = getParams(this);
				showpass.tickets.checkoutWidget(params);
			});

			if (Cookies.get("cart")) {
				$(".showpass-cart-button span").html(Cookies.get("cart"));
			}

			/*
			 * Related events select box widget toggle
			 */

			$(".showpass-date-select").on("change", function (e) {
				var slug = $(this).val();
				if (slug != "") {
					let params = getParams(this);

					if (Cookies.get("affiliate")) {
						params["tracking-id"] = Cookies.get("affiliate");
					}

					showpass.tickets.eventPurchaseWidget(slug, params);
				}
			});
		});

		/**
		 * Shared function to decorate iframe - handles both cookie-based tracking and query parameters
		 * @param {HTMLIFrameElement} iFrame - The iframe element to decorate
		 */
		const decorateIframe = (iFrame) => {
			if (!iFrame || !iFrame.src) {
				console.warn("Invalid iframe provided to decorateIframe");
				return;
			}

			// Prevent multiple decorations
			if (iFrame.dataset.decorated) {
				return;
			}

			let url = new URL(iFrame.src);

			// 1. Handle cookie-based tracking (GA and Facebook)
			if (typeof document.cookie === "string" && document.cookie !== "") {
				// Parse cookies into an object
				let cookie = {};
				document.cookie.split(";").forEach(function (el) {
					const splitCookie = el.split("=");
					const key = splitCookie[0].trim();
					const value = splitCookie[1];
					cookie[key] = value;
				});

				// Parse the _ga cookie to extract client_id and session_id.
				// A _ga cookie typically looks like GA1.1.1194072907.1685136322
				const gaCookie = cookie["_ga"];
				if (gaCookie) {
					const client_id = gaCookie.substring(6); // Example: "1194072907.1685136322"
					const session_id = client_id.split(".")[1]; // ["1194072907", "1685136322"]

					if (
						!isNaN(Number(client_id)) &&
						!isNaN(Number(session_id))
					) {
						url.searchParams.append("parent_client_id", client_id);
						url.searchParams.append(
							"parent_session_id",
							session_id
						);
					}
				}

				// Add fbclid from _fbc cookie if present and properly formatted
				const fbcCookie = cookie["_fbc"];
				if (fbcCookie) {
					const parts = fbcCookie.split(".");
					// Expecting exactly 4 parts; use the last part as fbclid
					if (parts.length === 4) {
						url.searchParams.append("fbclid", parts[3]);
					}
				}
			}

			// 2. Pass the parent page's referrer to our iFrame
			const referrer = document.referrer || "";
			if (referrer) {
				url.searchParams.append("parent_document_referrer", referrer);
			}

			// 3. Add current page query parameters
			// This is REQUIRED for the checkout widget to work properly with Affirm redirects
			const currentUrl = new URL(window.location.href);
			const queryParams = currentUrl.searchParams;

			if (queryParams.toString()) {
				queryParams.forEach((value, key) => {
					// Check if parameter already exists in iframe src to avoid duplicates
					if (!url.searchParams.has(key)) {
						url.searchParams.append(key, value);
					}
				});
			}

			// Update iframe src and mark as decorated
			iFrame.src = url.toString();
			iFrame.dataset.decorated = "true";

			console.log(
				"Decorated iframe with tracking parameters:",
				url.toString()
			);
		};

		/**
		 * Sets up observer to watch for ANY Showpass iframe anywhere in the document
		 * Handles both popup widgets and embedded widgets
		 */
		const setupShowpassIframeObserver = () => {
			const documentObserver = new MutationObserver((mutations) => {
				mutations.forEach((mutation) => {
					if (mutation.type === "childList") {
						mutation.addedNodes.forEach((node) => {
							if (node.nodeType === Node.ELEMENT_NODE) {
								// Check if the node itself is a Showpass iframe
								if (
									node.tagName === "IFRAME" &&
									node.src &&
									node.src.includes("showpass.com") &&
									!node.dataset.decorated
								) {
									setTimeout(() => {
										decorateIframe(node);
									}, 100);
								}

								// Look for any Showpass iframes within the added node
								if (node.getElementsByTagName) {
									const iframes = node.getElementsByTagName('iframe');
									for (let iframe of iframes) {
										if (iframe.src && iframe.src.includes("showpass.com") && !iframe.dataset.decorated) {
											decorateIframe(iframe);
										}
									}
								}
							}
						});
					}

					// Also watch for src attribute changes on existing iframes
					if (
						mutation.type === "attributes" &&
						mutation.attributeName === "src"
					) {
						const target = mutation.target;
						if (
							target.tagName === "IFRAME" &&
							target.src &&
							target.src.includes("showpass.com") &&
							!target.dataset.decorated
						) {
							setTimeout(() => {
								decorateIframe(target);
							}, 100);
						}
					}
				});
			});

			documentObserver.observe(document.body, {
				childList: true,
				subtree: true,
				attributes: true,
				attributeFilter: ["src"],
			});

			return documentObserver;
		};

		// Initialize the observer automatically
		setupShowpassIframeObserver();
	}
})(jQuery, window, document);
