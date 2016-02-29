<?php namespace system;

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

use \Closure;

class Application
{
    private $routes = [];

    public function getRequestUri(): string
    {
        return $_SERVER["REQUEST_URI"];
    }

    public function output($result)
    {
        echo (is_array($result)) ? json_encode($result) : $result;
    }

    public function registerCallBack(string $route, Closure $callback)
    {
        if (array_key_exists($route, $this->routes))
        {
            trigger_error(sprintf("Route '%s' is already registered", $route), E_USER_ERROR);
        }

        $this->routes[$route] = ["controller" => null, "method" => $callback];
    }

    public function register(string $route, string $controller, string $method = "main")
    {
        if (array_key_exists($route, $this->routes))
        {
            trigger_error(sprintf("Route '%s' is already registered", $route), E_USER_ERROR);
        }

        $controllerClass = "application\\Controllers\\" . $controller;
        $this->routes[$route] = ["controller" => new $controllerClass, "method" => $method];
    }

    public function run()
    {
        $app = &$this;
        require ROOT_DIR . "application/routes.php";

        if (!array_key_exists($this->getRequestUri(), $this->routes))
        {
            trigger_error("The page does not exist", E_USER_ERROR);
        }

        $route = $this->routes[$this->getRequestUri()];
        $callable = ($route["controller"] !== null) ? [$route["controller"], $route["method"]] : $route["method"];

        $this->output(call_user_func_array($callable, ["I am passed by Application->run()"]));
    }
}