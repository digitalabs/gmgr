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

    //$inputGID = $_GET['inputGID'];
    public function __construct() {
        
    }

    public function exec($url) {
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($handle);
        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        /* echo "<br>HTTP CODE ERROR: ".$code ."<br>";
          //echo $jsonText;
          echo "<br> cURL: WELCOME! ".$response."</outercode>";
          echo '<br>RESULT: '.print_r($response,1);
          echo "<br> END</outercode>"; */

        /* if($code != 200){
          header("Location: /GMGR/index.php?r=site/contactUs");
          } */
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

    public function updateMethod() {
        $url = "http://172.29.4.99:8083/ws/standardization/term/updateMethod";
        $this->exec($url);
    }

    public function show_germplasm_details() {
        $url = "http://172.29.4.99:8080/ws/standardization/term/show_germplasm_details";
        $this->exec($url);

        $gid = $_POST['hidGID'];
        $a = array('GID' => $gid);
        $data = json_encode($a);
        $ch = curl_init();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );

        $result = curl_exec($ch);
        $jsonfile = "trydocinfo.json";
        json_decode($result, true);
    }

    public function searchGID() {
        $url = "http://localhost:8080/ws/standardization/term/searchGID";
        $this->exec($url);

        $gid = $_POST['inputGID'];
        $level = $_POST['maxStep'];
        $a = array('GID' => $gid, 'LEVEL' => $level);
        //$b = array('LEVEL'=>$level);
        $data = json_encode($a);
        //$datab = json_encode($b);
        $ch = curl_init();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );

        $result = curl_exec($ch);
        $jsonfile = "trydocinfo.json";
        json_decode($result, true);
        //foreach($arr['GID'] as $GID){
        //	file_put_contents($jsonfile, print_r($arr));
        //echo "items: ". $GID;
        //echo('<pre>');
        //print_r($arr);
        //echo('</pre>');
        //};
        //echo "success"; 
    }

}

?>
