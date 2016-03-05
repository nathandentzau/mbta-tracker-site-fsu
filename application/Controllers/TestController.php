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
	public function main(): string
	{
		return sprintf("<pre>By default we are using the '%s' method of the '%s' class in the '%s' namespace.\n%s\n%s</pre>", __FUNCTION__, __CLASS__, __NAMESPACE__, "", "");
	}
}