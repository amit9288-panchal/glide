<?php

/*
 * Call relevant controller and action
 */
function call($controller, $action)
{
    require_once("./app/Controllers/$controller.php");
    $controller = new $controller;
    $controller->{$action}();
}

/*
 * Valid action associated with controller
 */
$controllers = array(
    'calorific' => ['index', 'search', 'import', 'upload','statistic','update']
);
if (array_key_exists($controller, $controllers)) {
    if (in_array($action, $controllers[$controller])) {
        call(ucfirst($controller), $action);
    } else {
        echo "Invalid route!";
        die;
    }
} else {
    echo "Invalid route!";
    die;
}