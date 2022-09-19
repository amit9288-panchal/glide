<?php

namespace App\Database;

class Database
{
    private static $instance = null;

    public static function getInstance()
    {
        try {
            global $config;
            if (!isset(self::$instance)) {
                self::$instance = mysqli_connect(
                    $config[ENVIRONMENT]['HOST'],
                    $config[ENVIRONMENT]['USER'],
                    $config[ENVIRONMENT]['PASSWORD'],
                    $config[ENVIRONMENT]['DATABASE']
                );
            }
            return self::$instance;
        } catch (\PDOException $e) {
            throw new \Exception('DB Connection Error : ' . $e->getMessage());
        }
    }
}
