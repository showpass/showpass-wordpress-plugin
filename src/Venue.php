<?php

/**
* 
*/

namespace Showpass;



class Venue
{
	
	public static function getVenueName($venue_id){

		$url = 'https://www.showpass.com/api/public/events/?venue=';

		$curl = curl_init();

	    curl_setopt($curl, CURLOPT_URL, $url . $venue_id);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	    $result = curl_exec($curl);

	    curl_close($curl);

		$data = $result; 

		$venue = json_decode($data);

		return $venue->results[0]->venue->name;

	}

	public static function testGetVenueStreetName($venue_id){

		$url = 'https://www.showpass.com/api/public/events/?venue=';

		$curl = curl_init();

	    curl_setopt($curl, CURLOPT_URL, $url . $venue_id);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	    $result = curl_exec($curl);

	    curl_close($curl);

		$data = $result; 

		$venue = json_decode($data);

		return $venue->results[0]->venue->street_name;
	}


}

?>