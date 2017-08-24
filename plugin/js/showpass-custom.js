$(document).ready(function() {
    $('.open-ticket-widget').on('click', function () {
        var slug = $(this).attr('id');
        var color = $(this).attr('data-color');
        var shopping = $(this).attr('data-shopping');
        var theme = $(this).attr('data-theme');
        console.log(theme);
        showpass.tickets.eventPurchaseWidget(slug, {'theme-primary': '#'+color, 'theme-dark': theme, 'keep-shopping': shopping});
    })
});
