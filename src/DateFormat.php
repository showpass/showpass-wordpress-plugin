<?php

/**
* 
*/

namespace Showpass;
use \Datetime;
use \DateTimeZone;


class DateFormat
{
	
	public static function date_format($date, $zone){

		$datetime = new Datetime($date); // current time = server time
		$otherTZ  = new DateTimeZone($zone);
		$datetime->setTimezone($otherTZ);

		$format_date = "l F d, Y";

		$new_date = $datetime->format($format_date);

		return $new_date;
	}

}

?>