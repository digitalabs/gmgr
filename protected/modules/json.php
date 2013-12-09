<?php

include_once(dirname(__FILE__) . "/file_toArray.php");
include_once(dirname(__FILE__) . "/curl.php");
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of json
 *
 * @author ncarumba
 */
define("root", dirname(__FILE__) . "/GMGR/");

class json {

    public function __construct($data) {
        $this->data = $data;
    }

    public function toFile($data) {
        return json_encode($data);
        //file_put_contents($jsonfile, $jsonText);
    }

    public function location() {
        $jsonfile = dirname(__FILE__) . "/../../json_files/location.json";
        $data["locationID"] = $this->data;
        $this->toFile($jsonfile, $data);
    }

    function getFile() {

        $file_toArray = new file_toArray();
        $array = $file_toArray->uploadedFile();

        $a = array('list' => $array,
            'locationID' => $this->data
        );

        //$this->toFile($jsonfile, $a);
        return $this->toFile($a);
    }

    function checkedBox() {
        $exists = (dirname(__FILE__) . "/../../json_files/checked.json");

        if ($exists) {

            $filepath = Yii::app()->basePath . '/../../json_files/checked.json';

            //unlink($filepath);
        }

        $jsonfile = dirname(__FILE__) . "/../../json_files/checked.json";
        //echo "jsonfile:".$jsonfile;
        // echo "**"."<br>";
        //print_r($this->data);
        $xdata = array('checked' => $this->data);
        // print_r($xdata);
        $this->toFile($jsonfile, $xdata);
    }

    function chosenGID() {
        $jsonfile = dirname(__FILE__) . "/../../json_files/term.json";

        $this->toFile($jsonfile, $this->data);
    }

    function create_tree() {
        $jsonfile = dirname(__FILE__) . "/../../json_files/tree.json";

        $this->toFile($jsonfile, $this->data);
    }

    function create_changeMethod() {
        $jsonfile = dirname(__FILE__) . "/../../json_files/changeMethod.json";

        $this->toFile($jsonfile, $this->data);
    }

}

?>
