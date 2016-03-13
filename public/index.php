<?php

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

/* Include System Configuration */
require "../config.php";

/**
* Register given function as __autoload() implementation
*
* @link http://php.net/manual/en/function.spl-autoload-register.php
*/
spl_autoload_register(function($class) {
    $class_location = ROOT_DIR . str_replace("\\", "/", $class) . ".php";

    if (file_exists($class_location)) {
        require $class_location;
    }
});

/* Initiate the Application class and then run it */
//$app = new system\Application();
//$app->run();

$mbta = new system\MBTA();

foreach ($mbta->getAllRoutes() as $type => $routes)
{
	echo "<h1>{$type}</h1>\n";

	for ($i = 0; $i < count($routes); $i++)
	{
		echo "<h3>{$routes[$i]["name"]}</h3>\n";

		$predictions = @$mbta->getPredictions($type, $routes[$i]["id"])->direction;

		foreach ($mbta->getStops($type, $routes[$i]["id"]) as $id => $direction)
		{
			echo "<h4>{$direction->direction_name}</h4>\n";

			//print_r($predictions->direction[$id]->trip);

			echo "<ul>\n";
			for ($j = 0; $j < count($direction->stop); $j++)
			{
				$preditionTimes = [];

				if (!$predictions[$id])
				{
					continue;
				}

				for ($k = 0; $k < count($predictions[$id]->trip); $k++)
				{
					for ($l = 0; $l < count($predictions[$id]->trip[$k]->stop); $l++)
					{
						if ($predictions[$id]->trip[$k]->stop[$l]->stop_id === $direction->stop[$j]->stop_id)
						{
							$preditionTimes[] = date("g:i A", $predictions[$id]->trip[$k]->stop[$l]->pre_dt);
						}
					}
				}

				echo "<li>{$direction->stop[$j]->stop_name} - " . implode(", ", $preditionTimes) . "</li>\n";
			}
			echo "</ul>\n";
		}
	}
}

?>