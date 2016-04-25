<?php namespace application\Controllers;

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

use system\Controller;

class CommuterRailController extends Controller 
{
    public function __construct()
    {
        $this->register([
            "commuterRail"    => "application\\Models\\CommuterRail"
        ]);
    }

    public function main()
    {

        return $this->b();
        // $line = strtolower($this->request("line"));

        // return method_exists($this, $line) ? $this->$line() : [];
    }

    public function b(): array
    {
        return $this->parseResults($this->commuterRail->getStops("CR-Worcester"), $this->commuterRail->getPredictions("CR-Worcester"));
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