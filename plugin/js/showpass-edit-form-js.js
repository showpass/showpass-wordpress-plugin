(function ($, window, document) {
	$('span#submit-event-url').on('click', () => {

		let url = $('input#showpass_url_field').val();

		const checkValidURL = (url) => {
			if (url) {
				$('span#error').html('');
				$('p.response').hide();
				$('#showpass-get-event-url .loader').addClass('spin');
				$.ajax({
					url: wpApiSettings.root + 'showpass/v1/process-url/?url=' + encodeURI(url),
					method: 'GET',
					contentType: 'application/json',
					beforeSend: function ( xhr ) {
						xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
					},
					success: function (success) {
						$('p.response').show();
						$('#showpass-get-event-url .loader').removeClass('spin');
						$('span#success').html('Success!')
						$('span#showpass-url').html('https://www.showpass.com/' + success.data.slug);
						$('span#showpass-shortcode').html(success.data.shortcode);
					},
					error: function (error) {
						console.log(error);
						$('#showpass-get-event-url .loader').removeClass('spin');
						if (error && error.responseText) {
							let msg = JSON.parse(error.responseText);
							$('span#error').html(msg.data);
						} else {
							$('span#error').html('Unknown Error: Please try again.');
						}

					}
				});
			}
		}

		checkValidURL(url);
	})
})(jQuery, window, document);
