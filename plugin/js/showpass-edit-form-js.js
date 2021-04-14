(function ($, window, document) {
	$('span#submit-event-url').on('click', () => {

		let url = $('input#showpass_url_field').val();

		const checkValidURL = (url) => {
			if (url) {
				$('span#error').html('');
				$('p#showpass-url').html('');
				$('#showpass-get-event-url .loader').addClass('spin');
				$.ajax({
					url: wpApiSettings.root + 'showpass/v1/process-url/?url=' + encodeURI(url),
					method: 'GET',
					contentType: 'application/json',
					beforeSend: function ( xhr ) {
						xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
					},
					success: function (success) {
						$('#showpass-get-event-url .loader').removeClass('spin');
						console.log(success);
						$('span#success').html('Success!')
						$('p#showpass-url').html('https://www.showpass.com/'+success.data)
					},
					error: function (error) {
						console.log(error);
						$('#showpass-get-event-url .loader').removeClass('spin');
						if (error && error.responseText) {
							let msg = JSON.parse(error.responseText);
							$('span#error').html(msg.data)
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
