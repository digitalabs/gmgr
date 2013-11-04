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

    public function exec($url) {
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($handle);
          $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
          //echo "<br>HTTP CODE ERROR: ".$code ."<br>";
          //echo $jsonText;
         // echo "<br> cURL: WELCOME! ".$response."</outercode>";
         // echo '<br>RESULT: '.print_r($response,1);
         // echo "<br> END</outercode>";
         
    }

    public function parse() {
		//http://172.29.4.99:8083/ws/standardization/term/parse
        $url = "http://172.29.4.99:8083/ws/standardization/term/parse";
        $this->exec($url);
    }

    public function standardize() {
        $url = "http://172.29.4.99:8083/ws/standardization/term/standardize";
        $this->exec($url);
    }

     public function createGID() {
        $url = "http://172.29.4.99:8083/ws/standardization/term/createGID";
        $this->exec($url);
    }
    
    public function chooseGID() {
        $url = "http://172.29.4.99:8083/ws/standardization/term/chooseGID";
        $this->exec($url);
    }

}

?>
