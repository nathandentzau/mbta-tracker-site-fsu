<?php namespace system;

/**
* mbTaNOW - Realtime MBTA Data
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @author Sherwyn Cooper <scooper4@student.framingham.edu>
* @copyright 2016 Framingham State University
* @license https://opensource.org/licenses/MIT MIT License
*/

abstract class Model 
{
	protected $mbta;

	public function __construct()
	{
		$this->mbta = new MBTA;
	}
}