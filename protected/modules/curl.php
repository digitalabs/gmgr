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
        // set some cURL options
        $result = curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        $result = curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
        $result = curl_exec($ch);
        //echo "<br>here yeah: ".$result."<br>";
        $output = json_decode($result, true);

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        echo "<br><br><br><br>HTTP CODE ERROR: " . $code . "<br>".$_SERVER['HTTP_REFERER'];
        /*  //echo $jsonText;
          echo "<br> cURL: WELCOME! ".$response."</outercode>";
          echo '<br>RESULT: '.print_r($response,1);
          echo "<br> END</outercode>"; */

        if (!curl_errno($ch)) {
            //
          //  echo "<br><br>could not connect to tomcat server";
        }

        if (empty($result)) {
            // some kind of an error happened
           // die(curl_error($ch));
           $values = parse_url($_SERVER['HTTP_REFERER']);
                $query = explode('&', $values['query']);

                for ($i = 0; $i < count($query); $i++) {
                    if ('yes=1' != $query[$i]) {
                        $append[] = $query[$i];
                    }
                }
                $query = implode('&', $append);
                $values['query'] = $query;
                $url = $values['scheme'] . '://' . $values['host'] . '/' . $values['path'] . '?' . $values['query'];
                echo "<br>".'"Location:'+ $values['path'] . '?' . $values['query']+'"';
            header('"Location:'+ $values['path'] . '?' . $values['query']+'"');
           // header("Location: /GMGR/index.php?r=site/contactUs");
            die();
             curl_close($ch); // close cURL handler
             
        } else {
            $info = curl_getinfo($ch);
            curl_close($ch); // close cURL handler

            if (empty($info['http_code'])) {
                die("No HTTP code was returned");
            } else {
                // load the HTTP codes
               /* $http_codes = parse_ini_file("path/to/the/ini/file/I/pasted/above");

                // echo results
                echo "The server responded: <br />";
                echo $info['http_code'] . " " . $http_codes[$info['http_code']];
                * 
                */
            }
             return $output;
        }
        //print_r($output);
       
    }

    public function createNew($data) {
        //http://172.29.4.99:8083/ws/standardization/term/parse
        $url = "http://172.29.4.99:8083/ws/standardization/term/createNew";
        //echo "<br>here";
        return $this->exec($url, $data);
    }

    public function updateGermplasmName($data) {
        //http://172.29.4.99:8083/ws/standardization/term/parse
        $url = "http://172.29.4.99:8083/ws/standardization/term/updateGermplasmName";
        //echo "<br>here";
        return $this->exec($url, $data);
    }

    public function parse($data) {
        //http://172.29.4.99:8083/ws/standardization/term/parse
        $url = "http://172.29.4.99:8083/ws/standardization/term/post";

        return $this->exec($url, $data);
    }

    public function standardize($data) {
        $url = "http://172.29.4.99:8083/ws/standardization/term/standardize2";
        return $this->exec($url, $data);
    }

    public function createGID($data) {
        $url = "http://172.29.4.99:8083/ws/standardization/term/createGID2";
        return $this->exec($url, $data);
    }

    public function createGID2($data) {
        $url = "http://172.29.4.99:8083/ws/standardization/term/createGID3";
        return $this->exec($url, $data);
    }

    public function chooseGID($data) {
        $url = "http://172.29.4.99:8083/ws/standardization/term/chooseGID2";
        return $this->exec($url, $data);
    }

    public function updateMethod($data) {
        $url = "http://172.29.4.99:8083/ws/standardization/term/updateMethod";
        return $this->exec($url, $data);
    }

    public function chooseGID_cross($data) {
        $url = "http://172.29.4.99:8083/ws/standardization/term/chooseGID_cross";
        return $this->exec($url, $data);
    }

    public function editGermplasmName($data) {
        $url = "http://172.29.4.99:8083/ws/standardization/term/checkEditedString";
        return $this->exec($url, $data);
    }

    public function show_germplasm_details() {
        $url = "http://172.29.4.99:8083/ws/standardization/term/show_germplasm_details";
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
        echo '<br/>gid:';
        print_r($gid);
        $level = $_POST['maxStep'];
        if (isset($_POST['cbox'])) {
            $selhis = '1';
        }
        else
            $selhis = '0';

        //database settings
        $local_db_host = Yii::app()->request->getParam('local_db_host');
        $local_db_name = Yii::app()->request->getParam('local_db_name');
        $local_db_port = Yii::app()->request->getParam('local_db_port');
        $local_db_username = Yii::app()->request->getParam('local_db_username');
        $local_db_password = Yii::app()->request->getParam('local_db_password');
        $central_db_host = Yii::app()->request->getParam('central_db_host');
        $central_db_name = Yii::app()->request->getParam('central_db_name');
        $central_db_port = Yii::app()->request->getParam('central_db_port');
        $central_db_username = Yii::app()->request->getParam('central_db_username');
        $central_db_password = Yii::app()->request->getParam('central_db_password');

        $a = array(
            'GID' => $gid,
            'LEVEL' => $level,
            'SEL' => $selhis,
            'local_db_host' => $local_db_host,
            'local_db_name' => $local_db_name,
            'local_db_port' => $local_db_port,
            'local_db_username' => $local_db_username,
            'local_db_password' => $local_db_password,
            'central_db_host' => $central_db_host,
            'central_db_name' => $central_db_name,
            'central_db_port' => $central_db_port,
            'central_db_username' => $central_db_username,
            'central_db_password' => $central_db_password
        );


        $data = json_encode($a);

        $url = "http://172.29.4.99:8083/ws/standardization/term/searchGID";

        $this->exec($url, $data);

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


        return $try;
    }

    public function showDiagram($in, $max) {

        $gid = $in; //$_GET['inputGID'];
        $level = $max; //$_GET['maxStep'];
        if (isset($_POST['cbox'])) {
            $selhis = '1';
        }
        else
            $selhis = '0';

        //database settings
        $local_db_host = Yii::app()->request->getParam('local_db_host');
        $local_db_name = Yii::app()->request->getParam('local_db_name');
        $local_db_port = Yii::app()->request->getParam('local_db_port');
        $local_db_username = Yii::app()->request->getParam('local_db_username');
        $local_db_password = Yii::app()->request->getParam('local_db_password');
        $central_db_host = Yii::app()->request->getParam('central_db_host');
        $central_db_name = Yii::app()->request->getParam('central_db_name');
        $central_db_port = Yii::app()->request->getParam('central_db_port');
        $central_db_username = Yii::app()->request->getParam('central_db_username');
        $central_db_password = Yii::app()->request->getParam('central_db_password');

        $a = array(
            'GID' => $gid,
            'LEVEL' => $level,
            'SEL' => $selhis,
            'local_db_host' => $local_db_host,
            'local_db_name' => $local_db_name,
            'local_db_port' => $local_db_port,
            'local_db_username' => $local_db_username,
            'local_db_password' => $local_db_password,
            'central_db_host' => $central_db_host,
            'central_db_name' => $central_db_name,
            'central_db_port' => $central_db_port,
            'central_db_username' => $central_db_username,
            'central_db_password' => $central_db_password
        );
        $data = json_encode($a);

        $url = "http://172.29.4.99:8083/ws/standardization/term/searchGID";
        $this->exec($url, $data);

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

    public function editGermplasm($data) {
        $url = "http://172.29.4.99:8083/ws/standardization/term/editGermplasm";

        $this->exec($url, $data);

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
