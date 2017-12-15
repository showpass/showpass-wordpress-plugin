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

    });

    $(document).ready(function() {
        $('.open-ticket-widget').on('click', function () {
            var slug = $(this).attr('id');

            var params = {
                'theme-primary': $(this).attr('data-color'),
                'keep-shopping': $(this).attr('data-shopping'),
                'theme-dark': $(this).attr('data-theme')
            };

            if (Cookies.get('affiliate')) {
                params['tracking-id'] = Cookies.get('affiliate');
            }

            showpass.tickets.eventPurchaseWidget(slug, params);
        })
    });

})(jQuery);
