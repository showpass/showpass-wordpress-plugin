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
publishing plugin 4.0.10. Keep old SDK names as aliases so plugin 4.0.9 and existing
integrations continue working. Local browser review must precede PR creation;
formal QA, merge, SDK deployment, SVN submission and release confirmation are
separate checkpoints.
