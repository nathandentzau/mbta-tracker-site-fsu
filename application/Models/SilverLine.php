<?php namespace application\Models;

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

use system\Model;

class SilverLine extends Model 
{
	public function getSL1LineStops(): array 
	{
		return $this->getStops("741");
	}

	public function getSL1LinePredictions(): array 
	{
		return $this->getPredictions("741");
	}

	public function getSL2LineStops(): array 
	{
		return $this->getStops("742");
	}

	public function getSL2LinePredictions(): array 
	{
		return $this->getPredictions("742");
	}

	public function getSL4LineStops(): array 
	{
		return $this->getStops("751");
	}

	public function getSL4LinePredictions(): array 
	{
		return $this->getPredictions("751");
	}

	public function getSL5LineStops(): array 
	{
		return $this->getStops("749");
	}

	public function getSL5LinePredictions(): array 
	{
		return $this->getPredictions("749");
	}

    public function getPredictions(string $route): array 
    {
        $predictions = $this->mbta->getBusPredictions($route);
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
                    $name = trim(str_replace("after Manulife Building", "", str_replace("before Manulife Building", "", str_replace("South Station Silver Line", "South Station", explode(" - ", $predictions[$i]->trip[$j]->stop[$k]->stop_name)[0]))));
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

    public function getStops(string $route): array 
    {
        $route = $this->mbta->getBusStops($route);

        $stops = [];

        for ($i = 0; $i < count($route[1]->stop); $i++)
        {
            $name = trim(str_replace("after Manulife Building", "", str_replace("before Manulife Building", "", str_replace("South Station Silver Line", "South Station", explode(" - ", $route[1]->stop[$i]->stop_name)[0]))));

            $stops[] = [
                "name"        => $name,
                "inbound"    => 0,
                "outbound"    => 0,
            ];
        }

        return $stops;
    }
}