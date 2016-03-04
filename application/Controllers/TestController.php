<?php namespace application\Controllers;

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

use \system\Controller;

class TestController extends Controller
{
	public function __construct()
	{
		$this->register(["test"	=>	"\\application\\Models\\Test"]);
	}

	public function main(): string
	{
		return sprintf("<pre>By default we are using the '%s' method of the '%s' class in the '%s' namespace.\n%s\n%s</pre>", __FUNCTION__, __CLASS__, __NAMESPACE__, print_r($this->test->getAllDays(), true), $this->test);
	}

	public function test($poop): array
	{
		return array_merge(["message" => $poop], ["days" => $this->test->getAllDays()]);
	} 

	public function viewTest(): string
	{
		return $this->view->output("testPage.html");
	}
}