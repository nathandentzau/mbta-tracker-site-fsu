<?php namespace application\Models;

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

use system\Model;

class GreenLine extends Model 
{
    public function getBLineStops(): array 
    {
        return $this->getStops("Green-B");
    }

    public function getCLineStops(): array 
    {
        return $this->getStops("Green-C");
    }

    public function getDLineStops(): array 
    {
        return $this->getStops("Green-D");
    }

    public function getELineStops(): array 
    {
        return $this->getStops("Green-E");
    }

    public function getStops(string $route): array 
    {
        $route = $this->mbta->getTrolleyStops($route);

        $stops = [];

        for ($i = 0; $i < count($route[1]->stop); $i++)
        {
            $name = trim(explode(" - ", $route[1]->stop[$i]->stop_name)[0]);

            $stops[] = [
                "name"        => $name,
                "inbound"    => 0,
                "outbound"    => 0,
            ];
        }

        return $stops;
    }
}