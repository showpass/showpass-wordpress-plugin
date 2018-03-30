(function($) {
    $(window).on('load', function() {
        showpass.tickets.addCartCountListener(function (count) {
            var html = '';
            if (count > 0) {
                html = 'Shopping Cart ('+count+')';
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
          var assoc  = {};
          var decode = function (s) { return decodeURIComponent(s.replace(/\+/g, " ")); };
          var queryString = location.search.substring(1);
          var keyValues = queryString.split('&');

          for(var i in keyValues) {
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
          Cookies.set('affiliate', qs.aff, { expires: 7 });
        }

        // SET AUTO OPEN COOKIE
        if (!$.isEmptyObject(qs) && qs.auto) {
          Cookies.set('auto', qs.auto, { expires: 7 });
        }

    });

    $(document).ready(function() {
        function getQueryStrings() {
          var assoc  = {};
          var decode = function (s) { return decodeURIComponent(s.replace(/\+/g, " ")); };
          var queryString = location.search.substring(1);
          var keyValues = queryString.split('&');

          for(var i in keyValues) {
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
                  'theme-dark': $('#option_theme_dark').val() || ''
              };
              setTimeout(function(){ Cookies.remove('auto');
                                    showpass.tickets.eventPurchaseWidget(slug, params);
                                  }, 500);
        }

        $('body').on('click', '.open-ticket-widget', function (e) {
            e.preventDefault();
            var slug = $(this).attr('id');
            var params = {
                'theme-primary': $(this).attr('data-color') || $('#option_widget_color').val(),
                'keep-shopping': $(this).attr('data-shopping') || $('#option_keep_shopping').val(),
                'theme-dark': $(this).attr('data-theme') || $('#option_theme_dark').val()
            };

            if (Cookies.get('affiliate')) {
                params['tracking-id'] = Cookies.get('affiliate');
            }

            showpass.tickets.eventPurchaseWidget(slug, params);
        });

        $('.showpass-cart-button').on('click', function(e){
          e.preventDefault();
          showpass.tickets.checkoutWidget({
              'theme-primary': $('#option_widget_color').val() || '',
              'keep-shopping': $('#option_keep_shopping').val() || 'true',
              'theme-dark': $('#option_theme_dark').val() || ''
          });
        });

        if (Cookies.get('cart')) {
          $('.showpass-cart-button span').html(Cookies.get('cart'));
        }

        function css(element, property) {
          return window.getComputedStyle(element, null).getPropertyValue(property);
        }

        //Check to see if font awesome is available
        var span = document.createElement('span');
        span.className = 'fa';
        span.style.display = 'none';
        document.body.insertBefore(span, document.body.firstChild);
        if ((css(span, 'font-family')) !== 'FontAwesome') {
            $('.fa').hide();
        }
        document.body.removeChild(span);

    });

})(jQuery);
