<?php

/**
* 
*/

namespace Showpass;
use \Datetime;
use \DateTimeZone;


class TimeFormat
{
	
	public static function time_format($date, $zone){

		$datetime = new Datetime($date); // current time = server time
		$otherTZ  = new DateTimeZone($zone);
		$datetime->setTimezone($otherTZ);

		$format_time = "g:iA";

		$new_time = $datetime->format($format_time);

		return $new_time;
	}

}

?>