<?php 
	

	echo "Test for Event Date \n\n\n";
	/**
	* 
	*/

   /*********************
   *  Expected results  *
   *********************/

   /********************************************************************************************************
   *           Server Date             |              Timezone             |       Date in timezone        |  
   *-------------------------------------------------------------------------------------------------------|  
   *       2015-02-28T04:00:00Z        |             US/Mountain           |    Friday February 27, 2015   |
   *       2015-03-24T04:00:00Z        |             US/Mountain           |      Monday March 23, 2015    |
   ********************************************************************************************************/                  

	$server_date = '2015-03-24T04:00:00Z';
	$timezone = 'US/Mountain';

	function testGetEventDate($date, $timezone){


		$real_timezone_date = showpass_get_event_date($date, $timezone); 

		echo "Server date: " . $date . "\n"; // expected [EVOL INTENT + JFB , ]
		echo "Event Timezone: " . $timezone . "\n";      // expected [123]
		echo "Timezone date formated: " . $real_timezone_date . "\n";  // expected [evol-intent-jfb]


	}


	function showpass_get_event_date($date, $zone){

		$datetime = new Datetime($date); // current time = server time
		$otherTZ  = new DateTimeZone($zone);
		$datetime->setTimezone($otherTZ);

		$format_date = "l F d, Y";

		$new_date = $datetime->format($format_date);

		return $new_date;
	}

	testGetEventDate($server_date, $timezone);


?>