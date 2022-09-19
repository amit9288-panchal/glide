<?php

namespace App\Models;

use App\Database;

class AreaModel
{
    private $db;
    const TABLE = 'area_mst';
    const PRIMARY_KEY = 'id';

    public function __construct()
    {
        $this->db = Database\Database::getInstance();
    }

    /*
     * Checking given area is exists in table
     * $return integer existing area id or zero
     */
    public function checkAreaExists(string $name)
    {
        try {
            $query = "SELECT id 
                  FROM `" . self::TABLE . "`
                  WHERE deleted_at IS NULL  AND LOWER(area_name) = '" . strtolower($name) . "'";
            $result = mysqli_query($this->db, $query);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                return $row['id'];
            }
            return 0;
        } catch (Exception $e) {
            echo "Opps Error : " . $e->getMessage();
        }
    }

    /*
     * Insert area in table
     * $return integer existing area id or inserted area id
     */
    public function insert(string $name)
    {
        try {
            $id = $this->checkAreaExists($name);
            if ($id > 0) {
                return $id;
            }
            $query = "INSERT INTO `" . self::TABLE . "`
                  (`area_name`) VALUES ('" . $name . "')";
            mysqli_query($this->db, $query);
            return mysqli_insert_id($this->db);
        } catch (Exception $e) {
            echo "Opps Error : " . $e->getMessage();
        }
    }

    /*
     * Get distinct area available in table
     * $return array list of area
     */
    public function getDistinctArea()
    {
        try {
            $areas = [];
            $query = "SELECT DISTINCT area_name  
                  FROM `" . self::TABLE . "`
                  WHERE deleted_at IS NULL ORDER BY area_name ASC ";
            $result = mysqli_query($this->db, $query);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $areas[] = $row['area_name'];
                }
            }
            return $areas;
        } catch (Exception $e) {
            echo "Opps Error : " . $e->getMessage();
        }
    }
}