<?php

namespace App\Models;

use App\Database;

class CalorificModel
{
    private $db;
    const TABLE = 'calorific_mst';
    const PRIMARY_KEY = 'id';

    public function __construct()
    {
        $this->db = Database\Database::getInstance();
    }

    /*
     * To get all calorific based on type
     * $return Array list of calorific
     */
    public function getAllCalorific(array $search = [], $type = "general_search")
    {
        try {
            $calorificInfo = [];
            $query = " SELECT cal.id,cal.applicable_date,cal.value,cal.area_id,am.area_name FROM `" . self::TABLE . "` as cal";
            $query .= " INNER JOIN area_mst am ON cal.area_id=am.id";
            $query .= " WHERE cal.deleted_at IS NULL AND YEAR(cal.applicable_date) = YEAR(CURDATE()) ";
            if (!empty($search) && (trim($_POST['search_type']) === 'specific_search')) {
                if (!empty($search['calorific_date'])) {
                    $query .= " AND cal.applicable_date like '%" . date(
                            'Y-m-d',
                            strtotime($search['calorific_date'])
                        ) . "%' ";
                }
                if (!empty($search['calorific_value'])) {
                    $query .= " AND cal.value like '%" . $search['calorific_value'] . "%' ";
                }
                if (!empty($search['area'])) {
                    $query .= " AND am.area_name like '%" . $search['area'] . "%' ";
                }
            } elseif (!empty($search) && (trim($_POST['search_type']) === 'general_search')) {
                $query .= " AND cal.applicable_date like '%" . date(
                        'Y-m-d',
                        strtotime($search['calorific_date'])
                    ) . "%' ";
                $query .= " OR cal.value like '%" . $search['calorific_value'] . "%' ";
                $query .= " OR am.area_name like '%" . $search['area'] . "%' )";
            }

            $query .= " ORDER BY cal.applicable_date ASC ";
            $result = mysqli_query($this->db, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $calorificInfo[] = [
                    'id' => $row['id'],
                    'applicable_date' => $row['applicable_date'],
                    'value' => number_format((float)$row['value'], 2, '.', ''),
                    'area_id' => $row['area_id'],
                    'area_name' => $row['area_name']
                ];
            }
            return $calorificInfo;
        } catch (Exception $e) {
            echo "Opps Error : " . $e->getMessage();
        }
    }

    /*
     * Insert data based on provided array
     * $return bool/integer false or inserted id
     */
    public function insert(array $dataArr = [])
    {
        try {
            if (count($dataArr) === 0) {
                return false;
            }
            $query = "INSERT INTO `" . self::TABLE . "`
                  (`applicable_date`,`value`,`area_id`) VALUES ('" . $dataArr['applicable_date'] . "','" . $dataArr['value'] . "','" . $dataArr['area_id'] . "')";
            mysqli_query($this->db, $query);
            return mysqli_insert_id($this->db);
        } catch (Exception $e) {
            echo "Opps Error : " . $e->getMessage();
        }
    }

    /*
     * Geting average value based on selected date range
     * @return Array Average value with date
     */
    public function getStatistics(array $filter = [])
    {
        $query = " SELECT applicable_date,AVG(value) as `avg_value` FROM `" . self::TABLE . "` as cal";
        $query .= " WHERE applicable_date BETWEEN '" . date('Y-m-d', strtotime($filter['from'])) . "' 
                        AND '" . date('Y-m-d', strtotime($filter['to'])) . "' ";
        $query .= " GROUP BY applicable_date ";
        $query .= " ORDER BY avg_value DESC LIMIT 10 ";
        $result = mysqli_query($this->db, $query);
        $calorificInfo = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $calorificInfo[] = [
                'applicable_date' => $row['applicable_date'],
                'avg_value' => number_format((float)$row['avg_value'], 6, '.', '')
            ];
        }
        return $calorificInfo;
    }

    /*
     * Right rather than creating general update function
     * it is specific to only calorific value
     */
    public function updateCalorificValue($id, $calValue)
    {
        $query = "UPDATE `" . self::TABLE . "` SET value='" . $calValue . "' WHERE id='" . $id . "'";
        return mysqli_query($this->db, $query);
    }
}