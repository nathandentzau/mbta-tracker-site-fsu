<?php

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

$app->register("green", "GreenLineController");
$app->register("green/b", "GreenLineController", "b");
$app->register("green/c", "GreenLineController", "c");
$app->register("green/d", "GreenLineController", "d");
$app->register("green/e", "GreenLineController", "e");

?>