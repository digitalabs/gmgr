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

    public function startConnection() {
        //http://localhost:8080/ws/standardization/term/parse
        $url = "http://localhost:8080/ws/standardization/term/connect";

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($handle);
    }

    public function endConnection() {
        //http://localhost:8080/ws/standardization/term/parse
        $url = "http://localhost:8080/ws/standardization/term/connect";
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($handle);
    }

    public function updateGermplasmName($data) {
        //http://localhost:8080/ws/standardization/term/parse
        $url = "http://localhost:8080/ws/standardization/term/updateGermplasmName";
        echo "<br>here";
        return $this->exec($url, $data);
    }

    public function parse($data) {
        //http://localhost:8080/ws/standardization/term/parse
        $url = "http://localhost:8080/ws/standardization/term/post";

        return $this->exec($url, $data);
    }

    public function standardize($data) {
        $url = "http://localhost:8080/ws/standardization/term/standardize2";
        return $this->exec($url, $data);
    }

    public function createGID($data) {
        $url = "http://localhost:8080/ws/standardization/term/createGID2";
        return $this->exec($url, $data);
    }

    public function createGID2($data) {
        $url = "http://localhost:8080/ws/standardization/term/createGID3";
        return $this->exec($url, $data);
    }

    public function chooseGID($data) {
        $url = "http://localhost:8080/ws/standardization/term/chooseGID2";
        return $this->exec($url, $data);
    }

    public function updateMethod($data) {
        $url = "http://localhost:8080/ws/standardization/term/updateMethod";
        return $this->exec($url, $data);
    }

    public function editGermplasmName($data) {
        $url = "http://localhost:8080/ws/standardization/term/checkEditedString";
        return $this->exec($url, $data);
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
