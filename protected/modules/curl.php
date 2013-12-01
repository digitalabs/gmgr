<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of curl
 *
 * @author ncarumba
 */
class curl {

    public function __construct() {
        
    }

    public function exec($url, $data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
        $result = curl_exec($ch);
        //echo "<br>here yeah: ".$result."<br>";
        $output = json_decode($result, true);

        //$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        /* echo "<br>HTTP CODE ERROR: ".$code ."<br>";
          //echo $jsonText;
          echo "<br> cURL: WELCOME! ".$response."</outercode>";
          echo '<br>RESULT: '.print_r($response,1);
          echo "<br> END</outercode>"; */

        //if($code != 200){
        //     header("Location: /GMGR/index.php?r=site/contactUs");
        //}
        //print_r($output);
        return $output;
    }

    public function parse($data) {
        //http://localhost:8080/ws/standardization/term/parse
        $url = "http://localhost:8080/ws/standardization/term/post";

        return $this->exec($url, $data);
    }

    public function standardize($data) {
        $url = "http://localhost:8080/ws/standardization/term/standardize2";
        return $this->exec($url,$data);
    }

    public function createGID($data) {
        $url = "http://localhost:8080/ws/standardization/term/createGID2";
        return $this->exec($url, $data);
    }

    public function chooseGID($data) {
        $url = "http://localhost:8080/ws/standardization/term/chooseGID2";
        return $this->exec($url,$data);
    }

    public function updateMethod() {
        $url = "http://localhost:8080/ws/standardization/term/updateMethod";
        $this->exec($url);
    }

    public function editGermplasmName() {
        $url = "http://localhost:8080/ws/standardization/term/checkEditedString";
        $this->exec($url);
    }

}

?>
