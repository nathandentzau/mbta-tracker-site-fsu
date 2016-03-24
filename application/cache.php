<?php 

/**
* mbTaNOW - Realtime MBTA Data
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @author Sherwyn Cooper <scooper4@student.framingham.edu>
* @copyright 2016 Framingham State University
* @license https://opensource.org/licenses/MIT MIT License
*/

date_default_timezone_set("America/New_York");

$hour = date("G");

/* Do not cache when the MBTA does not run */
if ($hour >= 3 && $hour <= 5) 
{
	exit;
}

define("ROOT_DIR", "/home/nathan/www/mbta/");
define("APPLICATION_DIR", ROOT_DIR . "applications/");
define("CACHE_DIR", ROOT_DIR . "cache/");
define("PUBLIC_DIR", ROOT_DIR . "public/");
define("SYSTEM_DIR", ROOT_DIR . "system/");

/* MBTA Developer Settings */
define("MBTA_API_KEY", "YcqP0PC7Zk64lr1HkBq3XQ");
define("MBTA_API_URL", "http://realtime.mbta.com/developer/api/");
define("MBTA_API_VERSION", "v2");
require SYSTEM_DIR . "FileHandler.php";
require SYSTEM_DIR . "MBTA.php";

$mbta = new system\MBTA;
// $mbta->cacheAll(); this sends all the request we need to store
 $mbta->cachePredictions();
?>


