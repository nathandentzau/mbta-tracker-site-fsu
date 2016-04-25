<?php namespace application\Models;

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

use system\Model;

class OrangeLine extends Model 
{
	public function getPredictions(): array 
    {
        $predictions = $this->mbta->getSubwayPredictions("Orange");
        $data = [];

        /* Loop through the directions: [outbound, inbound] */
        for ($i = 0; $i < 2; $i++)
        {
            $data[$i] = [];

            /* Loop through the trips */
            for ($j = 0; $j < count($predictions[$i]->trip); $j++)
            {
                /* Loop through the stops */
                for ($k = 0; $k < count($predictions[$i]->trip[$j]->stop); $k++)
                {
                    /* Remove specfic station name and get the generic name */
                    $name = str_replace("Orange Line", "", trim(explode(" - ", $predictions[$i]->trip[$j]->stop[$k]->stop_name)[0]));
                    $time = @$predictions[$i]->trip[$j]->stop[$k]->pre_dt;

                    /* For whatever reason MBTA return N/A sometimes, just skip it */
                    if ($name === "N/A")
                    {
                        continue;
                    }

                    /* Check if the stop already has a time entered */
                    if (array_key_exists($name, $data[$i]))
                    {
                        /* If the stored stop time is great than the current predicted time then replace it */
                        if ($data[$i][$name] > $time)
                        {
                            $data[$i][$name] = $time;
                        }
                    }
                    else 
                    {
                        $data[$i][$name] = $time;
                    }
                }
            }
        }

        return $data;
    }

	public function getStops(): array 
    {
        $route = $this->mbta->getSubwayStops("Orange");

        $stops = [];

        for ($i = 0; $i < count($route[0]->stop); $i++)
        {
            $name = str_replace("Orange Line", "", trim(explode(" - ", $route[0]->stop[$i]->stop_name)[0]));

            $stops[] = [
                "name"        => $name,
                "inbound"    => 0,
                "outbound"    => 0,
            ];
        }

        return $stops;
    }
}