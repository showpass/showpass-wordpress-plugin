<?php


/**
* 
*/
class TimezoneTest extends PHPUnit\Framework\TestCase
{
	
	public function testTimezone(){

		$this->assertEquals('MDT', \Showpass\Timezone::getTimezone('US/Mountain'));

	}

	public function testTimezoneLA(){

		$this->assertEquals('PDT', \Showpass\Timezone::getTimezone('America/Los_Angeles'));

	}

}

?>
