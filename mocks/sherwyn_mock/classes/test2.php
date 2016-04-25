<?php
	require '../autoload.php';
	//Set Time Zone to New York because MBTA is in this Time Zone :)
	date_default_timezone_set("America/New_York");
	$date_format = "g:i a";		//format for displaying time hour:minutes am/pm
	$test = new test;// create a test object
	
	$result = json_decode($test->getPredictionsByRoute("CR-Providence"));//Decode result string to json array format 
	$resultStops = json_decode($test->getStopsByRoute("CR-Providence"));//Decode resultStops string to json array format 
	$stops = $test->getStops($resultStops); //return all stops in an AssociativeArray
	$stopsOutbound = $test->getAllPredictions($result,$stops,$date_format,0); //retrun All Outbound stops with earliest predicted time in Array
	$stopsInbound = $test->getAllPredictions($result,$stops,$date_format,1); //retrun All Inbound stops with earliest predicted time in Array
    
    // var_dump($resultStops);
	// if (array_key_exists("error", $result)){	//if the key error exist in object $result
	// 	$result = file_get_contents("sample-data.txt"); // If its really late at night and no data :)
	// 	echo "Hi Sherwyn there was an error";
	// }
	
	class test2 {
		


	}


?>


