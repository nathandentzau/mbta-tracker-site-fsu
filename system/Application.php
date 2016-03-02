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

    /**
    * Get the current URI from _SERVER global
    *
    * @link http://php.net/manual/en/reserved.variables.server.php
    * @return string
    */
    public function getRequestUri(): string
    {
        return $_SERVER["REQUEST_URI"];
    }

    /**
    * Output the return of the controllers. If the return is an array it will be
    * converted to JSON and if it's a string, well we really don't care that much
    * so just echo it.
    *
    * @param string|array $result Either an array or string returned from the controller
    * @link http://php.net/json_encode
    */
    public function output($result)
    {
        echo (is_array($result)) ? json_encode($result) : $result;
    }

    /**
    * Register a call back or closure to a URI. We will then add it to our $this->routes array.
    *
    * @param string $route The route uri being selected
    * @param Closure $callback The anonymous function being passed
    */
    public function registerCallBack(string $route, Closure $callback)
    {
        if (array_key_exists($route, $this->routes))
        {
            trigger_error(sprintf("Route '%s' is already registered", $route), E_USER_ERROR);
        }

        $this->routes[$route] = ["controller" => null, "method" => $callback];
    }

    /**
    * Register a controller within the application directory. If a method is not specified
    * we will call the main method by default. Kinda like Java, I like it that way.
    *
    * @param string $route The route uri being selected
    * @param string $controller The name of the controller, no need to specify it's namespace
    * @param string $method The method in the controller to be called. The main method is default!
    */
    public function register(string $route, string $controller, string $method = "main")
    {
        if (array_key_exists($route, $this->routes))
        {
            trigger_error(sprintf("Route '%s' is already registered", $route), E_USER_ERROR);
        }

        $controllerClass = "application\\Controllers\\" . $controller;
        $this->routes[$route] = ["controller" => new $controllerClass, "method" => $method];
    }

    /**
    * This method is called in public/index.php to run the entire application. This little guy
    * is a pretty important piece of our puzzel. First we're including the defined routes within 
    * application directory. Then we're initiating the route and the outputing the result of the
    * defined controller (or callback).
    *
    * @see application/routes.php This is where we define the routes for the application
    */
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