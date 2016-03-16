<?php namespace application\Controllers;

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

use system\Controller;

class SilverLineController extends Controller 
{
	public function __construct()
	{
		$this->register([
			"silverLine"	=> "application\\Models\\SilverLine"
		]);
	}

	public function main(): array
    {
        $line = strtolower($this->request("line"));

        return method_exists($this, $line) ? $this->$line() : [];
    }

	public function sl1()
	{
		return $this->parseResults($this->silverLine->getSL1LineStops(), $this->silverLine->getSL1LinePredictions());
	}

	public function sl2()
	{
		return $this->parseResults($this->silverLine->getSL2LineStops(), $this->silverLine->getSL2LinePredictions());
	}

	public function sl4()
	{
		return $this->parseResults($this->silverLine->getSL4LineStops(), $this->silverLine->getSL4LinePredictions());
	}

	public function sl5()
	{
		return $this->parseResults($this->silverLine->getSL5LineStops(), $this->silverLine->getSL5LinePredictions());
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