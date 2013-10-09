<?php 
 //Yii::import('application.modules.configDB');
/*
if (!$model->CheckLogin()) {
    $model->RedirectToURL("login.php");
    exit;
}*/

if (isset($_POST["editGermplasmForm"]['newGermplasmName'])) {
    $germplasm = $_POST["editGermplasmForm"]['germplasmName'];
    $new = $_POST["editGermplasmForm"]['newGermplasmName'];
    $myfile = dirname(__FILE__).'/../../modules/corrected.csv';
    //echo $myfile;
    if (callCurl($new) == true) {
        $myfile = dirname(__FILE__).'/../../modules/newString.csv';

		
        $fin = fopen($myfile, 'r');
        $data = array();

        while ($line = fgetcsv($fin, 0)) {
            echo join(', ', $line) . '<br>';
            $error = $line[2];
            $gid = $line[3];
            echo "errrror: " . $error . "<br>";
            echo "gid: " . $gid . "<br>";
        }
        fclose($fin);
        
        //open corrected.csv and process
        $myfile = dirname(__FILE__).'/../../modules/corrected.csv';
        
        $fin = fopen($myfile, 'r');
        $data = array();

        while ($line = fgetcsv($fin, 0)) {
            //echo join(', ', $line).'<br>';
            for ($i = 5, $k = count($line); $i < $k; $i++) {
                if ($i == 5) {
                    if (strcmp($line[$i], $germplasm) == 0) {
                        //echo"heree female"."<br>";
                        $line[$i] = $new;
                        $line[3] = 'in standardized format';
                        $line[4] = $gid;
                    }
                } else if ($i == 9) {
                    if (strcmp($line[$i], $germplasm) == 0) {
                        //echo"heree male"."<br>";
                        $line[$i] = $new;
                        $line[7] = 'in standardized format';
                        $line[8] = $gid;
                    }
                }
            }
            $data[] = $line;
        }
        fclose($fin);
        //print_r($data);
        
        $fout = fopen($myfile, 'w');
        foreach ($data as $line) {
            fputcsv($fout, $line);
        }
       fclose($fout);
       header("Location: /GMGR/index.php?r=site/standardTable");
       // Yii::app()->createUrl("site/standardTable");
        die();
    } else { 
	
        echo "</br></br></br><p class='important'><strong>ERROR:</strong>&nbsp; 
				Germplasm name is not in standardized format. Please edit the germplasm name. Hint is next to germplasm name text box
			  </p>";
       $myfile = dirname(__FILE__).'/../../modules/newString.csv';

        $fin = fopen($myfile, 'r');
        $data = array();

        while ($line = fgetcsv($fin, 0)) {
            $error = $line[2];
        }
        $your_array = array();
        $your_array = explode("#", $error);
        $your_array = implode("\n", $your_array);
        $error = $your_array;
        fclose($fin);
    }
} else {
    echo "Error";
}

function callCurl($new) {
    echo "new:".$new;
    $jsonfile = dirname(__FILE__)."/../../modules/docinfo.json";
    
    $a = array('new' => $new);
    $jsonText = json_encode($a);
    file_put_contents($jsonfile, $jsonText);
    
    $url = "http://localhost:8080/ws/standardization/term/checkEditedString";
    
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL, $url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($handle);
    $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
   
    $myfile = dirname(__FILE__).'/../../modules/newString.csv';

    $fin = fopen($myfile, 'r');

    while ($line = fgetcsv($fin, 0)) {
        if (strcmp($line[2], 'in standardized format') == 0) {
            return true;
        }
        else
            return false;
    }
    fclose($fin);
    return false;
}
?>


</body>
</div>
</html>
