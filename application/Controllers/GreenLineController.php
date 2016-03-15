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
        return $this->greenLine->getBLineStops();
    }

    public function c(): array 
    {
        return $this->greenLine->getCLineStops();
    }

    public function d(): array 
    {
        return $this->greenLine->getDLineStops();
    }

    public function e(): array 
    {
        return $this->greenLine->getELineStops();
    }
}