<?php 
	

	echo "Test for Event Time \n\n\n";
	/**
	* 
	*/

   /*********************
   *  Expected results  *
   *********************/

   /********************************************************************************************************
   *           Server Date             |              Timezone             |       Time in timezone        |  
   *-------------------------------------------------------------------------------------------------------|  
   *       2015-02-28T04:00:00Z        |             US/Mountain           |            9:00PM             |
   *       2015-03-24T04:00:00Z        |             US/Mountain           |           10:00PM             |
   ********************************************************************************************************/                  

	$server_date = '2015-03-24T04:00:00Z';
	$timezone = 'US/Mountain';

	function testGetEventTime($date, $timezone){


		$real_timezone_date = showpass_get_event_time($date, $timezone); 

		echo "Server date: " . $date . "\n"; 
		echo "Event Timezone: " . $timezone . "\n";    
		echo "Timezone time formated: " . $real_timezone_date . "\n"; 


	}


	function showpass_get_event_time($date, $zone){

		$datetime = new Datetime($date); // current time = server time
		$otherTZ  = new DateTimeZone($zone);
		$datetime->setTimezone($otherTZ);

		$format_date = "g:iA";

		$new_date = $datetime->format($format_date);

		return $new_date;
	}

	testGetEventTime($server_date, $timezone);


?>