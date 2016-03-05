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
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public abstract function main();

    /**
    * What PHP defines as a "Magic Method." This method returns a value of the $this->models array.
    *
    * @link http://php.net/manual/en/language.oop5.overloading.php#object.get
    */
    public function __get($model): Model
    {
        return $this->models[$model];
    }

    /**
    * This method registers all the models needed for our Controller to be able to process data. That's
    * right, keep your data processing out of these damn controllers.
    *
    * @param array $models We're defining models like so: name => namespace\class. Pretty simple.
    */
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

    /**
    * This method returns a merged array of post and get. I thought of this one before bed one night.
    *
    * @param string $request This string is the key passed through the global arrays of _GET and _POST.
    * @return string Yeah, we're just returning the value of the associative array key that's called.
    */
    private function request(string $request): string
    {
        return @array_merge($_POST, $_GET)[$request];
    }
}