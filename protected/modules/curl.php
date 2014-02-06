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
        $url = "http://localhost:8080/ws/standardization/term/show_germplasm_details";
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
	
        $gid = $_POST['inputGID'];
        $level = $_POST['maxStep'];
        $a = array('GID' => $gid, 'LEVEL' => $level);
        $data = json_encode($a);
		
		$url = "http://localhost:8080/ws/standardization/term/searchGID";
        $this->exec($url,$data);
        
        //$ch = curl_init();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
		
		//$results
        $result = curl_exec($ch);
        $jsonfile = "trydocinfo.json";
        $try = json_decode($result, true); 
		
		
		//echo $result;
		return $try;
    }
    public function showDiagram(){
        $gid = $_GET['inputGID'];
        $level = $_GET['maxStep'];
        $a = array('GID' => $gid, 'LEVEL' => $level);
        $data = json_encode($a);
		
		$url = "http://localhost:8080/ws/standardization/term/searchGID";
        $this->exec($url,$data);
        
        //$ch = curl_init();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
		
		//$results
        $result = curl_exec($ch);
        $jsonfile = "trydocinfo.json";
        $try = json_decode($result, true); 
		
		
		//echo $result;
		return $try;
    }
	
	public function editGermplasm() {
	
        //$gid = $_POST['inputGID'];
        //$level = $_POST['maxStep'];
        $a = array('GID' => 50533, 'LEVEL' => 2);
        $data = json_encode($a);
		
		$url = "http://localhost:8080:8080/ws/standardization/term/editGermplasm";
        $this->exec($url,$data);
        
        //$ch = curl_init();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
		
		//$results
        $result = curl_exec($ch);
        $jsonfile = "trydocinfo.json";
        $try = json_decode($result, true); 
		
		
		//echo $result;
		return $try;
    }

}

?>
