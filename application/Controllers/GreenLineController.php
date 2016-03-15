<?php namespace application\Controllers;

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

use system\Controller;

class GreenLineController extends Controller 
{
    public function __construct()
    {
        $this->register([
            "greenLine"    => "application\\Models\\GreenLine"
        ]);
    }

    public function main(): array
    {
        return $this->b();
    }

    public function b(): array
    {
        return $this->parseResults($this->greenLine->getBLineStops(), $this->greenLine->getBLinePredictions());
    }

    public function c(): array 
    {
        return $this->parseResults($this->greenLine->getCLineStops(), $this->greenLine->getCLinePredictions());
    }

    public function d(): array 
    {
        return $this->parseResults($this->greenLine->getDLineStops(), $this->greenLine->getDLinePredictions());
    }

    public function e(): array 
    {
        return $this->parseResults($this->greenLine->getELineStops(), $this->greenLine->getELinePredictions());
    }

    private function parseResults($stops, $predictions): array 
    {
        for ($i = 0; $i < count($stops); $i++)
        {
            $stops[$i]["inbound"] = (int) @$predictions[1][$stops[$i]["name"]];
            $stops[$i]["outbound"] = (int) @$predictions[0][$stops[$i]["name"]];
        }

        return $stops;
    }
}