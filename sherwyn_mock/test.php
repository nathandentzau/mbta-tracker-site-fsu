<?php  
//Set Time Zone to New York because MBTA is in this Time Zone :)
date_default_timezone_set("America/New_York");
$date_format = "g:i a";		//format for displaying time hour:minutes am/pm
// get the curl resource oject
$ch = curl_init();
/* Set Array Options :
   		Set return transfer to true so it returns the json object 
	 	Set the Url of the request
*/
curl_setopt_array($ch,  array(
	CURLOPT_RETURNTRANSFER => 1, 
	CURLOPT_URL => 'http://realtime.mbta.com/developer/api/v2/predictionsbyroute?api_key=wX9NwuHnZU2ToO7GmGR9uw&route=CR-Providence&format=json' ));
// execute the request and store json object in variable result
$result = curl_exec($ch);
$result = json_decode($result);
// var_dump($result);

//Loop through data find all outbound trains and print out what stops they arrive
foreach ($result->direction[0]->trip as $trip) {
	echo "this is the ", $trip->trip_id, "trip ", " and the train id is ", $trip->vehicle->vehicle_id , "\n";
	foreach ($trip->stop as $stop) {
		// if ($stop->stop_id == "Providence"){
			echo $trip->vehicle->vehicle_id, " will arrive at stop ", $stop->stop_name ," at ", date($date_format,$stop->sch_arr_dt) , "\n";
		// }
	}
	// var_dump($trip);
	// echo "------------------------------------------\n---------------------------\n-=--------------------\n";
}
// close the resource
curl_close($ch);

?>