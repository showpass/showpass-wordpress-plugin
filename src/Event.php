<?php

/**
* 
*/

namespace Showpass;



class Event
{
	
	public static function getEventName($event_id){

		$url = 'https://www.showpass.com/api/public/events/';

		$curl = curl_init();

	    curl_setopt($curl, CURLOPT_URL, $url . $event_id . "/");
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	    $result = curl_exec($curl);

	    curl_close($curl);

		$data = $result; 

		$event = json_decode($data);

		return $event->name;

	}

	public static function getEventSlug($event_id){

		$url = 'https://www.showpass.com/api/public/events/';

		$curl = curl_init();

	    curl_setopt($curl, CURLOPT_URL, $url . $event_id . "/");
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	    $result = curl_exec($curl);

	    curl_close($curl);

		$data = $result; 

		$event = json_decode($data);

		return $event->slug;

	}

}

?>