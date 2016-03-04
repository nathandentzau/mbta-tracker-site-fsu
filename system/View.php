<?php namespace system;

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

class View
{
	private $vars = [];

	private function include(string $file)
	{
		if (!file_exists(ROOT_DIR . "cache/template/" . $file))
        {
            trigger_error(sprintf("Template file '%s' does not exist in '/cache/template'", $file));
        }

        require ROOT_DIR . "cache/template/" . $file;
	}

    public function output(string $file): string
    {
        return file_get_contents(ROOT_DIR . "cache/template/" . $file);;
    }

    public function parse(string $html)
    {
    	$template = &$this;
    	eval($html);
    }

    public function push(array $vars)
    {
    	$this->vars = $vars;
    }
}