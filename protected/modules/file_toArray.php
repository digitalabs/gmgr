<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of file_toArray
 *
 * @author ncarumba
 */
class file_toArray {

    public function __construct() {
        
    }

    public function uploadedFile($filePath) {

        $f = fopen($filePath, "r");

        //get headers
        $row1 = fgetcsv($f, 1000, ';');
        for ($i = 0; $i < count($row1); $i++) {
            $header = array();
            $header = explode(",", $row1[$i]);
        }
       // echo "<br>HEADER<br>";
       // print_r($header);

        $column_female = -1;
        $column_male = -1;
        $column_cross = -1;
        $column_date = -1;
        for ($i = 0; $i < count($header) - 1; $i++) {


            if (preg_match("/female/i", $header[$i], $output_array) == 1) {

                $column_female = $i;
            } elseif (preg_match("/male/i", $header[$i], $output_array) == 1) {
                $column_male = $i;
            }
            if (preg_match("/cross/i", $header[$i], $output_array) == 1) {
                $column_cross = $i;
            }
            if (preg_match("/date/i", $header[$i], $output_array) == 1) {
                $column_date = $i;
            }
        }
        // echo "cross: ".$column_cross . "<br>";

        $fr = fread($f, filesize($filePath));
        fclose($f);
        $lines = array();
        $lines = explode("\n", $fr); // IMPORTANT the delimiter here just the "new line" \n 
        $dataString = array();
        //echo $column_date . "<br>";
        for ($i = 0; $i < count($lines); $i++) {
            $cells = array();
            $cells = explode(",", $lines[$i]); // use the cell/row delimiter ;
            //print_r($cells);
            //echo "<br>";
            
            for ($k = 0; $k < count($cells) - 1; $k++) {

                if ($k == $column_cross) {
                    $cross = $cells[$k];
                } elseif ($k == $column_female) {
                    $female = $cells[$k];
                } elseif ($k == $column_male) {
                    $male = $cells[$k];
                } elseif ($k == $column_date) {
                    $date = $cells[$k];
                }
                
            }// for k end
            array_push($dataString, $cross);
            array_push($dataString, $female);
            array_push($dataString, $male);
            if ($column_date == -1 || empty($date)) {
                array_push($dataString, "not specified");
            } else {
                array_push($dataString, $date);
            }
        }
        return $dataString;
    }

    public function hasChecked($checked, $fid) {
        for ($i = 0; $i < count($checked); $i++) {
            if ($checked[$i] === $fid) {
                return true;
            }
        }
        return false;
    }

    public function updateGID_createdGID($term, $pedigree, $id, $choose, $fid, $mid, $female, $male, $createdGID, $existingTerm, $list, $userID, $theParent) {

        $data = array();
        for ($i = 0, $k = count($existingTerm); $i < $k; $i++) {
            if ($existingTerm[$i][2] === $choose) {
                $data[] = $existingTerm[$i]; //existingTerm data
            }
        }

        $data2 = array();
        $germplasm = array();

        for ($i = 0, $k = count($createdGID); $i < $k; $i++) {
            // echo "".$createdGID[$i][0]."  id: ".$id."<br>";
            // echo "".$createdGID[$i][2]."  id: ".$term."<br>";
            if ($createdGID[$i][0] == $id && $createdGID[$i][2] == $term) {
                $createdGID[$i][3] = $data[0][6];
                $createdGID[$i][4] = $data[0][7];
                $createdGID[$i][5] = $data[0][8];
                $createdGID[$i][6] = $data[0][9];
                $createdGID[$i][7] = $data[0][10];
                $createdGID[$i][8] = $data[0][2];
                $createdGID[$i][9] = $data[0][4];
                //$germplasm = $createdGID; //data 4 is the details of the chosen GID	

                $germplasm[0] = $createdGID[$i][0];
                $germplasm[1] = $createdGID[$i][1];
                $germplasm[2] = $createdGID[$i][2];
                $germplasm[3] = $createdGID[$i][3];
                $germplasm[4] = $createdGID[$i][4];
                $germplasm[5] = $createdGID[$i][5];
                $germplasm[6] = $createdGID[$i][6];
                $germplasm[7] = $createdGID[$i][7];
                $germplasm[8] = $createdGID[$i][8];
                $germplasm[9] = $createdGID[$i][9];
                // echo "HERE"."<br>";
            }
            $data2[] = $createdGID; // data2: edited CreatedGID data
        }

        //array from createdGID.csv

        print_r($germplasm);
        echo "<br><br>";


        for ($i = 0; $i < count($createdGID); $i++) {
            if ($createdGID[$i][0] == $fid . "/" . $mid) {
                $cross = $createdGID[$i][2];
            }
        }


        if ($fid === $choose) {
            $is_female = true;
        } else {
            $is_female = false;
        }
        if ($id === $mid) {
            $data3["parent2ID"] = $fid;
            $data3["parent2"] = $female;
        } else {
            $data3["parent2ID"] = $mid;
            $data3["parent2"] = $male;
        }

        $data3["parent1"] = $pedigree; // data 3 is the container for json file
        $data3["term"] = $term;
        $data3["germplasm"] = $germplasm;
        $data3["cross"] = $cross;
        $data3["is_female"] = $is_female;
        $data3["createdGID"] = $createdGID;
        $data3["list"] = $list;
        $data3["existingTerm"] = $existingTerm;
        $data3["userID"] = $userID;
        $data3["theParent"] = $theParent;

        return $data3;
    }

    public function getPedigreeLine($rows_checked, $rows) {

        $final = array();
        $final_rows = array();

        for ($i = 0; $i < count($rows_checked); $i++) {

            for ($j = 0; $j < count($rows); $j++) {

                $c = (int) $rows_checked[$i] + 1; //male id
                $e = (int) $rows[$j][0]; //female id
                $b = $rows[$j][0];
                $a = $e . "/" . ($c);

                if ($rows_checked[$i] === $rows[$j][0] || $c === $e || $b === $a) {
                    $final_rows[] = $rows[$j];
                }
            }

            array_push($final, $final_rows);
            $final_rows = array();
        }
        return $final;
    }

    function checkIf_standardize($checked, $rows) {
        //print_r($checked);
        // echo "checked count:".count($checked);

        $selected = array();
        foreach ($rows as $row) : list($GID, $nval, $fid, $fremarks, $female, $femalename, $mid, $mremarks, $male, $malename) = $row;
            for ($i = 0; $i < count($checked); $i++) {

                if ($fremarks == "in standardized format" && $fid == $checked[$i] && $mremarks == "in standardized format") {
                    $selected[] = $fid;
                }
            }
        //echo "count selected:".count($selected);
        endforeach;
        //echo "<br>selected: ";
        // print_r($selected);
        // echo "<br>--selected: ";

        return $selected;
    }

    public function csv_corrected_GID($list) {
        $rows = array();
        for ($i = 0; $i < count($list); $i++) {
            if ($list[$i][0] != "N/A") {
                $rows[] = $list[$i];
            }
        }

        return $rows;
    }

    function get_unselected_rows($checked, $rows) {

        $unselected = array();
        for ($i = 0; $i < count($rows); $i++) {
            $fid = $rows[$i][2];
            if ($this->hasChecked($checked, $fid) === false) {
                $unselected[] = $fid;
            }
        }

        return $unselected;
    }

    public function array_push_assoc($array, $key, $value) {
        $array[$key] = $value;
        return $array;
    }

    public function output_tree_json($pages) {

        /* create array for tree.json */
        $mid = (int) ($pages[0][0][0]) + 1;
        for ($i = (count($pages[0]) - 2); $i >= 0; $i--) {
            if ($mid === (int) $pages[0][$i][0]) {
                if ($i === count($pages[0]) - 2) {
                    $male_arr = array(
                        "name" => $pages[0][$i][2],
                        "gid" => $pages[0][$i][3],
                        "location" => $pages[0][$i][7]
                    );
                    //print_r($male_arr);
                    //echo "<br>";
                } else {
                    $male1_arr = array(
                        "name" => $pages[0][$i][2],
                        "gid" => $pages[0][$i][3],
                        "location" => $pages[0][$i][7]
                    );
                    $male1_arr = $this->array_push_assoc($male1_arr, 'children', array($male_arr));
                    $male_arr = $male1_arr;
                }
                $fid_i = $i;
            } else {
                if ($i == (int) $fid_i - 1) {
                    $female_arr = array(
                        "name" => $pages[0][$i][2],
                        "gid" => $pages[0][$i][3],
                        "location" => $pages[0][$i][7]
                    );
                    //print_r($female_arr);
                    //echo "HERE<br>";
                } else {
                    //$female=array();
                    $female1_arr = array(
                        "name" => $pages[0][$i][2],
                        "gid" => $pages[0][$i][3],
                        "location" => $pages[0][$i][7]
                    );
                    //echo "<br>";
                    //print_r($female1_arr);
                    $female1_arr = $this->array_push_assoc($female1_arr, 'children', array($female_arr));
                    $female_arr = $female1_arr;
                }
            }
        }

        $tree = array(
            "name" => $pages[0][count($pages[0]) - 1][2],
            "gid" => $pages[0][count($pages[0]) - 1][3],
            "location" => $pages[0][count($pages[0]) - 1][7],
            "children" => array($female_arr, $male_arr)
        );
        //echo "<br>";
        //print_r($tree);
        Yii::import('application.modules.json');
        $json = new json($tree);
        $json->create_tree();
        return $fid_i;
    }

}

?>
