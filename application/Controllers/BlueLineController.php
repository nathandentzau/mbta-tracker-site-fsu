<?php namespace application\Controllers;

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

use system\Controller;

class BlueLineController extends Controller 
{
	public function __construct()
	{
		$this->register([
			"blueLine"	=> "application\\Models\\BlueLine"
		]);
	}

	public function main(): array 
	{
		return $this->parseResults($this->blueLine->getStops(), $this->blueLine->getPredictions());
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