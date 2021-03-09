(function($) {
    $(window).on('load', function() {

        showpass.tickets.addCartCountListener(function(count) {
            var html = '';
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
            var params = {
                'theme-primary': $('#option_widget_color').val() || '',
                'keep-shopping': $('#option_keep_shopping').val() || 'true',
                'theme-dark': $('#option_theme_dark').val() || '',
                'show-description': $('#option_show_widget_description').val() || 'false'
            };
            setTimeout(function() {
                Cookies.remove('auto');
                showpass.tickets.eventPurchaseWidget(slug, params);
            }, 500);
        }

		$('body').on('click', '.open-calendar-widget', function(e) {
			e.preventDefault();

			var id = $(this).attr('data-org-id');
			var params = {
				'theme-primary': $(this).attr('data-color') || $('#option_widget_color').val(),
				'keep-shopping': false
			};

			showpass.tickets.calendarWidget(id, params);
		});

        $('body').on('click', '.open-product-widget', function(e) {
            e.preventDefault();

			var id = $(this).attr('id');
            var params = {
                'theme-primary': $(this).attr('data-color') || $('#option_widget_color').val(),
                'keep-shopping': $(this).attr('data-shopping') || $('#option_keep_shopping').val(),
                'theme-dark': $(this).attr('data-theme') || $('#option_theme_dark').val(),
                'show-description': $(this).attr('data-show-description') || $('#option_show_widget_description').val()
            };

            if ($(this).attr('data-tracking')) {
                params['tracking-id'] = $(this).attr('data-tracking');
            }

            // Overwrite tracking-id if set in URL
            if (Cookies.get('affiliate')) {
                params['tracking-id'] = Cookies.get('affiliate');
            }

            showpass.tickets.productPurchaseWidget(id, params);
        });

        $('body').on('click', '#force-showpass-widget a[href*="showpass.com"]', function(e) {
            e.preventDefault();
            slug = $(this).attr('href').split('.com/')[1];

            var params = {
                'theme-primary': $('#option_widget_color').val(),
                'keep-shopping':$('#option_keep_shopping').val() || true,
                'theme-dark': $('#option_theme_dark').val(),
                'show-description': $('#option_show_widget_description').val() || 'false'
            };

            // Overwrite tracking-id if set in URL
            if (Cookies.get('affiliate')) {
                params['tracking-id'] = Cookies.get('affiliate');
            }

            showpass.tickets.eventPurchaseWidget(slug, params);
        });

        $('body').on('click', '.open-ticket-widget', function (e) {
            e.preventDefault();

            let slug = $(this).attr('id');

            const openWidget = () => {
				let params = {
					'theme-primary': $(this).attr('data-color') || $('#option_widget_color').val(),
					'keep-shopping': $(this).attr('data-shopping') || $('#option_keep_shopping').val() || true,
					'theme-dark': $(this).attr('data-theme') || $('#option_theme_dark').val(),
					'show-description': $(this).attr('data-show-description') || $('#option_show_widget_description').val() || 'false'
				};

				if ($(this).attr('data-tracking')) {
					params['tracking-id'] = $(this).attr('data-tracking');
				}

				if ($(this).attr('data-eyereturn')) {
					params['show-eyereturn'] = $(this).attr('data-eyereturn');
				}

				/**
				 * Add query parameters if distribution tracking is enabled
				 */
				if ($(this).attr('data-distribution-tracking')) {
					params['distribution-tracking'] = $(this).attr('data-distribution-tracking');
				}

				// Overwrite tracking-id if set in URL
				if (Cookies.get('affiliate')) {
					params['tracking-id'] = Cookies.get('affiliate');
				}

				showpass.tickets.eventPurchaseWidget(slug, params);
            }

            /**
             * Handle the redirect if distribution partner with an external link
             */
            if ($(this).attr('data-distribution') === 'true') {
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

        });

        $('.showpass-cart-button').on('click', function(e) {
            e.preventDefault();
            showpass.tickets.checkoutWidget({
                'theme-primary': $('#option_widget_color').val() || '',
                'keep-shopping': $('#option_keep_shopping').val() || 'true',
                'theme-dark': $('#option_theme_dark').val() || '',
                'show-description': $('#option_show_widget_description').val() || 'false'
            });
        });

        if ($(this).attr('data-eyereturn')) {
            params['show-eyereturn'] = $(this).attr('data-eyereturn');
        }

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
                var params = {
                    'theme-primary': $(this).attr('data-color') || $('#option_widget_color').val(),
                    'keep-shopping': $(this).attr('data-shopping') || $('#option_keep_shopping').val() || true,
                    'theme-dark': $(this).attr('data-theme') || $('#option_theme_dark').val(),
                    'show-description': $(this).attr('data-show-description') || $('#option_show_widget_description').val() || 'false'
                };

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

})(jQuery);
