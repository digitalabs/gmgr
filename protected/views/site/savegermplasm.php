

<?php
Yii::import('application.modules.curl');
//Yii::import('application.modules.configDB');
/*
  if (!$model->CheckLogin()) {
  $model->RedirectToURL("login.php");
  exit;
  } */

if (isset($_POST["editGermplasmForm"]['newGermplasmName'])) {
    $germplasm = $_POST["editGermplasmForm"]['germplasmName'];
    $new = $_POST["editGermplasmForm"]['newGermplasmName'];
    $list_array = json_decode($_POST['list']);

    $output = callCurl($new, $list_array, $germplasm);
    var_dump($output);
    echo "<br>";
    $list_array = $output['list'];

    if ($output['updated'] == true) {
        ?>
        <body onload="storeLocal()">
        </body>
        <script type="text/javascript">
            function storeLocal() {
                if ('localStorage' in window && window['localStorage'] != null) {
                    try {
                        console.log(JSON.stringify(<?php echo json_encode($list_array); ?>));
                        localStorage.setItem('list', JSON.stringify(<?php echo json_encode($list_array); ?>));
                    } catch (e) {
                        if (e === QUOTA_EXCEEDED_ERR) {
                            alert('Quota exceeded!');
                        }
                    }
                } else {
                    alert('Cannot store user preferences as your browser do not support local storage');
                }
            }
            window.addEventListener('storage', storageEventHandler, false);
            function storageEventHandler(event) {
                storeLocal();
            }
        </script>
        <?php
        header("Location: /gmgr/index.php?r=site/output");
        // Yii::app()->createUrl("site/standardTable");
         die();
    } else {

        echo "</br></br></br><p class='important'><strong>ERROR:</strong>&nbsp; 
				Germplasm name is not in standardized format. Please edit the germplasm name. Hint is next to germplasm name text box
			  </p>";
        $error = $output['newString'][2];
        $gid = $output['newString'][3];
        echo "errrror: " . $error . "<br>";
        echo "gid: " . $gid . "<br>";

        $your_array = array();
        $your_array = explode("#", $error);
        $your_array = implode("\n", $your_array);
        $error = $your_array;
    }
} else {
    echo "Error";
}

function callCurl($new, $list_array, $old) {
    echo "new:" . $new;

    $a = array('new' => $new, 'list' => $list_array, 'old' => $old);
    $data = json_encode($a);
    $curl = new curl();
    $list = $curl->updateGermplasmName($data);

    return $list;
}
?>





</html>
