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
        file_put_contents($jsonfile, $jsonText);
    }

    public function location() {
        $jsonfile = dirname(__FILE__)."/location.json";
        $data["locationID"] = $this->data;

        $this->toFile($jsonfile, $data);
    }

    function getFile() {
        $jsonfile = dirname(__FILE__)."/docinfo.json";

        $file_toArray = new file_toArray();
        $array = $file_toArray->uploadedFile();

        $a = array('list' => $array);
        $this->toFile($jsonfile, $a);
    }

    function checkedBox() {
        $exists = file_exists(dirname(__FILE__)."/checked.json");
        if ($exists) {
            unlink(root."checked.json");
        }
        $jsonfile = root . "checked.json";
        echo "**"."<br>";
        print_r($this->data);
        $data = array('checked' => $this->data);
        $this->toFile($jsonfile, $data);
    }

    function chosenGID() {
        $jsonfile = dirname(__FILE__)."/term.json";

        $this->toFile($jsonfile, $this->data);
    }

}

?>
