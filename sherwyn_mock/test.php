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
	CURLOPT_URL => 'http://realtime.mbta.com/developer/api/v2/schedulebyroute?api_key=YcqP0PC7Zk64lr1HkBq3XQ&route=green-b&format=json' ));
// execute the request and store json object in variable result
$result = curl_exec($ch);
//Decode result string to json array format and error check in if block
$result = json_decode($result);
if (array_key_exists("error", $result)){	//if the key error exist in object $result
	$result = file_get_contents("sample-data.txt"); // If its really late at night and no data :)
	$result = json_decode($result);		//decode from string to json object
	echo "Hi Sherwyn there was an error";
}

// echo $result->direction[0];
//Loop through data find all outbound trains and print out what stops they arrive
foreach ($result->direction[0]->trip as $trip) {
	echo "this is the Outbound ", $trip->trip_id, "trip ", " and the train id is ", $trip->vehicle->vehicle_id , "\n";
	foreach ($trip->stop as $stop) {
		// if ($stop->stop_id == "Providence"){
			echo $trip->vehicle->vehicle_id, " will arrive at stop ", $stop->stop_name ," at ", date($date_format,$stop->sch_arr_dt) , "\n";
		// }
	}
	// var_dump($trip);
	// echo "------------------------------------------\n---------------------------\n-=--------------------\n";
}
//Loop through data find all Inbound trains and print out what stops they arrive
foreach ($result->direction[1]->trip as $trip) {
	echo "this is the Inbound", $trip->trip_id, "trip ", " and the train id is ", $trip->vehicle->vehicle_id , "\n";
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