<?php


/**
* 
*/
class EventTest extends PHPUnit_Framework_TestCase
{
	
	public function testGetEventName(){

		$this->assertEquals('EVOL INTENT + JFB', \Showpass\Event::getEventName('123'));

	}

	public function testGetEventSlug(){

		$this->assertEquals('evol-intent-jfb', \Showpass\Event::getEventSlug('123'));

	}

}

?>
