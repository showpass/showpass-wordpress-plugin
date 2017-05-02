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