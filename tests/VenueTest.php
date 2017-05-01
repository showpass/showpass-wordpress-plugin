<?php


/**
* 
*/
class VenueTest extends PHPUnit\Framework\TestCase
{
	
	public function testGetVenueName(){

		$this->assertEquals('Cowboys Dance Hall', \Showpass\Venue::getVenueName('5'));

	}

	public function testGetVenueNumberOfPages(){

		$this->assertEquals('2', \Showpass\Venue::getVenueNumberOfPages('5'));

	}

}

?>
