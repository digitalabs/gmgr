<?php

include_once(dirname(__FILE__)."/file_toArray.php");
include_once(dirname(__FILE__)."/curl.php");
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of json
 *
 * @author ncarumba
 */
define("root", dirname(__FILE__)."/GMGR/");

class json {

    public function __construct($data) {
        $this->data = $data;
    }

    public function toFile($jsonfile, $data) {
        $jsonText = json_encode($data);
<<<<<<< HEAD
        //chmod($jsonfile,0777);
=======
        chmod($jsonfile, 0777);
>>>>>>> 43c07efb29ee0f4572c5f659dd6912e27fa7333b
        file_put_contents($jsonfile, $jsonText);
    }

    public function location() {
        $jsonfile = dirname(__FILE__)."/../../json_files/location.json";
        $data["locationID"] = $this->data;
        $this->toFile($jsonfile, $data);
    }

    function getFile() {
        $jsonfile = dirname(__FILE__)."/../../json_files/docinfo.json";
        //echo $jsonfile;
        $file_toArray = new file_toArray();
        $array = $file_toArray->uploadedFile();

        $a = array('list' => $array);
        $this->toFile($jsonfile, $a);
    }

    function checkedBox() {
        $exists = (dirname(__FILE__)."/../../json_files/checked.json");
        
        if ($exists) {
            
            $filepath=Yii::app()->basePath.'/../../json_files/checked.json';
           
            //unlink($filepath);
            
        }
       
        $jsonfile = dirname(__FILE__)."/../../json_files/checked.json";
        //echo "jsonfile:".$jsonfile;
       // echo "**"."<br>";
        //print_r($this->data);
        $xdata = array('checked' => $this->data);
       // print_r($xdata);
        $this->toFile($jsonfile, $xdata);
    }

    function chosenGID() {
        $jsonfile = dirname(__FILE__)."/../../json_files/term.json";

        $this->toFile($jsonfile, $this->data);
    }
    
    function create_tree() {
       $jsonfile = dirname(__FILE__)."/../../json_files/tree.json";

       $this->toFile($jsonfile, $this->data);
   }

}

?>
