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

    public function uploadedFile() {
        //$f = fopen($_FILES["file"]["name"], "r");
        $filePath = dirname(__FILE__).'/germplasmList.csv';
        $f = fopen($filePath, "r");
        //echo $_FILES["file"]["name"];
        //$fr = fread($f, filesize($_FILES["file"]["name"]));
        $fr = fread($f, filesize($filePath));
        fclose($f);
        $lines = array();
        $lines = explode("\n", $fr); // IMPORTANT the delimiter here just the "new line" \n 
        $dataString = array();
        for ($i = 0; $i < count($lines); $i++) {
            $cells = array();
            $cells = explode(";", $lines[$i]); // use the cell/row delimiter ;
            for ($k = 0; $k < count($cells) - 1; $k++) {
                array_push($dataString, $cells[$k]);
            }// for k end	
        }
        return $dataString;
    }

    public function csv_output() {
        $fp = fopen(dirname(__FILE__)."/output.csv", "r");
       
        while (($row = fgetcsv($fp)) !== FALSE) {
            $rows[] = $row;
        }
        fclose($fp);
        return $rows;
    }

    public function csv_corrected() {
        $fp = fopen(dirname(__FILE__)."/corrected.csv", "r");
        
        while (($row = fgetcsv($fp)) !== FALSE) {
            $rows[] = $row;
        }
        fclose($fp);
        return $rows;
    }

    public function csv_createdGID() {
        $fp = fopen(dirname(__FILE__)."/createdGID.csv", "r");
        while (($row = fgetcsv($fp)) !== FALSE) {
            $rows[] = $row;
        }
        fclose($fp);
        return $rows;
    }

    public function csv_checked() {
        $fp = fopen(dirname(__FILE__)."/checked.csv", "r");
        while (($row = fgetcsv($fp)) !== FALSE) {
            $rows[] = $row;
        }
        fclose($fp);
        return $rows;
    }

    public function csv_checked2() {
        $fp = fopen(dirname(__FILE__)."/checked.csv", "r");
        //$checked=array();
        while (($row = fgetcsv($fp)) !== FALSE) {
            $checked = $row;
        }
        fclose($fp);
        return $checked;
    }

    public function csv_existingTerm() {
        $myfile = dirname(__FILE__).'/existingTerm.csv';

        $fin = fopen($myfile, 'r');
        $existing = array();
        while (($line = fgetcsv($fin)) !== FALSE) {
            $existing[] = $line;
        }
        fclose($fin);
        return $existing;
    }

    public function json_checked() {
        //echo "checked dir:".dirname(__FILE__)."/checked.json";
        $json = file_get_contents(dirname(__FILE__)."/checked.json");
        $jsonIterator = new RecursiveIteratorIterator(
                new RecursiveArrayIterator(json_decode($json, TRUE)), RecursiveIteratorIterator::SELF_FIRST);
        $checked = array();
        foreach ($jsonIterator as $key => $val) {
            if (is_array($val)) {
//echo "$key:\n\n jj";
            } else {
//echo "$key : $val\n";
                array_push($checked, $val);
            }
        }
    //    fclose($json);
//echo count($checked);
        return $checked;
    }

    public function update_csv_correctedGID($fid, $mid, $checked) {
        //echo "checked count:".count($checked);
        $myfile = dirname(__FILE__).'/createdGID.csv';
        $fin = fopen($myfile, 'r');
        $data = array();
       //print_r($fid); print_r($mid);
        while ($line = fgetcsv($fin, 0)) {
            for ($i = 0; $i < count($checked); $i++) {
                if ($line[0] === $fid[$i] . "/" . $mid[$i]) {
                 //   echo "HERE*******";
                    $data[] = $line; //existingTerm data
                }
            }
        }
        fclose($fin);

        $myfile = dirname(__FILE__).'/corrected.csv';
        $fin = fopen($myfile, 'r');
        $data2 = array();   // data2: edited CreatedGID data
        $data3 = array();   //data 3 is the details of the chosen GID



        while ($line = fgetcsv($fin, 0)) {
            //echo join(', ', $line).'<br>';
            for ($i = 0, $k = count($line); $i < $k; $i++) {
                if ($line[2] == $fid) {

                    $line[0] = $data[0][3];
                    $data3 = $line; //data 3 is the details of the chosen GID				
                }
            }
            $data2[] = $line; // data2: edited CreatedGID data
        }
        fclose($fin);

        $fout = fopen($myfile, 'w');
        foreach ($data2 as $line) {
            fputcsv($fout, $line);
        }
        fclose($fout);
    }

    public function hasChecked($checked, $fid) {
        for ($i = 0; $i < count($checked); $i++) {
            if ($checked[$i] === $fid) {
                return true;
            }
        }
        return false;
    }

    public function updateGID_createdGID($term, $pedigree, $id, $choose, $fid, $mid, $female, $male) {
        $existingTerm = dirname(__FILE__).'/existingTerm.csv';
        $fin = fopen($existingTerm, 'r');
        $data = array();
        while ($line = fgetcsv($fin, 0)) {
            if ($line[2] === $choose) {
                $data[] = $line; //existingTerm data
            }
        }
        fclose($fin);

        $createdGID = dirname(__FILE__).'/createdGID.csv';
        $fin = fopen($createdGID, 'r');
        $data2 = array();
        $data4 = array();

        while ($line = fgetcsv($fin, 0)) {
            for ($i = 0, $k = count($line); $i < $k; $i++) {
                if ($line[0] === $id && $line[2] == $term) {
                    $line[3] = $data[0][6];
                    $line[4] = $data[0][7];
                    $line[5] = $data[0][8];
                    $line[6] = $data[0][9];
                    $line[7] = $data[0][10];
                    $line[8] = $data[0][2];
                    $line[9] = $data[0][4];
                    $data4 = $line; //data 4 is the details of the chosen GID				
                }
            }
            $data2[] = $line; // data2: edited CreatedGID data
        }
        fclose($fin);

        $fout = fopen($createdGID, 'w');
        foreach ($data2 as $line) {
            fputcsv($fout, $line);
        }
        fclose($fout);

        //array from createdGID.csv
        $rows = $this->csv_createdGID();

        for ($i = 0; $i < count($rows); $i++) {
            if ($rows[$i][0] === $fid . "/" . $mid) {
                $cross = $rows[$i][2];
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
        $data3["germplasm"] = $data4;
        $data3["cross"] = $cross;
        $data3["is_female"] = $is_female;

        return $data3;
    }

    public function getPedigreeLine() {

        // array from checked.csv file
        $rows_checked = $this->csv_checked();

        $csv_createdGID = dirname(__FILE__).'/createdGID.csv';
        $final = array();

        for ($i = 0; $i < count($rows_checked[0]) - 1; $i++) {
            $j = 0;
            //echo "i: " . $rows_checked[0][$i] . "<br>";
            $fin = fopen($csv_createdGID, 'r');
            while (($line = fgetcsv($fin)) !== FALSE) {
                $rows[] = $line;

                $c = (int) $rows_checked[0][$i] + 1;
                $e = (int) $rows[$j][0];
                $b = $rows[$j][0];
                $a = $e . "/" . ($c);

                if ($rows_checked[0][$i] === $rows[$j][0] || $c === $e || $b === $a) {
                    $final_rows[] = $line;
                }
                $j++;
            }
            fclose($fin);
            array_push($final, $final_rows);
            $final_rows = array();
        }
        return $final;
    }
    
       function checkIf_standardize($checked) {
        //echo "checked count:".count($checked);
        $rows=$this->csv_corrected();
        $selected = array();
        foreach ($rows as $row) : list($GID, $nval, $fid, $fremarks, $female, $femalename, $mid, $mremarks, $male, $malename) = $row;
        //echo "jksjkdj"."<br>";
        //echo $fid;
            for ($i = 0; $i < count($checked); $i++) {
          //      echo $fremarks." ".$mremarks."<br>";
          //      echo $fid." ".$checked[$i]."<br>";
                if ($fremarks === "in standardized format" && $fid === $checked[$i] && $mremarks === "in standardized format") {
                    $selected[$i] = $fid;
                }
            }

        endforeach;
       /* echo "<br>selected: ";
        print_r($selected);
        echo "<br>--selected: ";
        */
        return $selected;
    }
     function get_unselected_rows() {
        $checked = $this->csv_checked();
        //echo "<br>";
        //print_r($checked);

        $fp = fopen(dirname(__FILE__)."/corrected.csv", "r");
        while (($row = fgetcsv($fp)) !== FALSE) {
            $rows[] = $row;
        }
        fclose($fp);

        foreach ($rows as $row) : list($GID, $nval, $fid) = $row;

            if ($this->hasChecked($checked, $fid) === false) {
                $unselected[] = $fid;
            }
        endforeach;
        //echo "unselected:";
        //print_r($unselected);
        return $unselected;
    }
        public function csv_corrected_GID() {
        $rows = array();
        $fp = fopen(dirname(__FILE__)."/corrected.csv", "r");
       
        while (($row = fgetcsv($fp)) !== FALSE) {
            if ($row[0] != "N/A") {
                $rows[] = $row;
            }
        }
        fclose($fp);
         
        return $rows;
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
               if ($i === (int) $fid_i - 1) {
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
                   $female1_arr =$this->array_push_assoc($female1_arr, 'children', array($female_arr));
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
       $json = new json($tree);
       $json->create_tree();
       return $fid_i;
   }
  

}

?>
