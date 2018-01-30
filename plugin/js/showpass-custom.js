(function($) {

    $(window).on('load', function() {

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


        if (Cookies.get('auto') != qs.auto && qs.auto) {
            Cookies.set('auto', qs.auto, {expires: 2});
        }

        if (Cookies.get('auto')) {
            var slug = Cookies.get('auto');
            var params = {
                'theme-primary': $('#option_widget_color').val() || '',
                'keep-shopping': $('#option_keep_shopping').val() || 'true',
                'theme-dark': $('#option_theme_dark').val() || ''
            };
            setTimeout(function(){ Cookies.remove('auto');
                                  showpass.tickets.eventPurchaseWidget(slug, params);
                                }, 500);
        }

        $('.open-ticket-widget').on('click', function () {
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
        })
    });

})(jQuery);
