<?php


/**
* 
*/
class Time_formatTest extends PHPUnit\Framework\TestCase
{
	
	public function testTime_format(){

		$this->assertEquals('8:00PM', \Showpass\TimeFormat::time_format('2017-05-06T02:00:00Z', 'US/Mountain'));

	}

	public function testTime_formatSecond(){

		$this->assertEquals('9:52AM', \Showpass\TimeFormat::time_format('2017-04-27T15:52:10.223Z', 'US/Mountain'));

	}

}

?>
