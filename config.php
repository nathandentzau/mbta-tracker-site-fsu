<?php

/**
* mbTaNOW - Realtime MBTA Data
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @author Sherwyn Cooper <scooper4@student.framingham.edu>
* @copyright 2016 Framingham State University
* @license https://opensource.org/licenses/MIT MIT License
*/

define("DEBUG", true);

/* Directory Location Settings */
define("ROOT_DIR", "../");
define("APPLICATION_DIR", ROOT_DIR . "applications/");
define("CACHE_DIR", ROOT_DIR . "cache/");
define("PUBLIC_DIR", ROOT_DIR . "public/");
define("SYSTEM_DIR", ROOT_DIR . "system/");

/* MBTA Developer Settings */
define("MBTA_API_KEY", "wX9NwuHnZU2ToO7GmGR9uw");
define("MBTA_API_URL", "http://realtime.mbta.com/developer/api/");
define("MBTA_API_VERSION", "v2");