<?php

require_once("./config/config.php");
$controller = (isset($_GET["controller"]) && !empty($_GET["controller"])) ? trim($_GET["controller"]) : "calorific";
$action = (isset($_GET["action"]) && !empty($_GET["action"])) ? trim($_GET["action"]) : "index";
require_once("route/routes.php");
