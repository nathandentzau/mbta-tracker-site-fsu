<?php 

/**
* mbTaNOW - Realtime MBTA Data
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @author Sherwyn Cooper <scooper4@student.framingham.edu>
* @copyright 2016 Framingham State University
* @license https://opensource.org/licenses/MIT MIT License
*/

require "../config.php";
require SYSTEM_DIR . "FileHandler.php";
require SYSTEM_DIR . "MBTA.php";

$mbta = new system\MBTA;
$mbta->cacheRoutes();
print_r($mbta->getTrolleyRoutes());

?>