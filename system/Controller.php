<?php namespace system;

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

abstract class Controller
{
    private $models = [];

    public abstract function main();

    public function __get($model): Model
    {
        return $this->models[$model];
    }

    public function register(array $models)
    {
        foreach ($models as $name => $class)
        {
            if (array_key_exists($name, $this->models))
            {
                trigger_error(sprintf("Model '%s' is already initiated in %s", $name, __METHOD__), E_USER_ERROR);
            }

            $this->models[$name] = new $class;
        }
    }

    private function request($request_name): string
    {
        return @array_merge($_POST, $_GET)[$request_name];
    }
}