# Tests

## Steps

* At first you need to have composer installed on your OS. Here is the <a href="https://getcomposer.org/download/">Link</a>

* Next you need to initialize composer `composer init`.

* Next you need to install composer `composer install`.

* Next step is download and put `src` and `tests` folders in `root` folder.

* After that, you need to put this in `composer.json` 

     	"autoload":{      
			"psr-4":{        
				"Showpass\\" : "src/"       
   			}       
   		},
     
With this you tell the phpunit to use `src` folder for the main classes with `namespace Showpass`.

* After this, you need to make `composer dump-autoload`.

* And the phpunit is ready. All you need is to write this command `./vendor/bin/phpunit ./tests` .

This will start `phpunit` and will take all tests in `tests` folder.

The default PHPUnit suite excludes the legacy Event and Venue tests because they
call the live Showpass API. Calendar shortcode, date, time and timezone tests run
offline.

## Calendar and card-image regressions

Run `npm run test:calendar` with Node.js to check typed options and the actual
modal/embedded SDK initialization calls. Run `npm run test:card-images` with PHP
and its DOM extension to render the card templates and check their widget
bindings, destinations, tracking, language, and membership keyboard markup.
These focused checks do not require npm or Composer dependencies. CircleCI runs
the calendar tests in the Node job and the rendered-template tests in the PHP job.

## Public parameter aliases

Run `npm run test:parameter-aliases` to render modern shortcodes with legacy and
canonical attributes. It checks WordPress attribute lowercasing, canonical
precedence, explicit false, shared theme/options and preserved locale markup.
`npm run test:calendar` also executes the real modal and embedded JavaScript
handlers and checks canonical SDK payloads, including shared options and redirects.

Release order: deploy the matching SPW-20291 frontend and rebuilt SDK before
publishing plugin 4.1.0. Keep old SDK names as aliases so plugin 4.0.9 and existing
integrations continue working. Local browser review must precede PR creation;
formal QA, merge, SDK deployment, SVN submission and release confirmation are
separate checkpoints.

## Editor block regressions

Run `npm run test:editor` against the shipped editor bundle and
`npm run test:editor-api` against the offline URL/shortcode contract. These check
empty-input handling, URL and label encoding, saved shortcode extraction, local
URL validation, default labels and escaped attributes. No HTTP requests are made.
The rendered-card checks also cover membership responses that keep currency on
the venue and omit `num_pages`.


## WordPress shortcode rendering

On a local WordPress test installation, run the following against this checkout
using WP-CLI (adjust the WordPress path):

```sh
wp --path=/absolute/path/to/local-wordpress --skip-plugins --skip-themes eval-file /absolute/path/to/showpass-wordpress-plugin/tests/editor-wordpress.test.php
```

This integration test uses WordPress's actual shortcode parser and HTML escaping
to verify rendered button labels with square brackets, quotes, ampersands and
HTML-like text. It checks for one button with no extra page text or injected
markup. It blocks HTTP requests and does not write database options or posts.
The offline editor suite also guards raw shortcode extraction and repeated
save/reopen cycles. The WP-CLI integration check is separate from default CI.
