<?php


/**
* 
*/
class TimezoneTest extends PHPUnit_Framework_TestCase
{
	
	public function testTimezone(){

		$this->assertContains(\Showpass\Timezone::getTimezone('US/Mountain'), ['MDT', 'MST']);

	}

	public function testTimezoneLA(){

		$this->assertContains(\Showpass\Timezone::getTimezone('America/Los_Angeles'), ['PDT', 'PST']);

	}

}

?>
