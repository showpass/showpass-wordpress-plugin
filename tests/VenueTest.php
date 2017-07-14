<?php


/**
* 
*/
class VenueTest extends PHPUnit\Framework\TestCase
{
	
	public function testGetVenueName(){

		$this->assertEquals('Cowboys Dance Hall', \Showpass\Venue::getVenueName('5'));

	}

	public function testGetVenueStreetName(){

		$this->assertEquals('9615 Macleod Trail SW', \Showpass\Venue::testGetVenueStreetName('2'));

	}

}

?>
