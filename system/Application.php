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
    * Output the return of the controllers. If the return is an array it will be
    * converted to JSON and if it's a string, well we really don't care that much
    * so just echo it.
    *
    * @param string|array $result Either an array or string returned from the controller
    * @link http://php.net/json_encode
    */
    private function output($result)
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
        $this->register($route, null, $callback);
    }

    /**
    * Register a controller within the application directory. If a method is not specified
    * we will call the main method by default. Kinda like Java, I like it that way.
    *
    * @param string $route The route uri being selected
    * @param mixed $controller The name of the controller, no need to specify it's namespace
    * @param mixed $method The method in the controller to be called. The main method is default!
    */
    public function register(string $route, $controller, $method = "main")
    {
        if (array_key_exists($route, $this->routes))
        {
            trigger_error(sprintf("Route '%s' is already registered", $route), E_USER_ERROR);
        }

        if ($controller !== null)
        {
            $className = "application\\Controllers\\" . $controller;
            $controller = new $className;
        }

        $this->routes[$route] = ["controller" => $controller, "method" => $method];
    }

    /**
    * Request method that returns the requested key of either a _GET or _POST variable 
    * 
    * @param string $key This is the key of the associative array being called
    * @return string returns the value of the requested key. Array out of bounds exceptions ignored
    */
    private function request(string $key)
    {
        return @array_merge($_GET, $_POST)[$key];
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

        if (array_key_exists($this->request("route"), $this->routes))
        {
            $route = $this->routes[$this->request("route")];
            $callable = ($route["controller"] !== null) ? [$route["controller"], $route["method"]] : $route["method"];

            $this->output(call_user_func($callable));
        }
    }
}