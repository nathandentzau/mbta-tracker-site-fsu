<?php namespace system;

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

abstract class Model 
{
	protected $db;

	public function __construct()
	{
		$this->db = new \PDO("sqlite:/home/nathan/www/sandbox/test.db");
	}
}