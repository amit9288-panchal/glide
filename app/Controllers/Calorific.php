<?php

use App\Models\CalorificModel;
use App\Models\AreaModel;

class Calorific
{
    private $calorific;
    private $area;

    function __construct()
    {
        $this->calorific = new CalorificModel();
        $this->area = new AreaModel();
    }

    /*
     * Display list of Calorific in data grid
     */
    function index()
    {
        try {
            $calorificInfo = $this->calorific->getAllCalorific();
            $areas = $this->area->getDistinctArea();
            require_once("./app/View/Calorific/index.php");
        } catch (Exception $e) {
            echo "Opps Error : " . $e->getMessage();
        }
    }

    /*
     * It will perform two types of search general search and
     * specific search based on selected criteria
     */
    function search()
    {
        try {
            if (count($_POST) === 0) {
                throw new Exception('Search is not proper!');
            }
            $search = [];
            if (trim($_POST['search_type']) === 'specific_search') {
                $search['calorific_date'] = $_POST['calorific_date'];
                $search['calorific_value'] = $_POST['calorific_value'];
                $search['area'] = $_POST['area'];
            } else {
                $search['calorific_date'] = $_POST['search'];
                $search['calorific_value'] = $_POST['search'];
                $search['area'] = $_POST['search'];
            }
            $calorificInfo = $this->calorific->getAllCalorific($search, trim($_POST['search_type']));
            $areas = $this->area->getDistinctArea();
            require_once("./app/View/Calorific/index.php");
        } catch (Exception $e) {
            echo "Opps Error : " . $e->getMessage();
        }
    }

    /*
     * This action will simply load import UI
     */
    function import()
    {
        require_once("./app/View/Calorific/import.php");
    }

    /*
     * Reading each record from uploaded csv and upload current year calorific
     * information to table
     * @note  1) while reading area from csv values having " after values which is handled
     *        2) date is treated as string which was not direct convert by date() which is handled
     *
     */
    function upload()
    {
        try {
            if (empty($_FILES)) {
                throw new Exception('Please upload csv file');
            }
            if ($_FILES['calorific_file']['type'] != 'text/csv' || $_FILES['calorific_file']['type'] == '') {
                throw new Exception('Please enter valid csv file');
            }
            $file = fopen($_FILES['calorific_file']['tmp_name'], "r");
            $row = 1;
            while ($singleRow = fgetcsv($file, 200, ";")) {
                if ($row == 1) {
                    $data = explode(",", $singleRow[0]);
                    if (strtolower(trim($data[1])) != 'applicable for' &&
                        strtolower(trim($data[2])) != 'data item' &&
                        strtolower(trim($data[3])) != 'value') {
                        throw new Exception('CSV does not have correct format for applicable for,data item,value');
                    }
                    $row++;
                    continue;
                } else {
                    $data = explode(",", $singleRow[0]);
                    $date = strtotime(trim(str_replace('/', '-', str_replace('"', '', $data[1])) . " 00:00:00"));
                    if (date('Y', $date) !== date('Y')) {
                        continue;
                    }
                    $area = trim(str_replace('"', '', $data[3]));
                    if (strpos($area, '(') !== false && strpos($area, ')') !== false) {
                        preg_match('#\((.*?)\)#', $area, $match);
                        $area = trim($match[1]);
                    }
                    $areaId = $this->area->insert(trim($area));
                    $calorificArr['applicable_date'] = date('Y-m-d', $date);
                    $calorificArr['value'] = number_format((float)trim(str_replace('"', '', $data[4])), 2, '.', '');
                    $calorificArr['area_id'] = $areaId;
                    $this->calorific->insert($calorificArr);

                }
            }
            fclose($file);
            header("Location: " . BASE_URL . 'calorific/?action=index');
        } catch (Exception $e) {
            echo "Opps Error : " . $e->getMessage();
        }
    }

    /*
     * Get statistic based on selection of date range and display graph
     */
    public function statistic()
    {
        try {
            $calorificInfo = [];
            if (isset($_POST['calorific_date_from']) && isset($_POST['calorific_date_to'])) {
                $filter['from'] = $_POST['calorific_date_from'];
                $filter['to'] = $_POST['calorific_date_to'];
                $calorificInfo = $this->calorific->getStatistics($filter);
            }
            $areas = $this->area->getDistinctArea();
            $dates = [];
            $averageValues = [];
            if (count($calorificInfo) > 0) {
                foreach ($calorificInfo as $calorificAvg) {
                    $dates[] = $calorificAvg['applicable_date'];
                    $averageValues[] = $calorificAvg['avg_value'];
                }
            }
            require_once("./app/View/Calorific/statistic.php");
        } catch (Exception $e) {
            echo "Opps Error : " . $e->getMessage();
        }
    }

    /*
     * @note Rather than creating general update function in model created specifically for Calorific value
     * Update Calorific value
     */
    public function update()
    {
        try {
            if (!isset($_POST['id']) && !isset($_POST['cal_value'])) {
                throw new Exception('Missing information');
            }

            $updated = $this->calorific->updateCalorificValue((int)$_POST['id'], $_POST['cal_value']);
            if ($updated) {
                echo "Updated value";
            }
        } catch (Exception $e) {
            echo "Opps Error : " . $e->getMessage();
        }
    }
}