<?php


/**
* 
*/
class Date_formatTest extends PHPUnit_Framework_TestCase
{
	
	public function testDate_format(){

		$this->assertEquals('Friday May 05, 2017', \Showpass\DateFormat::date_format('2017-05-06T02:00:00Z', 'US/Mountain'));

	}

	public function testDate_formatSecond(){

		$this->assertEquals('Thursday April 27, 2017', \Showpass\DateFormat::date_format('2017-04-27T15:52:10.223Z', 'US/Mountain'));

	}

}

?>
