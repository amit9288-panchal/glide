<?php

session_start();
define("ENVIRONMENT", 'local');
define("PROJECT", 'glide');
define("ROOT", $_SERVER['DOCUMENT_ROOT'] . '/' . PROJECT . '/');
define(
    "BASE_URL",
    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http' . '://' . $_SERVER['SERVER_NAME'] . '/' . PROJECT . '/'
);
require_once("db.config.php");
require_once("./app/Database/Database.php");
require_once("./app/Models/Calorific.php");
require_once("./app/Models/Area.php");
