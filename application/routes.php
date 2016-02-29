<?php

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

$app->register("/", "TestController");

$app->register("/controller", "TestController", "test");

$app->registerCallBack("/callback", function($poop) {
    echo $poop;
});

?>