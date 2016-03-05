<?php

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

/* Include System Configuration */
require "../config.php";

/**
* Register given function as __autoload() implementation
*
* @link http://php.net/manual/en/function.spl-autoload-register.php
*/
spl_autoload_register(function($class) {
    $class_location = ROOT_DIR . str_replace("\\", "/", $class) . ".php";

    if (file_exists($class_location)) {
        require $class_location;
    }
});

/* Initiate the Application class and then run it */
$app = new system\Application();
$app->run();

?>