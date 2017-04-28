<?php 
	

	echo "Test for Get Event \n\n\n";
	/**
	* 
	*/

   /*********************
   *  Expected results  *
   *********************/

   /********************************************************************************************************
   *        event parameter      |               name              |   id  |             slug              | 
   *-------------------------------------------------------------------------------------------------------|  
   *              123            |         EVOL INTENT + JFB       |  123  |        evol-intent-jfb        |
   *          sound-remedy       |           SOUND REMEDY          |   41  |         sound-remedy          |
   *   stampeders-game-packages  |     Stampeders Game Packages    |   5   |    stampeders-game-packages   |
   *********************************************************************************************************/                  

	$event_parameter = 'stampeders-game-packages';

	function testGetEvent($event_id){

		$url = 'https://www.myshowpass.com/api/public/events/';

		$data = CallAPI($url . $event_id . '/'); 

		$event = json_decode($data);


		echo "Event name: " . $event->name . "\n";
		echo "Event id: " . $event->id. "\n";     
		echo "Event slug: " . $event->slug. "\n";  


	}


	function CallAPI($url, $method = "GET", $data = false)
	{
	    $curl = curl_init();

	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	    $result = curl_exec($curl);

	    curl_close($curl);

	    return $result;
	}

	testGetEvent($event_parameter);


?>