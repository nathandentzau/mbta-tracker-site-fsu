<?php namespace application\Controllers;

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

use system\Controller;

class RedLineController extends Controller 
{
    public function __construct()
    {
        $this->register([
            "redLine"    => "application\\Models\\RedLine"
        ]);
    }

    public function main(): array 
    {
        $line = strtolower($this->request("line"));

        return method_exists($this, $line) ? $this->$line() : [];
    }

    public function ashmont(): array 
    {
        return $this->parseResults($this->redLine->getAshmontLineStops(), $this->redLine->getPredictions());
    }

    public function braintree(): array 
    {
        return $this->parseResults($this->redLine->getBraintreeLineStops(), $this->redLine->getPredictions());
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