(function(window, document, src) {
    var config = window.__shwps;
    if (typeof config === "undefined") {
        config = function() {
            config.c(arguments)
        };
        config.q = [];
        config.c = function(args) {
            config.q.push(args)
        };
        window.__shwps = config;

        var s = document.createElement('script');
        s.type = 'text/javascript';
        s.async = true;
        s.src = src;
        var x = document.getElementsByTagName('script')[0];
        x.parentNode.insertBefore(s, x);
    }
})(window, document, 'https://beta.showpass.com/static/dist/sdk.js');
