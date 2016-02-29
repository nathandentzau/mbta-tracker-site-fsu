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

	/**
	* The contructor method. Why PHP calls this a "Magic Method" is beyond me.
	* Constructors are pretty standard in every other language................
	* Oh yeah, we're just initiating the SQLite database here and storing it in 
	* the $this->db variable.
	*
	* @link http://php.net/manual/en/book.pdo.php
	*/
	public function __construct()
	{
		$this->db = new \PDO("sqlite:" . ROOT_DIR . "test.db");
	}
}