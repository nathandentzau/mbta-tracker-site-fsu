<?php

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

const ROOT_DIR = "../";

spl_autoload_register(function($class) {
    $class_location = ROOT_DIR . str_replace("\\", "/", $class) . ".php";

    if (file_exists($class_location)) {
        require $class_location;
    }
});

$app = new system\Application();
$app->run();

?>