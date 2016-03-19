<?php  


class test {
	function getPredictionsByRoute($route){
		
			// get the curl resource oject
		$ch = curl_init();
		/* Set Array Options :
		   		Set return transfer to true so it returns the json object 
			 	Set the Url of the request
		*/
		curl_setopt_array($ch,  array(
			CURLOPT_RETURNTRANSFER => 1, 
			CURLOPT_URL => 'http://realtime.mbta.com/developer/api/v2/predictionsbyroute?api_key=wX9NwuHnZU2ToO7GmGR9uw&route='.$route.'&format=json' ));
		// execute the request and store json object in variable result
		$result = curl_exec($ch);

		// close the resource
		curl_close($ch);

		return $result;
	}
	function getStopsByRoute($route){
		
		// get the curl resource oject
		$ch = curl_init();
		/* Set Array Options :
		   		Set return transfer to true so it returns the json object 
			 	Set the Url of the request
		*/
		curl_setopt_array($ch,  array(
			CURLOPT_RETURNTRANSFER => 1, 
			CURLOPT_URL => 'http://realtime.mbta.com/developer/api/v2/stopsbyroute?api_key=wX9NwuHnZU2ToO7GmGR9uw&route='.$route.'&format=json' ));
		// execute the request and store json object in variable result
		$result = curl_exec($ch);
		// close the resource
		curl_close($ch);
		return $result;
	}

	function getStops($resultStops){
		$stops = [];

		foreach ($resultStops->direction[0]->stop as $stop ) {
			$stops[$stop->stop_name] = null;
		}
		return $stops;
	}

	function getAllPredictions($result,$stops,$date_format,$direction){

		//Loop through data find all outbound trains and print out what stops they arrive
	foreach ($result->direction[$direction]->trip as $trip) {
		foreach ($trip->stop as $stop) {

			if ($stops[$stop->stop_name] == null ||  $stops[$stop->stop_name] <= $stop->sch_arr_dt){
				$stops[$stop->stop_name] = date($date_format,$stop->sch_arr_dt);
			}
		}
		var_dump($stops);
		return $stops;
		// echo "------------------------------------------\n---------------------------\n-=--------------------\n";
	}
	}

}