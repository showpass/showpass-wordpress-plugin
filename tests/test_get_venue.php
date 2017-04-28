<?php 
	

	echo "Test for Get Venue \n\n\n";
	/**
	* 
	*/

   /*********************
   *  Expected results  *
   *********************/

   /********************************************************************************************************
   *    venue parameter    |               name              |   No. events one page  |     No. pages      | 
   *-------------------------------------------------------------------------------------------------------|  
   *           5           |         Cowboys Dance Hall      |            20          |         2          |
   *      	   4           |          Knoxville's YYC        |             1          |         1          |
   *********************************************************************************************************/                  

	$venue_parameter = '4';

	function testGetVenue($venue_id){

		$url = 'https://www.myshowpass.com/api/public/events/?venue=';

		$data = CallAPI($url . $venue_id); 

		$venue = json_decode($data);

		echo "Venue name: " . $venue->results[0]->venue->name . "\n"; 
		echo "Number of events on one page: " . count($venue->results) . "\n";     
		echo "Number of pages: " . $venue->num_pages. "\n";  


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

	testGetVenue($venue_parameter);


?>