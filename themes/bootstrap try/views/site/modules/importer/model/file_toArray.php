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
        $filePath = dirname(__FILE__).'/../germplasmList.csv';
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
        $fp = fopen(dirname(__FILE__)."/../output.csv", "r");

        while (($row = fgetcsv($fp)) !== FALSE) {
            $rows[] = $row;
        }
        fclose($fp);
        return $rows;
    }

    public function csv_corrected() {
        $fp = fopen(dirname(__FILE__)."/../corrected.csv", "r");
        
        while (($row = fgetcsv($fp)) !== FALSE) {
            $rows[] = $row;
        }
        fclose($fp);
        return $rows;
    }

    public function csv_createdGID() {
        $fp = fopen(dirname(__FILE__)."/../createdGID.csv", "r");
        while (($row = fgetcsv($fp)) !== FALSE) {
            $rows[] = $row;
        }
        fclose($fp);
        return $rows;
    }

    public function csv_checked() {
        $fp = fopen(dirname(__FILE__)."/../checked.csv", "r");
        while (($row = fgetcsv($fp)) !== FALSE) {
            $rows[] = $row;
        }
        fclose($fp);
        return $rows;
    }

    public function csv_checked2() {
        $fp = fopen(dirname(__FILE__)."/../checked.csv", "r");
        //$checked=array();
        while (($row = fgetcsv($fp)) !== FALSE) {
            $checked = $row;
        }
        fclose($fp);
        return $checked;
    }

    public function csv_existingTerm() {
        $myfile = dirname(__FILE__).'/../existingTerm.csv';

        $fin = fopen($myfile, 'r');
        $existing = array();
        while (($line = fgetcsv($fin)) !== FALSE) {
            $existing[] = $line;
        }
        fclose($fin);
        return $existing;
    }

    public function json_checked() {
        $json = file_get_contents(dirname(__FILE__)."/../checked.json");
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
        $myfile = dirname(__FILE__).'/../createdGID.csv';
        $fin = fopen($myfile, 'r');
        $data = array();

        while ($line = fgetcsv($fin, 0)) {
            for ($i = 0; $i < count($checked); $i++) {
                if ($line[0] === $fid . "/" . $mid) {
                    echo "HERE*******";
                    $data[] = $line; //existingTerm data
                }
            }
        }
        fclose($fin);

        $myfile = dirname(__FILE__).'/../corrected.csv';
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
        $existingTerm = dirname(__FILE__).'/../existingTerm.csv';
        $fin = fopen($existingTerm, 'r');
        $data = array();
        while ($line = fgetcsv($fin, 0)) {
            if ($line[2] === $choose) {
                $data[] = $line; //existingTerm data
            }
        }
        fclose($fin);

        $createdGID = dirname(__FILE__).'/../createdGID.csv';
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

        $csv_createdGID = dirname(__FILE__).'/../createdGID.csv';
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

}

?>
