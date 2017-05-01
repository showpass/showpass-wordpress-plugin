<?php

/**
* 
*/

namespace Showpass;
use \Datetime;
use \DateTimeZone;


class Timezone
{
	
	public static function getTimezone($timezone){

		date_default_timezone_set($timezone);
	
		$new_date = date('T');

		return $new_date;
	}

}

?>